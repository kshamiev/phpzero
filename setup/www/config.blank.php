<?php
/**
 * Константы приложения
 */
define("SAMPLE", "SAMPLE");

/**
 * The basic configuration of the all application.
 *
 * @package Config
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
        //  Protocol
        'Protocol' => 'http',
        //  Domain of the site by default
        'Domain' => '<DOMAIN>',
        //  Static Data Domain Site (css, js, img - design)
        'DomainAssets' => '',
        //  Domain binary data (uploaded by users)
        'DomainUpload' => '',
        // Use DB
        'UseDB' => ISUSEDB,
        // Токен сессии пользователя
        'Token' => 'i09u9Maf6l6sr7Um0m8A3u0r9i55m3il',
        // Timezone
        'TimeZone' => 'Europe/Moscow',
        // Maintenance ip access (list ip separator ',')
        'MaintenanceIp' => '',
        // Список разрешенных ip адресов для запросов
        'AccessAllowIp' => [],
    ],
    // Реквизиты доступа к внешним источникам SEE Zero_Request
    'AccessOutside' => [
        'Simple' => [
            'Name' => 'Прямые запросы без конфигурации',
            'AccessMethod' => 'Simple',
            'Url' => '',
            'ApacheLogin' => '',
            'ApachePassword' => '',
            'AuthUserToken' => '',
            'IsDebug' => true,
        ],
        'Sample' => [
            'Name' => 'Пример запроса с реквизитами доступа',
            'AccessMethod' => 'Sample',
            'Url' => 'http://domain.ru',
            'ApacheLogin' => 'login',
            'ApachePassword' => 'xxxxxxx',
            'AuthUserToken' => 'xxxxxxx',
            'IsDebug' => true,
        ],
    ],
    //  Access for DB (Mysql)
    'Db' => [
        'main' => [
            'Host' => "<DB_HOST>", //  Host or Socket
            'Login' => "<DB_LOGIN>", //  User
            'Password' => "<DB_PASSWORD>", //  Password
            'Name' => "<DB_NAME>", //  Name DB
            'Charset' => "utf8", //  Name DB
        ],
    ],
    //  Настройки почты
    'Mail' => [
        //  Host
        'Host' => '',
        //  Port
        'Port' => 25,
        //  Username
        'Username' => '',
        //  Password
        'Password' => '',
        //  Retry count
        'RetryCnt' => 10,
        //  Api прямой отправки письма (если указан то используется он)
        'ApiSend' => 'http://domain.ru/api/v1/mail/send',
        //  Api отправки письма через очередь (если указан то используется он)
        'ApiQueue' => 'http://domain.ru/api/v1/mail/queue',
        // Кодировка
        'CharSet' => 'utf-8',
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
];
