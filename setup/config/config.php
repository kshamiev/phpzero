<?php
/**
 * The basic configuration of the all application.
 */
return [
    //  system Settings
    'System' => [
        //  The path to the php Interpreter
        'PathPhp' => '/usr/bin/php',
        //  File storage sessions
        'PathSession' => '<PATH_SESSION>',
    ],
    //  site settings
    'Site' => [
        //  General Authorization Application
        'AccessLogin' => '',
        'AccessPassword' => '',
        //  Site name (of the project)
        'Name' => "<SITE_NAME>",
        //  Email the site (by default)
        'Email' => "<SITE_EMAIL>",
        //  Using a caching system
        'IsCache' => false,
        //  Timeout online users status
        'UsersTimeoutOnline' => 600,
        //  Domain of the site by default
        'Domain' => '<DOMAIN>',
        //  Static Data Domain Site (css, js, img - design)
        'DomainAssets' => '',
        //  Domain binary data (uploaded by users)
        'DomainUpload' => '',
    ],
    //  SubDomain the Alias for Host
    'Domain' => [
        //  main
        'www' => "www",
        //  control panel
        '<DOMAIN_SUB>' => "zero",
    ],
    //  Theme
    'Themes' => [
        //  main
        'www' => "default",
        //  control panel
        '<DOMAIN_SUB>' => "default",
    ],
    //  Access for DB (Mysql)
    'Db' => [
        //  Host or Socket
        'Host' => "<DB_HOST>",
        //  User
        'Login' => "<DB_LOGIN>",
        //  Password
        'Password' => "<DB_PASSWORD>",
        //  Name DB
        'Name' => "<DB_NAME>",
    ],
    //  The settings of the presentation of data
    'View' => [
        //  Number of items per page
        'PageItem' => "20",
        //  The range of visible pages
        'PageStep' => "11",
        //  Parsing the presentation templates
        'TemplateParsing' => true,
    ],
    //  Monitoring
    'Log' => [
        //  Profiling
        'Profile' => [
            //  Fatal errors
            'Error' => true,
            //  Warning
            'Warning' => true,
            //  User action
            'Action' => true,
            //  Work the application as a whole
            'Application' => true,
        ],
        //  Output
        'Output' => [
            //  File
            'File' => true,
            //  Display
            'Display' => true,
        ],
    ],
    //  Languages
    'LanguageDefault' => "<SITE_LANGDEFAULT>",
    'Language' => [
        'en-en' => ['ID' => 1, 'Name' => 'English'],
        'ru-ru' => ['ID' => 2, 'Name' => 'Русский'],
    ],
    //  Servers Memcache
    'Memcache' => [
        //  For Cache data
        'Cache' => [
            //  'localhost:11211'
        ],
        //  Session storage
        'Session' => [
            //  'localhost:11211'
        ],
    ],
];
