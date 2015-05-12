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
        'IsActive' => true
    ],
    /**
     * Удаление устаревших загруженных бинарных файлов
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
        'IsActive' => true
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
        'IsActive' => true
    ],
    /**
     * Контроль активности пользователя.
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
        'IsActive' => true
    ],
];
