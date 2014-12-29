<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/zero/class/App.php';

if ( preg_match("~^/api~si", $_SERVER['REQUEST_URI']) )
{
    // Инициализация входных параметров и данных в случае api
    if ( $_SERVER['REQUEST_METHOD'] === "PUT" )
    {
        $data = file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']);
        $_POST = json_decode($data, true);
    }
    else if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($GLOBALS["HTTP_RAW_POST_DATA"]) )
    {
        $_POST = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
    }
    Zero_App::Init('application', 'api');
}
else
{
    Zero_App::Init('application', 'web');
}

//`Full (use Mysql)
//--USE--//Zero_App::Execute();

// Native (no sql mode)
//--NOT--//Zero_App::ExecuteSimple();

exit;
