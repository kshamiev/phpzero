<?php
/**
 * File Configure Api Controllers
 */
return [
    // Синтаксис: 'Uri' => array('Controller' => 'ClassName-Method', 'View' => 'layoutName',
    // Пример
    /**
     * Какой-то там контроллер
     *
     * @see Zero_Api_Base_Upload
     */
    '/api/v1.0/zero/system/upload' => [
        'Controller' => 'Zero_Api_Base_Upload',
        'View' => '',
    ],
];
