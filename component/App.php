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
     */
    public static function Init($file_log = 'application')
    {
        //  Include Components
        require_once ZERO_PATH_ZERO . '/component/Config.php';
        require_once ZERO_PATH_ZERO . '/component/Session.php';
        require_once ZERO_PATH_ZERO . '/component/Cache.php';
        require_once ZERO_PATH_ZERO . '/component/Logs.php';
        require_once ZERO_PATH_ZERO . '/component/Route.php';

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        //  Configuration (Zero_Config)
        self::$Config = new Zero_Config();

        //  Initialize cache subsystem (Zero_Cache)
        Zero_Cache::Init(Zero_App::$Config->Memcache['Cache']);

        //  Processing incoming request (Zero_Route)
        if ( true == file_exists($path = ZERO_PATH_APPLICATION . '/' . self::$Config->Host . '/component/Route.php') )
        {
            require_once $path;
            $class = ucfirst(self::$Config->Host) . '_Route';
            self::$Route = new $class();
        }
        else
            self::$Route = new Zero_Route();

        require_once ZERO_PATH_ZERO . '/component/View.php';

        spl_autoload_register(['Zero_App', 'Autoload']);

        //  Session Initialization (Zero_Session)
        Zero_Session::Init(self::$Config->Db['Name']);

        self::Set_Variable("responseCode", 200);

        // Initialization of the profiled application processors
        set_error_handler(['Zero_App', 'Error_Handler']);
        set_exception_handler(['Zero_App', 'Exception_Handler']);
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
     * @return mixed The result work of the application
     * @throws Exception
     */
    public static function Execute()
    {
        Zero_Logs::Start('#{APP.Full}');
        Zero_Logs::Start('#{APP.Main}');

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
        if ( 1 < self::$Users->Zero_Groups_ID )
        {
            if ( 'yes' == self::$Section->IsAuthorized && 0 == count($Action_List) )
                throw new Exception('Access Denied', 403);
        }
        //  Execute controller
        $output = "";
        self::Set_Variable('action_message', []);
        if ( self::$Section->Controller )
        {
            if ( !isset($_REQUEST['act']) )
                $_REQUEST['act'] = 'Default';
            if ( !isset($Action_List[$_REQUEST['act']]) )
                throw new Exception('Access Denied', 403);
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];

            $Controller = Zero_Controller::Factory(self::$Section->Controller);
            Zero_Logs::Start('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            $output = $Controller->__call($_REQUEST['act'], []);
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . self::$Section->Controller . ' -> ' . $_REQUEST['act']);
            Zero_App::Set_Variable('action_message', $Controller->Get_Message());
        }

        Zero_Logs::Stop('#{APP.Main}');

        self::Exit_Application($output);

        Zero_Logs::Stop('#{APP.Full}');
        return true;
    }

    /**
     * Redirect to the specified page
     *
     * @param string $url link to which page to produce redirect
     */
    public static function ResponseRedirect($url)
    {
        self::$Config->Log_Output_Display = false;
        self::$Config->Log_Output_File = false;
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
    public static function Error_Handler($code, $message, $filename, $line)
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
    public static function Exception_Handler(Exception $exception)
    {
        if ( 403 == $exception->getCode() )
        {
            self::Set_Variable("responseCode", 403);
            Zero_Logs::Set_Message('Section Url: ' . Zero_App::$Config->Host . Zero_App::$Route->Url);
        }
        else if ( 404 == $exception->getCode() )
        {
            self::Set_Variable("responseCode", 404);
            Zero_Logs::Set_Message('Section Url: ' . Zero_App::$Config->Host . Zero_App::$Route->Url);
        }
        else
        {
            self::Set_Variable("responseCode", 500);
            //            header('HTTP/1.1 500 Server Error');
            $range_file_error = 10;
            Zero_Logs::Set_Message("#{ERROR_EXCEPTION} " . $exception->getMessage() . ' ' . $exception->getFile() . '(' . $exception->getLine() . ')');
            Zero_Logs::Set_Message(Zero_Logs::Get_SourceCode($exception->getFile(), $exception->getLine(), $range_file_error), '');
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
                $error = "   #{" . $id . "}" . $trace['file'] . '(' . $trace['line'] . '): ' . $callback . "(" . str_replace("\n", "", $trace['args']) . ");";
                Zero_Logs::Set_Message($error);
                if ( $trace['file'] && $trace['line'] )
                    Zero_Logs::Set_Message(Zero_Logs::Get_SourceCode($trace['file'], $trace['line'], $range_file_error), 'code');
            }
        }

        //                ob_end_clean();
        $View = new Zero_View(ucfirst(Zero_App::$Config->Host) . '_Error');
        $View->Template_Add('Zero_Error');
        $View->Assign('http_status', self::Get_Variable("responseCode"));
        self::Exit_Application($View);
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    public static function Exit_Application($view)
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        switch ( self::Get_Variable("responseCode") )
        {
            case 200:
                header('HTTP/1.1 200 Ok');
                break;
            case 403:
                header('HTTP/1.1 403 Access Denied');
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                break;
            case 409:
                header('HTTP/1.1 409 Conflict Application');
                break;
            case 500:
                header('HTTP/1.1 500 Server Error');
                break;
        }

        // Generate and output the result
        switch ( self::$Section->ContentType )
        {
            case 'html':
                header("Content-Type: text/html; charset=utf-8");
                //
                Zero_Logs::Start('#{LAYOUT.View}');
                // Основные данные
                if ( 200 == self::Get_Variable("responseCode") )
                {
                    $viewLayout = new Zero_View(self::$Section->Layout);
                    if ( true == $view instanceof Zero_View )
                    {
                        $view->Assign('Action', self::$Section->Get_Action_List());
                        $viewLayout->Assign('Content', $view->Fetch());
                    }
                    else
                        $viewLayout->Assign('Content', $view);
                    echo $viewLayout->Fetch();
                }
                else
                    echo $view->Fetch(true);
                // Логирование (в браузер)
                if ( self::$Config->Log_Output_Display )
                    echo Zero_Logs::Output_Display();
                Zero_Logs::Stop('#{LAYOUT.View}');
                break;
            case 'json':
                header("Content-Type: application/json; charset=utf-8");
                //
                if ( 500 == self::Get_Variable("responseCode") )
                {
                    $view = new Zero_View;
                    $view->AssignApi(Zero_Logs::Get_Message(), self::Get_Variable("responseCode"), "Ошибка работы приложения");
                }
                echo json_encode($view->Receive(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                break;
            case 'xml':
                header("Content-Type: text/xml; charset=utf-8");
                echo $view->Fetch();
                break;
            //            case 'file':
            //                header("Content-Type: " . Zero_Lib_FileSystem::File_Type($view));
            //                header("Content-Length: " . filesize($view));
            //                header('Content-Disposition: attachment; filename = "' . basename($view) . '"');
            //                if ( file_exists($view) )
            //                    echo file_get_contents($view);
            //                break;
            //            case 'img':
            //                header("Content-Type: " . Zero_Lib_FileSystem::File_Type($view));
            //                header("Content-Length: " . filesize($view));
            //                if ( file_exists($view) )
            //                    echo file_get_contents($view);
            //                break;
        }

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
    }
}
