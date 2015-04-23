<?php
/**
 * The version of PhpZero
 */
define('VERSION_PHPZERO', '2.0.0');
/**
 * The absolute path to the project (site)
 */
define('ZERO_PATH_SITE', dirname(dirname(__DIR__)));
/**
 * Location of binary data
 */
define('ZERO_PATH_DATA', ZERO_PATH_SITE . '/upload/data');
/**
 * The location of the site log
 */
define('ZERO_PATH_LIBRARY', ZERO_PATH_SITE . '/library');
/**
 * The location of the site log
 */
define('ZERO_PATH_LOG', ZERO_PATH_SITE . '/log');
/**
 * Location cache
 */
define('ZERO_PATH_CACHE', ZERO_PATH_SITE . '/cache');
/**
 * Communication with the outside world
 */
define('ZERO_PATH_EXCHANGE', ZERO_PATH_SITE . '/exchange');
/**
 * Location applications and modules
 */
define('ZERO_PATH_APPLICATION', ZERO_PATH_SITE . '/application');
/**
 * Location templates grouped by subject
 */
define('ZERO_PATH_VIEW', ZERO_PATH_SITE . '/view');
/**
 * Location System
 */
define('ZERO_PATH_ZERO', ZERO_PATH_SITE . '/zero');

/**
 * Application.
 *
 * The main component of execute the application as a whole.
 *
 * @package General.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_App
{
    const MODE_WEB = 'web';
    const MODE_API = 'web';
    const MODE_CONSOLE = 'console';
    /**
     * Режим работы приложения (api, web, console).
     *
     * @var string
     */
    public static $Mode = '';

    /**
     * Configuration
     *
     * @var Zero_Config
     */
    public static $Config;

    /**
     * Routing (по URL)
     *
     * @var Zero_Route
     */
    public static $Route;

    /**
     * User
     *
     * @var Www_Users
     */
    public static $Users;

    /**
     * Section (page)
     *
     * @var Www_Section
     */
    public static $Section;

    /**
     * Connection classes
     *
     * Setting up automatic downloads of files with the required classes
     *
     * @param string $class_name
     * @return bool
     */
    public static function Autoload($class_name)
    {
        if ( class_exists($class_name) )
            return true;
        $arr = explode('_', $class_name);
        $module = strtolower(array_shift($arr));
        $class = implode('/', $arr);
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/class/' . $class . '.php';
        if ( file_exists($path) )
        {
            require_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        Zero_Logs::Set_Message_Error('Класс не найден: ' . $class_name);
        return false;
    }

    public static function RequestJson($method, $url, $content = '')
    {
        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $opts = array(
            'http' => array(
                'method' => $method,
                'header' => "Content-Type: application/json; charset=utf-8\r\n" . "Content-Length: " . strlen($content) . "\r\n" . "",
                'content' => $content,
                'timeout' => 30,
            )
        );
        $fp = fopen($url, 'rb', false, stream_context_create($opts));
        $response = stream_get_contents($fp);
        fclose($fp);
        $data = json_decode($response, true);
        if ( !$data )
            return $response;
        return $data;
    }

//    public static function RequestJsonHttps($method, $url, $content = '')
//    {
//        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
//        $opts = array(
//            'https' => array(
//                'method' => $method,
//                'header' => "Content-Type: application/json; charset=utf-8\r\n" . "Content-Length: " . strlen($content) . "\r\n" . "",
//                'content' => $content,
//                'timeout' => 30,
//            )
//        );
//        $fp = fopen($url, 'rb', false, stream_context_create($opts));
//        $response = stream_get_contents($fp);
//        fclose($fp);
//        $data = json_decode($response, true);
//        if ( !$data )
//            return $response;
//        return $data;
//    }

    /**
     * Сборка ответа клиенту
     *
     * @param mixed $content Отдаваемые данные
     * @param int $code Код ошибки
     * @param string $message Сообщение
     * @return bool
     */
    public static function ResponseJson($content, $status, $code = 0, $params = [])
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header('HTTP/1.1 ' . $status . ' ' . $status);
        $message = Zero_I18n::CodeMessage(self::$Section->Controller, $code, $params);
        $data = [
            'Code' => $message[0],
            'Message' => $message[1],
        ];
        if ( $content )
            $data['Content'] = $content;
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output();
        exit;
    }

    /**
     * Сборка ответа клиенту
     *
     * @return bool
     */
    public static function ResponseConsole()
    {
        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( self::$Config->Log_Output_File )
            Zero_Logs::Output();
        exit;
    }

    public static function ResponseImg($path)
    {
        header("Content-Type: " . Zero_Helper_File::File_Type($path));
        header("Content-Length: " . filesize($path));
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    public static function ResponseFile($path)
    {
        header("Content-Type: " . Zero_Helper_File::File_Type($path));
        header("Content-Length: " . filesize($path));
        header('Content-Disposition: attachment; filename = "' . basename($path) . '"');
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    /**
     * Application initialization
     *
     * - Include Components (Zero_Config, (Zero_Route,) Zero_Session, Zero_Cache, Zero_Logs, Zero_View)
     * - Monitoring of the work application. Component Zero_Logs
     * - The configuration and initialization of the application. Component Zero_Config
     * - Initialize cache subsystem. Component Zero_Cache
     * - Processing incoming request (GET). Component Zero_Route
     * - Session Initialization. Component Zero_Session
     *
     * @param string $file_log the base name of the log file
     * @param string $mode the base name of the log file
     */
    public static function Init($file_log = 'application')
    {
        //  Include Components
        require_once ZERO_PATH_ZERO . '/class/Config.php';
        require_once ZERO_PATH_ZERO . '/class/Logs.php';
        require_once ZERO_PATH_ZERO . '/class/DB.php';
        require_once ZERO_PATH_ZERO . '/class/Session.php';
        require_once ZERO_PATH_ZERO . '/class/Cache.php';
        require_once ZERO_PATH_ZERO . '/class/View.php';
        require_once ZERO_PATH_ZERO . '/function.php';
        require_once ZERO_PATH_APPLICATION . '/function.php';

        spl_autoload_register(['Zero_App', 'Autoload']);
        set_exception_handler(['Zero_App', 'Exception']);
        // register_shutdown_function(['Zero_App', 'Exit_Application']);

        //  Configuration (Zero_Config)
        self::$Config = new Zero_Config($file_log);

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        // DB init config
        foreach (self::$Config->Db as $name => $config)
        {
            Zero_DB::Config_Add($name, $config);
        }

        //  Session Initialization (Zero_Session)
        Zero_Session::Init(self::$Config->Site_Domain);

        //  Initialize cache subsystem (Zero_Cache)
        if ( class_exists('Memcache') && 0 < count(self::$Config->Memcache['Cache']) )
            Zero_Cache::InitMemcache(self::$Config->Memcache['Cache']);

        require_once ZERO_PATH_ZERO . '/constant.php';
    }

    public static function ExecuteSimple()
    {
        // Пользователь
        self::$Users = Zero_Model::Factories('Www_Users');

        // Раздел - страница
        $route = [];
        self::$Section = Zero_Model::Makes('Www_Section');
        foreach (Zero_Config::Get_Modules() as $module)
        {
            $config = Zero_Config::Get_Config($module, self::$Mode . 'Route');
            if ( isset($config[ZERO_URL]) )
            {
                $route = $config[ZERO_URL];
                break;
            }
        }
        if ( 0 == count($route) )
            throw new Exception('Page Not Found', 404);

        //  Выполнение контроллера
        $view = '';
        $messageResponse = ['Code' => 0, 'Message' => ''];
        if ( isset($route['Controller']) && $route['Controller'] )
        {
            self::$Section->Controller = $route['Controller'];
            if ( isset($_REQUEST['act']) && $_REQUEST['act'] )
                $_REQUEST['act'] = trim($_REQUEST['act']);
            else if ( 'api' == self::$Mode )
                $_REQUEST['act'] = $_SERVER['REQUEST_METHOD'];
            else
                $_REQUEST['act'] = 'Default';
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];

            Zero_Logs::Start('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            $Controller = Zero_Controller::Factories(self::$Section->Controller);
            if ( !method_exists($Controller, $_REQUEST['act']) )
            {
                throw new Exception('Контроллер не имеет метода: ' . $_REQUEST['act'], -1);
            }
            if ( $_REQUEST['act'] != 'Action_Default' )
            {
                Zero_Logs::Set_Message_Action($_REQUEST['act']);
            }
            $view = $Controller->$_REQUEST['act']();
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);

            $messageResponse = $Controller->GetMessage();
        }

        // Сборка ст раницы на основании макета
        if ( isset($route['View']) && $route['View'] )
        {
            $viewLayout = new Zero_View($route['View']);
            $viewLayout->Assign('Message', $messageResponse);
            if ( true == $view instanceof Zero_View )
            {
                /* @var $view Zero_View */
                $view->Assign('Message', $messageResponse);
                $viewLayout->Assign('Content', $view->Fetch());
            }
            else
            {
                $viewLayout->Assign('Content', $view);
            }
            $view = $viewLayout->Fetch();
        }
        self::ResponseHtml($view, 200);
    }

    /**
     * Method of Application Execution
     *
     * - Инициализация запрошенного раздела (ZSection)
     * - Инициализация пользователя (Users)
     * - Инициализация и выполнение контролера и его действия
     * - Формирование и вывод профилированного результата
     *
     * - Initialization of the requested section (Section)
     * - User Initialization (Users)
     * - Initialization and execution of the controller and its actions
     * - The formation results and conclusion of the profiled format
     *
     * @throws Exception
     */
    public static function Execute()
    {
        //  Пользователь
        self::$Users = Zero_Model::Factories('Www_Users');

        //  Раздел - страница
        self::$Section = Zero_Model::Instances('Www_Section');
        if ( 0 == self::$Section->ID || 'no' == self::$Section->IsEnable )
            throw new Exception('Page Not Found', 404);
        if ( self::$Section->UrlRedirect )
            self::ResponseRedirect(self::$Section->UrlRedirect);

        //  Доступные операции контроллера раздела с учетом прав. Проверка прав на раздел
        $Action_List = self::$Section->Get_Action_List();
        if ( 1 < self::$Users->Groups_ID && 'yes' == self::$Section->IsAuthorized && 0 == count($Action_List) )
            throw new Exception('Page Forbidden', 403);

        //  Выполнение контроллера
        $view = "";
        $messageResponse = ['Code' => 0, 'Message' => ''];
        if ( self::$Section->Controller )
        {
            if ( isset($_REQUEST['act']) && $_REQUEST['act'] )
                $_REQUEST['act'] = trim($_REQUEST['act']);
            else if ( 'api' == self::$Mode )
                $_REQUEST['act'] = $_SERVER['REQUEST_METHOD'];
            else
                $_REQUEST['act'] = 'Default';
            //
            if ( !isset($Action_List[$_REQUEST['act']]) )
                throw new Exception('Page Forbidden', 403);
            //
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];

            Zero_Logs::Start('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            $Controller = Zero_Controller::Factories(self::$Section->Controller);
            if ( !method_exists($Controller, $_REQUEST['act']) )
            {
                throw new Exception('Контроллер не имеет метода: ' . $_REQUEST['act'], -1);
            }
            if ( $_REQUEST['act'] != 'Action_Default' )
            {
                Zero_Logs::Set_Message_Action($_REQUEST['act']);
            }
            $view = $Controller->$_REQUEST['act']();
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);

            $messageResponse = $Controller->GetMessage();
        }

        // Сборка ст раницы на основании макета
        if ( self::$Section->Layout )
        {
            $viewLayout = new Zero_View(self::$Section->Layout);
            $viewLayout->Assign('Message', $messageResponse);
            if ( true == $view instanceof Zero_View )
            {
                /* @var $view Zero_View */
                $view->Assign('Message', $messageResponse);
                $viewLayout->Assign('Content', $view->Fetch());
            }
            else
            {
                $viewLayout->Assign('Content', $view);
            }
            $view = $viewLayout->Fetch();
        }
        self::ResponseHtml($view, 200);
    }

    /**
     * Redirect to the specified page
     *
     * @param string $url link to which page to produce redirect
     */
    public static function ResponseRedirect($url)
    {
        header('HTTP/1.1 301 Redirect');
        header('Location: ' . $url);
        exit;
    }

    /**
     * Sending headers browser
     *
     * The possible values $type
     * - 'html' document html (или просто текст) в макете
     * - 'json' data format json
     * - 'xml' data xml
     * - 'img' binary data, output images
     * - 'file' binary data for download
     *
     */
    /**
     * Obrabotchik oshibok dlia funktcii set_error_handler()
     *
     * @param int $code kod oshibki
     * @param string $message soobshchenie ob oshibke
     * @param string $filename fai`l v kotorom proizoshla oshibka
     * @param string $line stroka, v kotoroi` proizoshla oshibka
     * @throws ErrorException
     */
    //    public static function ErrorHandler($code, $message, $filename, $line)
    //    {
    //        throw new ErrorException($message, $code, 0, $filename, $line);
    //    }

    /**
     * Obrabotchik iscliuchenii` dlia funktcii set_exception_handler()
     *
     * - '403' standartny`i` otvet na zakry`ty`i` razdel (stranitcu sai`ta)
     * - '404' standartny`i` otvet ne nai`dennogo dokumenta
     * - '500' vse ostal`ny`e kriticheskie oshibki prilozheniia libo servera
     *
     * @param Exception $exception
     */
    public static function Exception(Exception $exception)
    {
        $code = $exception->getCode();
        if ( $code < 0 || 999 < $code )
        {
            Zero_Logs::Exception($exception);
        }
        if ( Zero_App::$Mode == 'console' || !isset($_SERVER['REQUEST_URI']) )
            self::ResponseConsole();
        else if ( Zero_App::$Mode == 'api' )
            self::ResponseJson('', 200, $code, [$exception->getMessage()]);
        else if ( Zero_App::$Mode == 'web' )
        {
            $View = new Zero_View(ucfirst(self::$Config->Site_DomainSub) . '_Error');
            $View->Add('Zero_Error');
            $View->Assign('http_status', $code);
            $View->Assign('message', $exception->getMessage());
            self::ResponseHtml($View->Fetch(), $code);
        }
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    public static function ResponseHtml($content, $status)
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: text/html; charset=utf-8");
        header('HTTP/1.1 ' . $status . ' ' . $status);
        echo $content;

        // Логирование (в браузер)
        if ( self::$Config->Log_Output_Display )
            echo Zero_Logs::Display();

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
        {
            Zero_Logs::Output();
        }
        exit;
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    //    public static function ResponseError($code)
    //    {
    //        $View = new Zero_View(ucfirst(self::$Config->Site_DomainSub) . '_Error');
    //        $View->Add('Zero_Error');
    //        $View->Assign('http_status', $code);
    //        self::ResponseHtml($View->Fetch(), $code);
    //    }
}
