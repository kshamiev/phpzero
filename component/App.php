<?php
/**
 * The version of PhpZero
 */
define('VERSION_PHPZERO', '2.0.0');
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
     * User
     *
     * @var Zero_Users
     */
    public static $Users;

    /**
     * Routing (по URL)
     *
     * @var Zero_Route
     */
    public static $Route;

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
        echo $path. '<br>';
        return false;
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

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        //  Configuration (Zero_Config)
        self::$Config = new Zero_Config();

        //  Processing incoming request (Zero_Route)
        self::$Route = new Zero_Route();

        //  Session Initialization (Zero_Session)
        session_name(md5(self::$Config->Db['Name']));
        session_start();
        $Session = & $_SESSION['Session'];
        if ( !$Session instanceof Zero_Session )
            $Session = Zero_Session::Get_Instance();
        else
            Zero_Session::Set_Instance($Session);

        //  Initialize cache subsystem (Zero_Cache)
        Zero_Cache::Init();

        //  Include Components
        require_once ZERO_PATH_ZERO . '/component/View.php';
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

        //  Initialize the controller action
        //        $_REQUEST['act'] = (isset($_REQUEST['act'])) ? $_REQUEST['act'] : '';

        //  Initialize the type response

        //  Инициализация запрошенного раздела (Zero_Section)
        //        self::$Section = Zero_Model::Instance(ucfirst(self::$Config->Host) . '_Section');
        self::$Section = Zero_Model::Instance('Www_Section');

        //  User Initialization (Zero_Users)
        //        self::$Users = Zero_Model::Factory(ucfirst(self::$Config->Host) . '_Users');
        self::$Users = Zero_Model::Factory('Www_Users');

        //  Checking for non-existent section
        if ( 0 == self::$Section->ID || 'no' == self::$Section->IsEnable )
            throw new Exception('Page Not Found', 404);
        //  Call forwarding
        if ( self::$Section->UrlRedirect )
            self::ResponseRedirect(self::$Section->UrlRedirect);
        //  Checking the rights to the current section
        $Action_List = Zero_App::$Section->Get_Action_List();
        if ( 1 < self::$Users->Zero_Groups_ID )
        {
            if ( 'yes' == self::$Section->IsAuthorized && 0 == count($Action_List) )
                throw new Exception('Access Denied', 403);
        }

        //  Execute controller
        $output = '';
        self::Set_Variable('action_message', []);
        if ( self::$Section->Controller )
        {
            if ( !isset($_REQUEST['act']) )
                $_REQUEST['act'] = 'Default';
            if ( !isset($Action_List[$_REQUEST['act']]) )
                throw new Exception('Access Denied', 403);
            $_REQUEST['act'] = 'Action_' . $_REQUEST['act'];

            $Controller = Zero_Controller::Factory(self::$Section->Controller);
            Zero_Logs::Start('#{CONTROLLER.Action} ' . $_REQUEST['act']);
            $output = $Controller->$_REQUEST['act']();
            Zero_Logs::Stop('#{CONTROLLER.Action} ' . $_REQUEST['act']);
            Zero_App::Set_Variable('action_message', $Controller->Get_Message());
        }

        Zero_Logs::Stop('#{APP.Main}');

        // Generate and output the result
        if ( 'html' == self::$Section->ContentType )
        {
            self::ResponseHtml();
            Zero_Logs::Start('#{LAYOUT.View}');
            $View = new Zero_View(self::$Section->Layout);
            if ( $output instanceof Zero_View )
            {
                Zero_Logs::Start('#{CONTROLLER.View}');
                $output->Assign('Action', $Action_List);
                $output = $output->Fetch();
                Zero_Logs::Stop('#{CONTROLLER.View}');
            }
            $View->Assign('Content', $output);
            $View->Assign('Users', self::$Users);
            $View->Assign('Section', self::$Section);
            echo $View->Fetch(true);
            Zero_Logs::Stop('#{LAYOUT.View}');
        }
        else if ( 'xml' == self::$Section->ContentType )
        {
            self::ResponseXml();
            echo $output->Fetch();
        }
        else if ( 'json' == self::$Section->ContentType )
        {
            self::ResponseJson();
            echo json_encode($output->Receive());
        }
        else if ( 'img' == self::$Section->ContentType )
        {
            self::ResponseImg($output);
            if ( file_exists($output) )
                echo file_get_contents($output);
        }
        else if ( 'file' == self::$Section->ContentType )
        {
            self::ResponseFile($output);
            if ( file_exists($output) )
                echo file_get_contents($output);
        }

        Zero_Logs::Stop('#{APP.Full}');
        return true;
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
    private static function _HeaderNoCache()
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
    }

    public static function ResponseHtml($flagCache = false)
    {
        if ( true == $flagCache )
            self::_HeaderNoCache();
        header("Content-Type: text/html; charset=utf-8");
    }

    public static function ResponseXml($flagCache = false)
    {
        if ( true == $flagCache )
            self::_HeaderNoCache();
        header("Content-Type: text/xml; charset=utf-8");
    }

    public static function ResponseJson($flagCache = false)
    {
        if ( true == $flagCache )
            self::_HeaderNoCache();
        header("Content-Type: text/javascript; charset=utf-8");
    }

    public static function ResponseImg($path, $flagCache = false)
    {
        if ( true == $flagCache )
            self::_HeaderNoCache();
        header("Content-Type: " . Zero_Lib_FileSystem::File_Type($path));
        header("Content-Length: " . filesize($path));
    }

    public static function ResponseFile($path, $flagCache = false)
    {
        if ( true == $flagCache )
            self::_HeaderNoCache();
        header("Content-Type: " . Zero_Lib_FileSystem::File_Type($path));
        header("Content-Length: " . filesize($path));
        header('Content-Disposition: attachment; filename = "' . basename($path) . '"');
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
}
