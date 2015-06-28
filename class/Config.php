<?php

/**
 * The configuration of systems and applications in general.
 *
 * @package General.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Config
{

    /**
     * The path to the php Interpreter
     *
     * @var string
     */
    public $Site_PathPhp = '';

    /**
     * Site name (of the project)
     *
     * @var string
     */
    public $Site_AccessLogin = '';

    /**
     * Site name (of the project)
     *
     * @var string
     */
    public $Site_AccessPassword = '';

    /**
     * Site name (of the project)
     *
     * @var string
     */
    public $Site_Name = '';

    /**
     * Email the site (by default)
     *
     * @var string
     */
    public $Site_Email = '';

    /**
     * Timeout online users status
     *
     * @var integer
     */
    public $Site_UsersTimeoutOnline = 600;

    /**
     * Using a caching system
     *
     * @var bool
     */
    public $Site_IsCache = false;

    /**
     * Флаг Parsing the presentation templates
     *
     * @var bool
     */
    public $Site_TemplateParsing = true;

    /**
     * Default language
     *
     * @var string
     */
    public $Site_Language = '';

    /**
     * Default language
     *
     * @var string
     */
    public $Site_Domain = '';

    /**
     * Default language
     *
     * @var string
     */
    public $Site_DomainAssets = '';

    /**
     * Default language
     *
     * @var string
     */
    public $Site_DomainUpload = '';

    /**
     * Default language
     *
     * @var string
     */
    public $Site_DomainAlias = '';

    /**
     * Default language
     *
     * @var string
     */
    public $Site_DomainSub = '';

    /**
     * Использование БД
     *
     * @var bool
     */
    public $Site_UseDB = false;

    /**
     * Access for DB (Mysql)
     *
     * @var array
     */
    public $Db = [];

    /**
     * Number of items per page
     *
     * @var string
     */
    public $View_PageItem = '';

    /**
     * The range of visible pages
     *
     * @var string
     */
    public $View_PageStep = '';

    /**
     * Monitoring. Fatal errors
     *
     * @var bool
     */
    public $Log_Profile_Error = true;

    /**
     * Monitoring. Warning
     *
     * @var bool
     */
    public $Log_Profile_Warning = true;

    /**
     * Monitoring. Warning
     *
     * @var bool
     */
    public $Log_Profile_Notice = true;

    /**
     * Monitoring. User action
     *
     * @var bool
     */
    public $Log_Profile_Action = true;

    /**
     * Monitoring. Work the application as a whole
     *
     * @var bool
     */
    public $Log_Profile_Sql = true;

    /**
     * Monitoring. Work the application as a whole
     *
     * @var bool
     */
    public $Log_Profile_Application = true;

    /**
     * Output File
     *
     * @var bool
     */
    public $Log_Output_File = true;

    /**
     * Output Display
     *
     * @var bool
     */
    public $Log_Output_Display = true;

    /**
     * Languages
     *
     * @var array
     */
    public $Language = [];

    /**
     * Servers Memcache
     *
     * @var array
     */
    public $Memcache = [];

    /**
     * IP the source address of the request
     *
     * @var string
     */
    public $Ip = '';

    /**
     * Configuration
     *
     * @param string $file_config суффикс конфигурационного файла
     */
    public function __construct($file_config)
    {
        // Setting php
        set_time_limit(3600);
        date_default_timezone_set('Europe/Moscow');
        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        setlocale(LC_COLLATE, 'ru_RU.UTF-8');
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);

        // Initialization of the profiled application processors
        if ( !is_dir(ZERO_PATH_LOG) )
            if ( !mkdir(ZERO_PATH_LOG, 0777, true) )
                die('logs path: "' . ZERO_PATH_LOG . '" not exists');
        ini_set('log_errors', true);
        ini_set('error_log', ZERO_PATH_LOG . '/fatal.log');
        ini_set('magic_quotes_gpc', 0);
        error_reporting(-1);

        if ( file_exists($path = ZERO_PATH_APPLICATION . '/config_' . $file_config . '.php') )
            $Config = require $path;
        else
            $Config = require ZERO_PATH_APPLICATION . '/config.php';

        // The path to the php Interpreter
        $this->Site_PathPhp = $Config['Site']['PathPhp'];

        // The path to the php Interpreter
        $this->Site_AccessLogin = $Config['Site']['AccessLogin'];
        // File storage sessions
        $this->Site_AccessPassword = $Config['Site']['AccessPassword'];

        // Access for DB (Mysql)
        $this->Db = $Config['Db'];

        // Site name (of the project)
        $this->Site_Name = $Config['Site']['Name'];
        // Email the site (by default)
        $this->Site_Email = $Config['Site']['Email'];
        // Timeout online users status
        $this->Site_UsersTimeoutOnline = $Config['Site']['UsersTimeoutOnline'];
        // Using a caching system
        $this->Site_IsCache = $Config['Site']['IsCache'];
        // Parsing the presentation templates
        $this->Site_TemplateParsing = $Config['Site']['TemplateParsing'];
        //  Default language
        $this->Site_Language = $Config['Site']['Language'];

        $this->Site_Domain = $Config['Site']['Domain'];
        if ( $Config['Site']['DomainAssets'] )
            $this->Site_DomainAssets = $Config['Site']['DomainAssets'];
        else
            $this->Site_DomainAssets = $this->Site_Domain;

        if ( $Config['Site']['DomainUpload'] )
            $this->Site_DomainUpload = $Config['Site']['DomainUpload'];
        else
            $this->Site_DomainUpload = $this->Site_Domain;

        if ( isset($_SERVER["HTTP_HOST"]) )
            $this->Site_DomainAlias = $_SERVER["HTTP_HOST"];
        else
            $this->Site_DomainAlias = $Config['Site']['Domain'];

        //  Absolute system host a website.
        $this->Site_DomainSub = explode('.', $this->Site_Domain)[0];
        if ( isset($_SERVER['HTTP_HOST']) )
        {
            $arr = explode('.', strtolower($_SERVER['HTTP_HOST']));
            if ( 2 < count($arr) )
            {
                $this->Site_DomainSub = $arr[0];
            }
        }

        $this->Site_UseDB= $Config['Site']['UseDB'];

        //        $this->Site_ClassRoute = $Config['Site']['ClassRoute'];
        //        if ( !$this->Site_ClassRoute )
        //            die('class Route undefined');
        //        $this->Site_ClassSection = $Config['Site']['ClassSection'];
        //        if ( !$this->Site_ClassSection )
        //            die('class Route undefined');
        //        $this->Site_ClassUsers = $Config['Site']['ClassUsers'];
        //        if ( !$this->Site_ClassUsers )
        //            die('class Route undefined');
        //  Number of items per page
        $this->View_PageItem = $Config['View']['PageItem'];
        //  The range of visible pages
        $this->View_PageStep = $Config['View']['PageStep'];

        // Monitoring. Fatal errors
        $this->Log_Profile_Error = $Config['Log']['Profile']['Error'];
        // Monitoring. Warning
        $this->Log_Profile_Warning = $Config['Log']['Profile']['Warning'];
        // Monitoring. Warning
        $this->Log_Profile_Notice = $Config['Log']['Profile']['Notice'];
        // Monitoring. User action
        $this->Log_Profile_Action = $Config['Log']['Profile']['Action'];
        // Monitoring. User action
        $this->Log_Profile_Sql = $Config['Log']['Profile']['Sql'];
        // Monitoring. Work the application as a whole
        $this->Log_Profile_Application = $Config['Log']['Profile']['Application'];
        // Output File
        $this->Log_Output_File = $Config['Log']['Output']['File'];
        // Output Display
        $this->Log_Output_Display = $Config['Log']['Output']['Display'];

        // Languages
        $this->Language = $Config['Language'];

        // Servers Memcache
        $this->Memcache = $Config['Memcache'];

        // IP the source address of the request
        if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )
            $this->Ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if ( isset($_SERVER["REMOTE_ADDR"]) )
            $this->Ip = $_SERVER["REMOTE_ADDR"];

        if ( !is_dir(ZERO_PATH_EXCHANGE) )
            if ( !mkdir(ZERO_PATH_EXCHANGE, 0777, true) )
                die('path "exchange": "' . ZERO_PATH_EXCHANGE . '" not exists');

        if ( !is_dir(ZERO_PATH_CACHE) )
            if ( !mkdir(ZERO_PATH_CACHE, 0777, true) )
                die('path "cache": "' . ZERO_PATH_CACHE . '" not exists');

        if ( !is_dir(ZERO_PATH_APPLICATION . '/zero') )
            if ( !symlink(ZERO_PATH_ZERO, ZERO_PATH_APPLICATION . '/zero') )
                die('module "zero" path: "' . ZERO_PATH_APPLICATION . '/zero" not exists');

        //  Storage sessions
        if ( !session_id() )
            if ( 0 < count($Config['Memcache']['Session']) )
            {
                ini_set('session.save_handler', 'memcache');
                ini_set('session.save_path', $Config['Memcache']['Session'][0]);
            }
            else
            {
                ini_set('session.save_handler', 'files');
                $path = ini_get('session.save_path');
                if ( !is_dir($path) )
                    if ( !mkdir($path, 0777, true) )
                        die('session path: "' . $path . '" not exists');
            }
    }

    /**
     * Getting the module configuration
     *
     * @param string $module module
     * @param string $fileConfig config file
     * @return array Массив конфигурации указанного модуля и файла
     */
    public static function Get_Config($module, $fileConfig = '')
    {
        $configuration = [];
        if ( $module = strtolower($module) )
        {
            if ( is_dir($path = ZERO_PATH_APPLICATION . '/' . $module . '/config') )
            {
                if ( $fileConfig == '' )
                {
                    foreach (glob($path . '/*.php') as $fileConfig)
                    {
                        $fileConfig = substr(basename($fileConfig), 0, -4);
                        $configuration[$fileConfig] = require $fileConfig;
                    }
                }
                else if ( file_exists($path . '/' . $fileConfig . '.php') )
                {
                    $configuration = require $path . '/' . $fileConfig . '.php';
                }
            }
        }
        return $configuration;
    }

    /**
     * Получение списка существующий модулей в приложении
     *
     * @return array
     */
    public static function Get_Modules()
    {
        $result = [];
        foreach (glob(ZERO_PATH_APPLICATION . '/*', GLOB_ONLYDIR) as $path)
        {
            $result[] = basename($path);
        }
        return $result;
    }
}
