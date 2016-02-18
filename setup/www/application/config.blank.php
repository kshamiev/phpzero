<?php
/**
 * The basic configuration of the all application.
 */
return [
    //  site settings
    'Site' => [
        //  The path to the php Interpreter (see command: whereis php)
        'PathPhp' => '/usr/bin/php',
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
        // Use DB
        'UseDB' => ISUSEDB,
    ],
    //  Access for DB (Mysql)
    'Db' => [
        //  Profiling
        'main' => [
            'Host' => "<DB_HOST>", //  Host or Socket
            'Login' => "<DB_LOGIN>", //  User
            'Password' => "<DB_PASSWORD>", //  Password
            'Name' => "<DB_NAME>", //  Name DB
        ],
    ],
    //  The settings of the presentation of data
    'View' => [
        //  Number of items per page
        'PageItem' => "20",
        //  The range of visible pages
        'PageStep' => "11",
        // Сборка шаблона с данными в одну строку
        'TplOutString' => false,
    ],
    //  Monitoring
    'Log' => [
        //  Profiling
        'Profile' => [
            //  Fatal errors
            'Error' => true,
            //  Warning
            'Warning' => true,
            //  Notice
            'Notice' => true,
            //  User action
            'Action' => true,
            //  Work the application as a whole
            'Sql' => true,
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
        'en-en' => 'English',
        'ru-ru' => 'Русский',
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
    //  роутинг Web запросов
    'Web' => [
        new Zero_Config_Web(),
    ],
    //  Ротуинг Api запросов
    'Api' => [
        new Zero_Config_Api(),
    ],
    //  Консольные задачи
    'Console' => [
        new Zero_Config_Console(),
    ],
];
