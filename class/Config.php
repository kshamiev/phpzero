<?php

/**
 * The configuration of systems and applications in general.
 *
 * @package Zero.Component
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
     * Protocol
     *
     * @var string
     */
    public $Site_Protocol = 'http';

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
    public $Mail_Host = '';

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_Port = 25;

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_Username = '';

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_Password = '';

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_RetryCnt = 10;

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_ApiSend = '';

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_ApiQueue = '';

    /**
     * Number of items per page
     *
     * @var string
     */
    public $Mail_CharSet = '';

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
     * роутинг Web запросов
     *
     * @var array
     */
    public $Web = [];

    /**
     * Ротуинг Api запросов
     *
     * @var array
     */
    public $Api = [];

    /**
     * Консольные задачи
     *
     * @var array
     */
    public $Console = [];

    /**
     * IP the source address of the request
     *
     * @var string
     */
    public $Ip = '';

    /**
     * Configuration
     */
    public function __construct()
    {
        // Setting php
        set_time_limit(3600);
        date_default_timezone_set('Europe/Moscow');
        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        setlocale(LC_COLLATE, 'ru_RU.UTF-8');
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);

        // Initialization of the profiled application processors
        ini_set('log_errors', true);
        ini_set('error_log', ZERO_PATH_LOG . "/fatal.log");
        ini_set('magic_quotes_gpc', 0);
        error_reporting(-1);

        $Config = require ZERO_PATH_APPLICATION . '/config.php';

        // IP the source address of the request
        if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )
            $this->Ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if ( isset($_SERVER["REMOTE_ADDR"]) )
            $this->Ip = $_SERVER["REMOTE_ADDR"];

        if ( !is_dir(ZERO_PATH_EXCHANGE) )
            if ( !mkdir(ZERO_PATH_EXCHANGE, 0777, true) )
                die('path "exchange": "' . ZERO_PATH_EXCHANGE . '" not create');

        if ( !is_dir(ZERO_PATH_CACHE) )
            if ( !mkdir(ZERO_PATH_CACHE, 0777, true) )
                die('path "cache": "' . ZERO_PATH_CACHE . '" not create');

        if ( !is_dir(ZERO_PATH_LOG) )
            if ( !mkdir(ZERO_PATH_LOG, 0777, true) )
                die('logs path: "' . ZERO_PATH_LOG . '" not create');

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
                if ( $path && !is_dir($path) )
                    if ( !mkdir($path, 0777, true) )
                        die('session path: "' . $path . '" not exists');
            }

        // The path to the php Interpreter
        $this->Site_PathPhp = $Config['Site']['PathPhp'];

        // The path to the php Interpreter
        $this->Site_AccessLogin = $Config['Site']['AccessLogin'];
        // File storage sessions
        $this->Site_AccessPassword = $Config['Site']['AccessPassword'];
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
        //  Protocol
        if ( isset($Config['Site']['Protocol']) )
            $this->Site_Protocol = $Config['Site']['Protocol'];
        //
        $this->Site_Domain = $Config['Site']['Domain'];
        if ( $Config['Site']['DomainAssets'] )
            $this->Site_DomainAssets = $Config['Site']['DomainAssets'];
        else
            $this->Site_DomainAssets = $this->Site_Domain;
        //
        if ( $Config['Site']['DomainUpload'] )
            $this->Site_DomainUpload = $Config['Site']['DomainUpload'];
        else
            $this->Site_DomainUpload = $this->Site_Domain;
        //
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
        //
        $this->Site_UseDB = $Config['Site']['UseDB'];

        // Access for DB (Mysql)
        $this->Db = $Config['Db'];

        // Настройки почты
        $this->Mail_Host = $Config['Mail']['Host'];
        $this->Mail_Port = $Config['Mail']['Port'];
        $this->Mail_Username = $Config['Mail']['Username'];
        $this->Mail_Password = $Config['Mail']['Password'];
        $this->Mail_RetryCnt = $Config['Mail']['RetryCnt'];
        $this->Mail_ApiSend = $Config['Mail']['ApiSend'];
        $this->Mail_ApiQueue = $Config['Mail']['ApiQueue'];
        $this->Mail_CharSet = $Config['Mail']['CharSet'];

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

        // роутинг Web запросов
        if ( isset($Config['Web']) )
            $this->Web = $Config['Web'];

        // Ротуинг Api запросов
        if ( isset($Config['Api']) )
            $this->Api = $Config['Api'];

        // Консольные задачи
        if ( isset($Config['Console']) )
            $this->Console = $Config['Console'];
    }

    /**
     * Getting the module configuration
     *
     * @param string $module module
     * @param string $fileConfig config file
     * @return array Массив конфигурации указанного модуля и файла
     */
    public static function Get_Config($module)
    {
        $configuration = [];
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/config.php';
        if ( file_exists($path) )
        {
            $configuration = require $path;
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
