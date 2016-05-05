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
         * @see Zero_Api_Base_Upload
         */
        '/api/v1.0/zero/base/upload' => ['Controller' => 'Zero_Api_Base_Upload', 'View' => ''],
        /**
         * Загрузка бинарных данных через веб форму (ajax)
         *
         * @see Zero_Users_Api_Login
         */
        '/api/v1.0/zero/user/login' => ['Controller' => 'Zero_Users_Api_Login', 'View' => ''],
        /**
         * Загрузка бинарных данных через веб форму (ajax)
         *
         * @see Zero_Users_Api_Logout
         */
        '/api/v1.0/zero/user/logout' => ['Controller' => 'Zero_Users_Api_Logout', 'View' => ''],
        /**
         * Прямая отправка письма
         *
         * @see Zero_Api_Mail_Send
         */
        '/api/v1/mail/send' => ['Controller' => 'Zero_Api_Mail_Send', 'View' => ''],
        /**
         * Прямая отправка письма
         *
         * @see Zero_Api_Mail_Send
         */
        '/api/v1.0/mail/send' => ['Controller' => 'Zero_Api_Mail_Send', 'View' => ''],
        /**
         * Отправка письма через очередь
         *
         * @see Zero_Api_Mail_Queue
         */
        '/api/v1/mail/queue' => ['Controller' => 'Zero_Api_Mail_Queue', 'View' => ''],
        /**
         * Отправка письма через очередь
         *
         * @deprecated
         * @see Zero_Api_Mail_Queue
         */
        '/api/v1.0/mail/queue' => ['Controller' => 'Zero_Api_Mail_Queue', 'View' => ''],
    ];
}