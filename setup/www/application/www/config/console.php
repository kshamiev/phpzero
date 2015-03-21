<?php
/**
 * File Configure Console Controllers
 */
return [
    //  'ClassName-Method' => array('Description' => 'Description', 'Minute' => 'exp.', 'Hour' => 'exp.', 'Day' => 'exp.', 'Month' => 'exp.', 'Week' => 'exp.', 'IsActive' => 'exp.',
    //  expression "*", "20", "*/10", "3-8", "6/2", "5,6,7"
    'Zero_Users_Console-Offline' => [
        'Description' => 'Users Offline',
        'Minute' => '*/10',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => true
    ],
    'Zero_System_Console-RemoveTempFileUpload' => [
        'Description' => 'Remove TempFileUpload',
        'Minute' => '0',
        'Hour' => '*',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => true
    ],
    'Zero_Section_Console-SiteMap' => [
        'Description' => 'Create SiteMap',
        'Minute' => '0',
        'Hour' => '0',
        'Day' => '*',
        'Month' => '*',
        'Week' => '*',
        'IsActive' => true
    ],
];
