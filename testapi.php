<?php
/**
 * Manager daemons on schedule.
 *
 * @package Console
 */

//  Connecting application
require_once __DIR__ . '/class/Zero/App.php';

//  Work console task
if ( count($_SERVER['argv']) > 1 )
{
    $arr = explode('-', $_SERVER['argv'][1]);
    if ( 1 < count($arr) )
        $_REQUEST['act'] = 'Action_' . $arr[1];
    else
        $_REQUEST['act'] = 'Action_Default';

    Zero_App::Init($arr[0]);

    $Controller = Zero_Controller::Makes($arr[0]);
    $flag = $Controller->$_REQUEST['act']();

    if ( Zero_App::$Config->Site_UseDB && true === $flag )
    {
        $sql = "UPDATE Controllers SET DateExecute = NOW() WHERE Controller = '{$arr[0]}'";
        Zero_DB::Update($sql);
    }
    echo $flag;
}
else //  Launch Manager console task
{
    Zero_App::Init();

    $flag = 0;
    $week = date('w');
    $month = date('n');
    $day = date('j');
    $hour = date('G');
    $minute = date('i') * 1;

    // check whether the process is running on a server
    exec("ps ax | grep -v 'grep' | grep -v 'cd ' | grep -v 'sudo ' | grep 'console.php '", $result);
    $result = join("\n", $result);

    if ( Zero_App::$Config->Site_UseDB )
    {
        $sql = "SELECT * FROM Controllers WHERE Typ = 'Console' AND IsActive = 1";
        foreach (Zero_DB::Select_Array($sql) as $sys_cron)
        {
            $sys_demon = $sys_cron['Controller'];
            if ( !$sys_cron['IsActive'] || false !== strpos($result, Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_SITE . '/console.php ' . $sys_demon) )
                continue;
            //  check date and time to run
            if ( false == zero_crontab_check_datetime($week, $sys_cron['Week']) )
                continue;
            if ( false == zero_crontab_check_datetime($month, $sys_cron['Month']) )
                continue;
            if ( false == zero_crontab_check_datetime($day, $sys_cron['Day']) )
                continue;
            if ( false == zero_crontab_check_datetime($hour, $sys_cron['Hour']) )
                continue;
            if ( false == zero_crontab_check_datetime($minute, $sys_cron['Minute']) )
                continue;
            //  run
            // exec(Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_SITE . '/console.php ' . $sys_demon . ' > /dev/null 2>&1 &');
            exec(Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_ZERO . '/console.php ' . $sys_demon);
        }
    }
    else
    {
        if ( file_exists($path = ZERO_PATH_APPLICATION . '/routeConsole.php') )
            foreach (require $path as $sys_demon => $sys_cron)
            {
                if ( !$sys_cron['IsActive'] || false !== strpos($result, Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_SITE . '/console.php ' . $sys_demon) )
                    continue;
                //  check date and time to run
                if ( false == zero_crontab_check_datetime($week, $sys_cron['Week']) )
                    continue;
                if ( false == zero_crontab_check_datetime($month, $sys_cron['Month']) )
                    continue;
                if ( false == zero_crontab_check_datetime($day, $sys_cron['Day']) )
                    continue;
                if ( false == zero_crontab_check_datetime($hour, $sys_cron['Hour']) )
                    continue;
                if ( false == zero_crontab_check_datetime($minute, $sys_cron['Minute']) )
                    continue;
                //  run
                // exec(Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_SITE . '/console.php ' . $sys_demon . ' > /dev/null 2>&1 &');
                exec(Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_ZERO . '/console.php ' . $sys_demon);
            }
        else
        {
            Zero_Logs::Set_Message_Error('NOT FOUND ROUTE CONSOLE: ' . $path);
            $flag = 1;
        }
    }
    echo $flag;
}

Zero_Response::Console();
