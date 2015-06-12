<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/zero/class/App.php';

Zero_App::Init('application');

// General Authorization Application
if ( Zero_App::$Config->Site_AccessLogin )
{
    if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != Zero_App::$Config->Site_AccessLogin || $_SERVER['PHP_AUTH_PW'] != Zero_App::$Config->Site_AccessPassword )
    {
        header('WWW-Authenticate: Basic realm="Auth"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Auth Failed';
        exit;
    }
}

if ( Zero_App::$Config->Site_UseDB )
    Zero_App::Execute();
else
    Zero_App::ExecuteSimple();

exit;
