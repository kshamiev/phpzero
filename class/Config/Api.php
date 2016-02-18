<?php

/**
 * Конфигурация роутинга апи запросов
 *
 * @package Zero.Config
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.02.17
 */
class Zero_Config_Api
{
    /**
     * 'url' => 'ClassName-MethodName'
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
    ];
}