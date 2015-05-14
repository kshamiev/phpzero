<?php
/**
 * File Configure Console Controllers
 */
return [
    //  'ClassName' => array('Minute' => 'exp.', 'Hour' => 'exp.', 'Day' => 'exp.', 'Month' => 'exp.', 'Week' => 'exp.', 'IsActive' => 'exp.',
    //  expression "*", "20", "*/10", "3-8", "6/2", "5,6,7"
    /**
     * Формирование документации
     *
     * @see Zero_Console_ApiGen::Action_Default()
     */
    'Zero_Console_ApiGen' => [
        'Minute' => '*/10',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => false
    ],
    /**
     * Удаление старых бинарных данных загруженных через веб (ajax)
     *
     * @see Zero_Console_RemTmpFileUpload::Action_Default()
     */
    'Zero_Console_RemTmpFileUpload' => [
        'Description' => 'Remove TempFileUpload',
        'Minute' => '0',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => false
    ],
    /**
     * Формирование документации
     *
     * @see Zero_Section_Console_SiteMap::Action_Default()
     */
    'Zero_Section_Console_SiteMap' => [
        'Description' => 'Create SiteMap',
        'Minute' => '0',
        'Hour' => '0',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => false
    ],
    /**
     * Оперделение не  активных пользователей.
     *
     * @see Zero_Users_Console_Offline::Action_Default()
     *
     */
    'Zero_Users_Console_Offline' => [
        'Minute' => '*/10',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => false
    ],
    /**
     * Инженеринг моделей и контроллеров CRUD по БД (первой по умолчанию)
     *
     * @see Zero_System_Console_Engine::Action_Default()
     *
     */
    'Zero_System_Console_Engine' => [
        'Minute' => '*/10',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => false
    ],
];
