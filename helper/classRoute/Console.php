<?php

/**
 * Конфигурация запуска консольных задач
 *
 * @package Zero.Route
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.02.17
 */
class Zero_Route_Console
{
    /**
     * 'ClassName-MethodName' => ['Minute' => 'exp.', 'Hour' => 'exp.', 'Day' => 'exp.', 'Month' => 'exp.', 'Week' => 'exp.', 'IsActive' => 'bool'],
     * exp.: "*", "20", "* /10", "3-8", "6/2", "5,6,7"
     *
     * @var array
     */
    public $Task = [
        /**
         * Отправка очереди почтовых сообщений
         *
         * @see Helper_Mail_SolSend
         */
        'Helper_Mail_SolSend' => ['Minute' => '*/30', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
    ];
}