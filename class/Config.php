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
class Zero_Config
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
     */
    public function __construct()
    {
        $Config = require ZERO_PATH_SITE . '/config.php';

        // The path to the php Interpreter
        $this->System_PathPhp = $Config['System']['PathPhp'];
        // File storage sessions
        $this->System_PathSession = $Config['System']['PathSession'];

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
        $this->Site_DomainSub = 'www';
        if ( isset($_SERVER['HTTP_HOST']) )
        {
            $arr = explode('.', strtolower($_SERVER['HTTP_HOST']));
            if ( 2 < count($arr) )
            {
                $this->Site_DomainSub = $arr[0];
            }
        }

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

        // Setting php
        error_reporting(-1);
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
        //        ini_set('log_errors', 1);
        //        ini_set('error_log', ZERO_PATH_SITE . '/log/error_php.log');
        ini_set('magic_quotes_gpc', 0);

        //  Storage sessions
        if ( 0 < count($Config['Memcache']['Session']) )
        {
            ini_set('session.save_handler', 'memcache');
            ini_set('session.save_path', $Config['Memcache']['Session'][0]);
        }
        else
        {
            ini_set('session.save_handler', 'files');
            ini_set('session.save_path', $Config['System']['PathSession']);
        }
    }
}

/**
 * Debug output to the browser
 *
 * @param mixed $var variable
 */
function pre($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
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
