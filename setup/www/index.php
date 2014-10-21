<?php
/**
 * The entry point to the application.
 * Initialize and run.
 */

// Including the class Zero_App
require __DIR__ . '/zero/class/App.php';

Zero_App::Init('application');

//`Full (use Mysql)
//--USE--//Zero_App::Execute();

// Native (no sql mode)
//--NOT--//Zero_App::ExecuteSimple();

exit;