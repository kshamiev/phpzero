<?php
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
define('ZERO_PATH_THEMES', ZERO_PATH_SITE . '/themes');
/**
 * Location System
 */
define('ZERO_PATH_PHPZERO', ZERO_PATH_SITE . '/zero');
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
     * Type given out of the client
     *
     * @var string
     */
    public static $Response = 'html';

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
            require true;
        $arr = explode('_', $class_name);
        $module = strtolower(array_shift($arr));
        $class = implode('/', $arr);
        if ( file_exists($path = ZERO_PATH_APPLICATION . '/' . $module . '/class/' . $class . '.php') )
            return require_once $path;
        if ( file_exists($path = ZERO_PATH_PHPZERO . '/class/' . $class . '.php') )
            return require_once $path;
        return false;
    }

    /**
     * Application initialization
     *
     * - Monitoring of the work application. Component Zero_Logs
     * - The configuration and initialization of the application. Component Zero_Config
     * - Initialize cache subsystem. Component Zero_Cache
     * - Processing incoming request (GET). Component Zero_Route
     * - Session Initialization. Component Zero_Session
     *
     * @param string $config prefix configuration
     * @param string $file_log the base name of the log file
     */
    public static function Init($config, $file_log = 'application')
    {
        //  Include Components
        require_once ZERO_PATH_PHPZERO . '/class/Logs.php';
        require_once ZERO_PATH_PHPZERO . '/class/Cache.php';
        require_once ZERO_PATH_PHPZERO . '/class/Session.php';
        require_once ZERO_PATH_PHPZERO . '/class/Config.php';
        require_once ZERO_PATH_SITE . '/config/' . $config . '.php';

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init($file_log);

        //  Configuration (Zero_Config)
        $class_config = 'Config_' . $config;
        self::$Config = new $class_config($config);

        //  Initialize cache subsystem (Zero_Cache)
        Zero_Cache::Init();

        //  Processing incoming request (Zero_Route)
        if ( isset($_SERVER['REQUEST_URI']) )
        {
            $request_uri = rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/');
            self::$Route = new self::$Config->FactoryComponents['Zero_Route']($request_uri);
        }
        else
            self::$Route = new self::$Config->FactoryComponents['Zero_Route'];

        //  Session Initialization (Zero_Session)
        session_name(md5(self::$Config->Db['Name']));
        session_start();
        $Session = & $_SESSION['Session'];
        if ( !$Session instanceof Zero_Session )
            $Session = Zero_Session::Get_Instance();
        else
            Zero_Session::Set_Instance($Session);
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
        //  Absolute system host is not defined (domain is not defined)
        if ( !self::$Config->Host )
            throw new Exception('Not Found', 404);

        //  Инициализация запрошенного раздела (Zero_Section)
        self::$Section = Zero_Model::Instance('Zero_Section');

        //  User Initialization (Zero_Users)
        self::$Users = Zero_Model::Factory('Zero_Users');

        //  Checking for non-existent section
        if ( 0 == self::$Section->ID )
            throw new Exception('Not Found', 404);
        //  Call forwarding
        else if ( self::$Section->UrlRedirect )
            self::Redirect(self::$Section->UrlRedirect);
        //  Checking the rights to the current section
        else if ( 'yes' == self::$Section->IsAuthorized && 1 < self::$Users->Zero_Groups_ID && 0 == count(self::$Section->Get_Action_List()) )
            throw new Exception('Access Denied', 403);

        //  Initialize the controller action
        $_REQUEST['act'] = (isset($_REQUEST['act'])) ? $_REQUEST['act'] : '';
        self::$Response = (isset($_REQUEST['ajax'])) ? $_REQUEST['ajax'] : 'html';

        // Controller initialization messages
        Zero_App::Set_Variable('action_message', []);

        //  Execute controller or plugin
        $output = '';
        if ( self::$Section->Controller )
        {
            if ( method_exists(self::$Section->Controller, 'Factory') )
                $Controller = Zero_Controller::Factory(self::$Section->Controller);
            else
                $Controller = Zero_Plugin::Make(self::$Section->Controller);
            $output = $Controller->Execute($_REQUEST['act']);
        }

        Zero_Logs::Stop('#{APP.Main}');

        // Generate and output the result
        self::Header(self::$Response, $output);
        if ( 'html' == self::$Response )
        {
            Zero_Logs::Start('#{LAYOUT.View}');
            $Layout = Zero_Model::Make('Zero_Layout', self::$Section->Zero_Layout_ID);
            $Layout->DB->Load_Cache('Layout');
            $View = new Zero_View($Layout->Layout);
            if ( $output instanceof Zero_View )
            {
                Zero_Logs::Start('#{CONTENT.View}');
                $output = $output->Fetch();
                Zero_Logs::Stop('#{CONTENT.View}');
            }
            $View->Assign('Content', $output);
            echo $View->Fetch();
            Zero_Logs::Stop('#{LAYOUT.View}');
        }
        else if ( 'xml' == self::$Response )
            echo $output->Fetch();
        else if ( 'json' == self::$Response )
            echo json_encode($output->Receive());
        else if ( 'img' == self::$Response || 'file' == self::$Response )
            if ( file_exists($output) )
                echo file_get_contents($output);

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
     * @param string $type type of document given out (default html)
     * @param string $path the absolute path to the file given out or url link
     */
    public static function Header($type = 'html', $path = '')
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        if ( 'html' == $type )
            header("Content-Type: text/html; charset=utf-8");
        else if ( 'json' == $type )
            header("Content-Type: text/javascript; charset=utf-8");
        else if ( 'xml' == $type )
            header("Content-Type: text/xml; charset=utf-8");
        else if ( 'img' == $type )
        {
            header("Content-Type: " . Zero_Helper_FileSystem::File_Type($path));
            header("Content-Length: " . filesize($path));
        }
        else if ( 'file' == $type )
        {
            header("Content-Type: " . Zero_Helper_FileSystem::File_Type($path));
            header("Content-Length: " . filesize($path));
            header('Content-Disposition: attachment; filename = "' . basename($path) . '"');
        }
    }

    /**
     * Redirect to the specified page
     *
     * @param string $url link to which page to produce redirect
     */
    public static function Redirect($url)
    {
        self::$Config->Log_Output_Display = false;
        self::$Config->Log_Output_File = false;
        header('Location: ' . $url);
        exit;
    }
}
