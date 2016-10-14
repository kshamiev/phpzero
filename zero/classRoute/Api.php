<?php

/**
 * Конфигурация роутинга апи запросов
 *
 * @package Zero.Route
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.02.17
 */
class Zero_Route_Api
{
    /**
     * 'url' => ['Controller' => 'ClassName', 'View' => ''],
     *
     * @var array
     */
    public $Route = [
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
}