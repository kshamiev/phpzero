<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/phpzero/class/Zero/App.php';
Zero_App::Init();
Zero_App::Execute();
exit;
