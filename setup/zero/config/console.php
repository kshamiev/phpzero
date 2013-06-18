<?php
/**
 * File Configure Console Plugin
 */
return [
    //  'CrontabName' => array('Description' => 'Description', 'Minute' => 'exp.', 'Hour' => 'exp.', 'Day' => 'exp.', 'Month' => 'exp.', 'Week' => 'exp.', 'IsActive' => 'exp.',
    //  expression "*", "20", "*/10", "3-8", "6/2", "5,6,7"
    'Zero_Users_Offline' => [
        'Description' => 'Users Offline',
        'Minute' => '*/10',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => '1'
    ],
];
