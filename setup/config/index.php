<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/zero/class/App.php';

$arr = explode('.', strtolower($_SERVER['HTTP_HOST']));
if( '<DOMAIN_SUB>' == $arr[0] )
    Zero_App::Init('<DOMAIN_SUB>');
else
    Zero_App::Init('www');

Zero_App::Execute();
exit;