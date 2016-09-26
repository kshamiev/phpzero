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
         * Прямая отправка письма
         *
         * @see Helper_Mail_ApiSend
         */
        '/api/v1/mail/send' => ['Controller' => 'Helper_Mail_ApiSend', 'View' => ''],
        /**
         * Отправка письма через очередь
         *
         * @see Helper_Mail_ApiQueue
         */
        '/api/v1/mail/queue' => ['Controller' => 'Helper_Mail_ApiQueue', 'View' => ''],
    ];
}