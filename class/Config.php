<?php

/**
 * Component. The configuration of systems and applications in general.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
abstract class Zero_Config
{
    /**
     * The path to the php Interpreter
     *
     * @var string
     */
    public $System_PathPhp = '';

    /**
     * File storage sessions
     *
     * @var string
     */
    public $System_PathSession = '';

    /**
     * Access for DB (Mysql)
     *
     * @var array
     */
    public $Db = [];

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
     * Absolute system host a website.
     *
     * @var string
     */
    public $Host = '';

    /**
     * Current Theme
     *
     * @var string
     */
    public $Themes = '';

    /**
     * Root site (http://www.domain.com)
     *
     * @var string
     */
    public $Http = '';

    /**
     * http static data (images, css, js) (http://www.domain.com/assets/themename)
     *
     * @var string
     */
    public $Http_Assets = '';

    /**
     * http binary data (http://www.domain.com/upload/data)
     *
     * @var string
     */
    public $Http_Upload = '';

    /**
     * http referer
     *
     * @var string
     */
    public $Http_Ref = '';

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
     * Redefinition components
     *
     * @var array
     */
    public $FactoryComponents = [];

    /**
     * Redefinition models
     *
     * @var array
     */
    public $FactoryModel = [];

    /**
     * IP the source address of the request
     *
     * @var string
     */
    public $Ip = '';

    /**
     * Configuration
     *
     * @param string $config prefix configuration
     */
    public function __construct($config)
    {
        $Config = static::Get_Config();

        // General Authorization Application
        if ( $Config['Site']['AccessPassword'] && isset($_SERVER['HTTP_HOST']) )
            if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $Config['Site']['AccessLogin'] || $_SERVER['PHP_AUTH_PW'] != $Config['Site']['AccessPassword'] )
            {
                header('WWW-Authenticate: Basic realm="Auth"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Auth Failed';
                exit;
            }

        // The path to the php Interpreter
        $this->System_PathPhp = $Config['System']['PathPhp'];
        // File storage sessions
        $this->System_PathSession = $Config['System']['PathSession'];

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

        //  Absolute system host a website.
        $this->Host = $config;

        //  Current Theme.
        $this->Themes = isset($Config['Themes'][$config]) ? $Config['Themes'][$config] : 'default';

        // Root site (http://www.domain.com)
        if ( isset($_SERVER["HTTP_HOST"]) )
            $this->Http = 'http://' . $_SERVER["HTTP_HOST"];
        else
            $this->Http = 'http://' . $Config['Site']['Domain'];

        // http static data (images, css, js) (http://www.domain.com/assets)
        if ( $Config['Site']['DomainAssets'] )
            $this->Http_Assets = 'http://' . $Config['Site']['DomainAssets'] . '/assets/' . $this->Themes;
        else if ( isset($_SERVER["SERVER_NAME"]) )
            $this->Http_Assets = 'http://' . $_SERVER["SERVER_NAME"] . '/assets/' . $this->Themes;
        else
            $this->Http_Assets = 'http://' . $Config['Site']['Domain'] . '/assets/' . $this->Themes;

        // http binary data (http://www.domain.com/upload)
        if ( $Config['Site']['DomainUpload'] )
            $this->Http_Upload = 'http://' . $Config['Site']['DomainUpload'] . '/upload/data';
        else if ( isset($_SERVER["SERVER_NAME"]) )
            $this->Http_Upload = 'http://' . $_SERVER["SERVER_NAME"] . '/upload/data';
        else
            $this->Http_Upload = 'http://' . $Config['Site']['Domain'] . '/upload/data';

        // http referer
        $this->Http_Ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->Http;

        //  Number of items per page
        $this->View_PageItem = $Config['View']['PageItem'];
        //  The range of visible pages
        $this->View_PageStep = $Config['View']['PageStep'];

        // Monitoring. Fatal errors
        $this->Log_Profile_Error = $Config['Log']['Profile']['Error'];
        // Monitoring. Warning
        $this->Log_Profile_Warning = $Config['Log']['Profile']['Warning'];
        // Monitoring. User action
        $this->Log_Profile_Action = $Config['Log']['Profile']['Action'];
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

        //  Redefinition components
        $this->FactoryComponents = $Config['FactoryComponents'];
        //  Redefinition models
        $this->FactoryModel = $Config['FactoryModel'];

        // IP the source address of the request
        if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )
            $this->Ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if ( isset($_SERVER["REMOTE_ADDR"]) )
            $this->Ip = $_SERVER["REMOTE_ADDR"];

        // Setting php
        error_reporting(E_ALL | E_NOTICE | E_STRICT);
        date_default_timezone_set('Europe/Moscow');
        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        setlocale(LC_COLLATE, 'ru_RU.UTF-8');
        if ( $this->Log_Output_Display )
        {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
        else
        {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }
        ini_set('magic_quotes_gpc', 0);

        //  Storage sessions
        if ( 0 < count($Config['Memcache']['Session']) )
        {
            ini_set('session.save_handler', 'memcached');
            ini_set('session.save_path', $Config['Memcache']['Session'][0]);
        }
        else
        {
            ini_set('session.save_handler', 'files');
            ini_set('session.save_path', $Config['System']['PathSession']);
        }

        // Initialization of the profiled application processors
        spl_autoload_register(['Zero_App', 'Autoload']);
        set_error_handler(['Zero_Logs', 'Error_Handler']);
        set_exception_handler(['Zero_Logs', 'Exception_Handler']);
        register_shutdown_function(['Zero_Logs', 'Exit_Application']);
    }
}

/**
 * Debug output to the browser
 *
 * @param mixed $var variable
 */
function zero_pre($var)
{
    Zero_Logs::Set_Message('<pre>' . print_r($var, true) . '</pre>', 'code');
}

/**
 * Getting source on the property due
 *
 * @param string $prop свойство связи
 * @return string source of related objects
 */
function zero_relation($prop)
{
    return preg_replace('~(_[A-Z]{1}|_[0-9]{1,3})?_ID$~', '', $prop);
}
