<?php
/**
 * Роутинг запросов Api
 * 
 * 'url' => ['Controller' => 'ClassName', 'View' => ''],
 *
 * @package Config
 * @var array
 */
return [
    /**
     * Загрузка бинарных данных через веб форму (ajax)
     *
     * @see Zero_System_Api_Upload
     */
    '/api/v1/zero/base/upload' => ['Controller' => 'Zero_System_Api_Upload', 'View' => ''],
    /**
     * Загрузка бинарных данных через веб форму (ajax)
     *
     * @see Zero_Users_Api_Login
     */
    '/api/v1/zero/user/login' => ['Controller' => 'Zero_Users_Api_Login', 'View' => ''],
    /**
     * Загрузка бинарных данных через веб форму (ajax)
     *
     * @see Zero_Users_Api_Logout
     */
    '/api/v1/zero/user/logout' => ['Controller' => 'Zero_Users_Api_Logout', 'View' => ''],
];
