<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/zero/class/App.php';

Zero_App::Init('web', 'application');

Zero_App::Execute();

exit;