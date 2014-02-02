<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

/**
 * The absolute path to the project (site)
 */
define('ZERO_PATH_SITE', __DIR__);

// Including the class Zero_App
require ZERO_PATH_SITE . '/zero/component/App.php';

Zero_App::Init();

Zero_App::Execute();

exit;