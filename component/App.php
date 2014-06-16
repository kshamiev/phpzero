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
define('ZERO_PATH_LOG', dirname(ZERO_PATH_SITE) . '/log');
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
    public static $Mode = 'web';

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
     * @var Zero_Users
     */
    public static $Users;

    /**
     * Section (page)
     *
     * @var Zero_Section
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
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/component/' . $class . '.php';
        if ( file_exists($path) )
        {
            require_once $path;
            return true;
        }
        echo $path . '<br>';
        return false;
    }

    /**
     * Сборка ответа клиенту
     *
     * @param mixed $value Отдаваемые данные
     * @param int $code Код ошибки
     * @param string $message Сообщение
     * @param string $messageDebug Служебное сообщение
     * @return bool
     */
    public static function ResponseJson($value, $code, $message, $messageDebug = "")
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
            'MessageDebug' => "" != $messageDebug ? $messageDebug : $message,
            'Data' => $value,
        ];
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
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
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    public static function ResponseImg($path)
    {
        header("Content-Type: " . Zero_Lib_FileSystem::File_Type($path));
        header("Content-Length: " . filesize($path));
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    public static function ResponseFile($path)
    {
        header("Content-Type: " . Zero_Lib_FileSystem::File_Type($path));
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
     * @param string $mode режим работы приложения
     * @param string $file_log the base name of the log file
     */
    public static function Init($mode = 'web', $file_log = 'application')
    {
        //  Include Components
        require_once ZERO_PATH_ZERO . '/component/Config.php';
        require_once ZERO_PATH_ZERO . '/component/Session.php';
        require_once ZERO_PATH_ZERO . '/component/Cache.php';
        require_once ZERO_PATH_ZERO . '/component/Logs.php';
        require_once ZERO_PATH_ZERO . '/component/Route.php';

        spl_autoload_register(['Zero_App', 'Autoload']);

        self::$Mode = $mode;

        //  Configuration (Zero_Config)
        self::$Config = new Zero_Config();

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        //  Initialize cache subsystem (Zero_Cache)
        Zero_Cache::Init(Zero_App::$Config->Memcache['Cache']);

        //  Processing incoming request (Zero_Route)
        self::$Route = new Www_Route();

        //  Session Initialization (Zero_Session)
        Zero_Session::Init(self::$Config->Db['Name']);

        require_once ZERO_PATH_ZERO . '/component/View.php';

        // Initialization of the profiled application processors
        //        error_reporting(2147483647);
        set_error_handler(['Zero_App', 'Handler_Error'], 2147483647);
        set_exception_handler(['Zero_App', 'Handler_Exception']);
        //        register_shutdown_function(['Zero_App', 'Exit_Application']);

    }

    /**
     * Method of Application Execution
     *
     * - Инициализация запрошенного раздела (Zero_Section)
     * - Инициализация пользователя (Zero_Users)
     * - Инициализация и выполнение контролера и его действия
     * - Формирование и вывод профилированного результата
     *
     * - Initialization of the requested section (Zero_Section)
     * - User Initialization (Zero_Users)
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

        //  Инициализация запрошенного раздела (Zero_Section)
        self::$Section = Zero_Model::Instance('Www_Section');
        self::$Users = Zero_Model::Factory('Www_Users');

        //  Checking for non-existent section
        if ( 0 == self::$Section->ID || 'no' == self::$Section->IsEnable )
            throw new Exception('Page Not Found', 404);

        //  Call forwarding
        if ( self::$Section->UrlRedirect )
            self::ResponseRedirect(self::$Section->UrlRedirect);

        //  Checking the rights to the current section
        $Action_List = self::$Section->Get_Action_List();
        if ( 1 < self::$Users->Zero_Groups_ID && 'yes' == self::$Section->IsAuthorized && 0 == count($Action_List) )
            throw new Exception('Access Denied', 403);

        //  Execute controller
        if ( !isset($_REQUEST['act']) )
            $_REQUEST['act'] = 'Default';
        if ( $_REQUEST['act'] == 'Logout' )
        {
            $Controller = Zero_Controller::Factory("Zero_Users_Login");
            $Controller->Action_Logout();
        }
        else if ( $_REQUEST['act'] == 'Login' )
        {
            $Controller = Zero_Controller::Factory("Zero_Users_Login");
            $Controller->Action_Login();
            Zero_App::Set_Variable('action_message', $Controller->Get_Message());
        }

        //  Execute controller
        $view = "";
        self::Set_Variable('action_message', []);
        if ( self::$Section->Controller )
        {
            if ( !isset($Action_List[$_REQUEST['act']]) )
                throw new Exception('Access Denied', 403);
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
        $viewLayout = new Zero_View(self::$Section->Layout);
        if ( true == $view instanceof Zero_View )
        {
            /* @var $view Zero_View */
            $view->Assign('Action', $Action_List);
            $viewLayout->Assign('Content', $view->Fetch());
        }
        else
            $viewLayout->Assign('Content', $view);
        $view = $viewLayout->Fetch();
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
    public static function Handler_Error($code, $message, $filename, $line)
    {
        throw new ErrorException($message, $code, 0, $filename, $line);
    }

    /**
     * Obrabotchik iscliuchenii` dlia funktcii set_exception_handler()
     *
     * - '403' standartny`i` otvet na zakry`ty`i` razdel (stranitcu sai`ta)
     * - '404' standartny`i` otvet ne nai`dennogo dokumenta
     * - '500' vse ostal`ny`e kriticheskie oshibki prilozheniia libo servera
     *
     * @param Exception $exception
     */
    public static function Handler_Exception(Exception $exception)
    {
        $code = $exception->getCode();
        if ( $code != 403 && $code != 404 )
        {
            $range_file_error = 10;

            Zero_Logs::Set_Message_Error("#{ERROR_EXCEPTION} " . $exception->getMessage() . ' ' . $exception->getFile() . '(' . $exception->getLine() . ')');
            if ( Zero_App::$Mode == 'web' )
                Zero_Logs::Set_Message_Error(Zero_Logs::Get_SourceCode($exception->getFile(), $exception->getLine(), $range_file_error));

            $traceList = $exception->getTrace();
            array_shift($traceList);
            foreach ($traceList as $id => $trace)
            {
                if ( !isset($trace['args']) )
                    continue;
                $args = [];
                $range_file_error = $range_file_error - 2;
                foreach ($trace['args'] as $arg)
                {
                    if ( is_scalar($arg) )
                        $args[] = "'" . $arg . "'";
                    else if ( is_array($arg) )
                        $args[] = print_r($arg, true);
                    else if ( is_object($arg) )
                        $args[] = get_class($arg) . ' Object...';
                }
                $trace['args'] = join(', ', $args);
                if ( isset($trace['class']) )
                    $callback = $trace['class'] . $trace['type'] . $trace['function'];
                else if ( isset($trace['function']) )
                    $callback = $trace['function'];
                else
                    $callback = '';
                if ( !isset($trace['file']) )
                    $trace['file'] = '';
                if ( !isset($trace['line']) )
                    $trace['line'] = 0;
                $error = "\t#{" . $id . "}" . $trace['file'] . '(' . $trace['line'] . '): ' . $callback . "(" . str_replace("\n", "", $trace['args']) . ");";
                Zero_Logs::Set_Message_Error($error);
                if ( Zero_App::$Mode == 'web' && $trace['file'] && $trace['line'] )
                    Zero_Logs::Set_Message_Error(Zero_Logs::Get_SourceCode($trace['file'], $trace['line'], $range_file_error));
            }
        }

        if ( Zero_App::$Mode == 'api' )
        {
            self::ResponseJson(Zero_Logs::Get_Message(), $code, $exception->getMessage(), $exception->getFile() . '(' . $exception->getLine() . ')');
        }
        else if ( Zero_App::$Mode == 'web' )
        {
            //            Zero_App::$Users->UrlRedirect = $_SERVER['REQUEST_URI'];
            $View = new Zero_View(ucfirst(self::$Config->Site_DomainSub) . '_Error');
            $View->Template_Add('Zero_Error');
            $View->Assign('http_status', $code);
            self::ResponseHtml($View->Fetch(), $code);
        }
        else if ( Zero_App::$Mode == 'console' )
        {
            self::ResponseConsole();
        }
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
            echo Zero_Logs::Output_Display();

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }
}
