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
         * Формирование документации
         *
         * @see Zero_Console_Base_ApiGen::Action_Default()
         */
        'Zero_Console_Base_ApiGen' => ['Minute' => '*/10', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
        /**
         * Удаление старых бинарных данных загруженных через веб (ajax)
         *
         * @see Zero_Console_Base_RemTmpFileUpload::Action_Default()
         */
        'Zero_Console_Base_RemTmpFileUpload' => ['Minute' => '0', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
        /**
         * Формирование документации
         *
         * @see Zero_Console_Section_SiteMap::Action_Default()
         */
        'Zero_Console_Section_SiteMap' => ['Minute' => '0', 'Hour' => '0', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
        /**
         * Оперделение не  активных пользователей.
         *
         * @see Zero_Console_Users_Offline::Action_Default()
         *
         */
        'Zero_Console_Users_Offline' => ['Minute' => '*/10', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
        /**
         * Инженеринг моделей и контроллеров CRUD по БД (первой по умолчанию)
         *
         * @see Zero_Console_Base_Engine::Action_Default()
         *
         */
        'Zero_Console_Base_Engine' => ['Minute' => '*/10', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
        /**
         * Отправка очереди почтовых сообщений
         *
         * @see Zero_Console_Mail_Send
         */
        'Zero_Console_Mail_Send' => ['Minute' => '*/30', 'Hour' => '*', 'Day' => '*', 'Month' => '*', 'Week' => '*', 'IsActive' => false],
    ];
}