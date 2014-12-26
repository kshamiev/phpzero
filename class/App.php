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
define('ZERO_PATH_LOG', dirname(ZERO_PATH_SITE) . '/log');
/**
 * The location of the site log
 */
define('ZERO_PATH_SESSION', dirname(ZERO_PATH_SITE) . '/session');
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
 * Component. Application.
 *
 * The main component of execute the application as a whole.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_App
{

    /**
     * An array of abstract and key additional application variables
     *
     * @var array
     */
    private static $_Variable = [];

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
     * Getting the application variables
     *
     * @param string $variable
     * @return mixed value
     */
    public static function Get_Variable($variable)
    {
        return isset(self::$_Variable[$variable]) ? self::$_Variable[$variable] : null;
    }

    /**
     * Setting the application variables
     *
     * @param string $variable
     * @param mixed $value
     */
    public static function Set_Variable($variable, $value)
    {
        self::$_Variable[$variable] = $value;
    }

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
            return true;
        }
        Zero_Logs::Set_Message_Error('Класс не найден: ' . $class_name);
        return false;
    }

    public static function RequestJson($method, $url, $content)
    {
        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $opts = array(
            'http' => array(
                'method' => $method,
                'header' => "Content-Type: tapplication/json; charset=utf-8\r\n" . "Content-Length: " . strlen($content) . "\r\n" . "",
                'content' => $content,
                'timeout' => 30,
            )
        );
        $fp = fopen($url, 'rb', false, stream_context_create($opts));
        $response = stream_get_contents($fp);
        fclose($fp);
        return json_decode($response, true);
    }

    /**
     * Сборка ответа клиенту
     *
     * @param mixed $content Отдаваемые данные
     * @param int $code Код ошибки
     * @param string $message Сообщение
     * @return bool
     */
    public static function ResponseJson($content, $code, $message = '')
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header('HTTP/1.1 ' . $code . ' ' . $code);
        $data = [
            'Code' => $code,
            'Message' => $message,
        ];
        if ( $content )
            $data['Content'] = $content;
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::File();
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
            Zero_Logs::File();
        exit;
    }

    public static function ResponseImg($path)
    {
        header("Content-Type: " . Zero_Lib_File::File_Type($path));
        header("Content-Length: " . filesize($path));
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    public static function ResponseFile($path)
    {
        header("Content-Type: " . Zero_Lib_File::File_Type($path));
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
    public static function Init($file_log = 'application', $mode = 'web')
    {
        //  Include Components
        require_once ZERO_PATH_ZERO . '/class/Config.php';
        require_once ZERO_PATH_ZERO . '/class/Session.php';
        require_once ZERO_PATH_ZERO . '/class/Cache.php';
        require_once ZERO_PATH_ZERO . '/class/Logs.php';
        require_once ZERO_PATH_ZERO . '/class/Route.php';
        require_once ZERO_PATH_ZERO . '/class/DB.php';
        self::$Mode = $mode;

        //  Configuration (Zero_Config)
        self::$Config = new Zero_Config($file_log);

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        //  Initialize cache subsystem (Zero_Cache)
        if ( class_exists('Memcache') && 0 < count(self::$Config->Memcache['Cache']) )
            Zero_Cache::InitMemcache(self::$Config->Memcache['Cache']);

        // DB init config
        foreach (self::$Config->Db as $name => $config)
        {
            Zero_DB::Add_Config($name, $config);
        }

        //  Processing incoming request (Zero_Route)
        self::$Route = new Zero_Route();

        spl_autoload_register(['Zero_App', 'Autoload']);

        //  Session Initialization (Zero_Session)
        Zero_Session::Init(self::$Config->Site_Domain);

        require_once ZERO_PATH_ZERO . '/class/View.php';

        // Инициализация входных параметров и данных в случае api
        if ( 'api' == self::$Mode )
        {
            if ( $_SERVER['REQUEST_METHOD'] === "PUT" )
            {
                $data = file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']);
                $_POST = json_decode($data, true);
            }
            else if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($GLOBALS["HTTP_RAW_POST_DATA"]) )
            {
                $_POST = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
            }
        }
    }

    public static function ExecuteSimple()
    {
        // General Authorization Application
        if ( self::$Config->Site_AccessLogin )
            if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != self::$Config->Site_AccessLogin || $_SERVER['PHP_AUTH_PW'] != self::$Config->Site_AccessPassword )
            {
                header('WWW-Authenticate: Basic realm="Auth"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Auth Failed';
                exit;
            }

        //  Инициализация запрошенного раздела (Www_Section)
        // TODO Доработать инициализацию пользователя по токену и исключение если он есть а пользователя нет - либо в гостя его
        if ( isset($_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il']) && 0 < $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il'] )
        {
            self::$Users = Zero_Model::Factory('Www_Users', $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il']);
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il'], time() + 2592000, '/');
        }
        else
        {
            self::$Users = Zero_Model::Factory('Www_Users');
        }

        $route = [];
        foreach (Zero_Lib_File::Get_Modules() as $module)
        {
            $config = Zero_Lib_File::Get_Config($module, 'route');
            if ( isset($config['route'][ZERO_URL]) )
                $route = $config['route'][ZERO_URL];
                break;
        }
        if ( 0 == count($route) )
            self::ResponseError(404);

        //  Execute controller
        $view = '';
        Zero_App::Set_Variable('action_message', []);
        if ( isset($route['Controller']) && $route['Controller'] )
        {
            $routeDetails = explode('-', $route['Controller']);
            if ( 1 == count($routeDetails) )
                $routeDetails[1] = 'Default';
                //throw new Exception('контроллер определен неправильно: ' . $route['Controller'], 409);
            //
            if ( !isset($_REQUEST['act']) )
                $_REQUEST['act'] = $routeDetails[1];
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];
            //
            $Controller = Zero_Controller::Factory($routeDetails[0]);
            Zero_Logs::Start('#{CONTROLLER.Action} ' . $routeDetails[0] . ' -> ' . $_REQUEST['act']);
            $view = $Controller->$_REQUEST['act']();
            if ( $_REQUEST['act'] != 'Action_Default' )
                Zero_Logs::Set_Message_Action($_REQUEST['act']);
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . $routeDetails[0] . ' -> ' . $_REQUEST['act']);
            Zero_App::Set_Variable('action_message', $Controller->Get_Message());
        }

        // Основные данные
        if ( isset($route['View']) && $route['View'] )
        {
            $viewLayout = new Zero_View($route['View']);
            if ( true == $view instanceof Zero_View )
            {
                /* @var $view Zero_View */
                $viewLayout->Assign('Content', $view->Fetch());
            }
            else
                $viewLayout->Assign('Content', $view);
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
        // General Authorization Application
        if ( self::$Config->Site_AccessLogin )
            if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != self::$Config->Site_AccessLogin || $_SERVER['PHP_AUTH_PW'] != self::$Config->Site_AccessPassword )
            {
                header('WWW-Authenticate: Basic realm="Auth"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Auth Failed';
                exit;
            }

        //  Инициализация запрошенного раздела (Www_Section)
        if ( isset($_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il']) && 0 < $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il'] )
        {
            self::$Users = Zero_Model::Factory('Www_Users', $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il']);
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', $_COOKIE['i09u9Maf6l6sr7Um0m8A3u0r9i55m3il'], time() + 2592000, '/');
        }
        else
        {
            self::$Users = Zero_Model::Factory('Www_Users');
        }

        self::$Section = Zero_Model::Instance('Www_Section');
        //  Checking for non-existent section
        if ( 0 == self::$Section->ID || 'no' == self::$Section->IsEnable )
            self::ResponseError(404);

        //  Call forwarding
        if ( self::$Section->UrlRedirect )
            self::ResponseRedirect(self::$Section->UrlRedirect);

        //  Checking the rights to the current section
        $Action_List = self::$Section->Get_Action_List();
        if ( 1 < self::$Users->Groups_ID && 'yes' == self::$Section->IsAuthorized && 0 == count($Action_List) )
            self::ResponseError(403);

        //  Execute controller
        $view = "";
        self::Set_Variable('action_message', []);
        if ( self::$Section->Controller )
        {
            if ( !isset($_REQUEST['act']) )
                $_REQUEST['act'] = 'Default';
            if ( !isset($Action_List[$_REQUEST['act']]) )
                self::ResponseError(403);
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];
            //
            $Controller = Zero_Controller::Factory(self::$Section->Controller);
            Zero_Logs::Start('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            $view = $Controller->$_REQUEST['act']();
            if ( $_REQUEST['act'] != 'Action_Default' )
                Zero_Logs::Set_Message_Action($_REQUEST['act']);
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            Zero_App::Set_Variable('action_message', $Controller->Get_Message());
        }

        // Основные данные
        if ( self::$Section->Layout )
        {
            $viewLayout = new Zero_View(self::$Section->Layout);
            if ( true == $view instanceof Zero_View )
            {
                /* @var $view Zero_View */
                $viewLayout->Assign('Content', $view->Fetch());
            }
            else
                $viewLayout->Assign('Content', $view);
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
        header('Location: ' . $url);
        header('HTTP/1.1 301 Redirect');
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
        Zero_Logs::Exception($exception);

        $code = $exception->getCode();

        if ( Zero_App::$Mode == 'console' || !isset($_SERVER['REQUEST_URI']) )
            self::ResponseConsole();
        else if ( Zero_App::$Mode == 'api' )
            self::ResponseJson('', $code, $exception->getMessage());
        else if ( Zero_App::$Mode == 'web' )
            self::ResponseError($code);
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    public static function ResponseHtml($view, $code)
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: text/html; charset=utf-8");
        header('HTTP/1.1 ' . $code . ' ' . $code);
        echo $view;

        // Логирование (в браузер)
        if ( self::$Config->Log_Output_Display )
            echo Zero_Logs::Display();

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::File();
        exit;
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    public static function ResponseError($code)
    {
        $View = new Zero_View(ucfirst(self::$Config->Site_DomainSub) . '_Error');
        $View->Template_Add('Zero_Error');
        $View->Assign('http_status', $code);
        self::ResponseHtml($View->Fetch(), $code);
    }
}

/*
$opts_sample = array(
    'http' => array(
        'method' => 'POST',
        'header' => "Content-Type: text/xml; charset=utf-8\r\n" . "Content-Type: tapplication/json; charset=utf-8\r\n" . "Content-Length: 10000\r\n" . "Referer: http://ya.ru\r\n" . "Host: hostkey.ru\r\n" . "Connection: close\r\n" . "Cookie: foo=bar\r\n" . "Accept-language: en\r\n" . "Authorization: Basic " . base64_encode("login:password") . "\r\n",
        'content' => 'bla bla bla',
        'timeout' => 60,
    )
);
*/