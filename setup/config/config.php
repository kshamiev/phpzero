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
    //  site settings
    'Site' => [
        //  General Authorization Application
        'AccessLogin' => '',
        'AccessPassword' => '',
        //  Site name (of the project)
        'Name' => "<SITE_NAME>",
        //  Email the site (by default)
        'Email' => "<SITE_EMAIL>",
        //  Timeout online users status
        'UsersTimeoutOnline' => 600,
        //  Using a caching system
        'IsCache' => false,
        //  Parsing the templates view
        'TemplateParsing' => true,
        //  Language of the site by default
        'Language' => "<SITE_LANGDEFAULT>",
        //  Domain of the site by default
        'Domain' => '<DOMAIN>',
        //  Static Data Domain Site (css, js, img - design)
        'DomainAssets' => '',
        //  Domain binary data (uploaded by users)
        'DomainUpload' => '',
    ],
    //  Theme
    'Themes' => [
        //  main
        'www' => "phpzero",
        //  control panel
        '<DOMAIN_SUB>' => "phpzero",
    ],
    //  The settings of the presentation of data
    'View' => [
        //  Number of items per page
        'PageItem' => "20",
        //  The range of visible pages
        'PageStep' => "11",
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
