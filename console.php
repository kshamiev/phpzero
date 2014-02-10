<?php
/**
 * Manager daemons on schedule.
 */

/**
 * Check function on the the date and time run in the format of crontab
 *
 * @param string $date_this
 * @param string $date_cron
 * @return boolean
 */
function zero_crontab_check_datetime($date_this, $date_cron)
{
    //  any valid value or exact match
    if ( '*' == $date_cron || $date_this == $date_cron )
    {
        return true;
    }
    //  range
    if ( false !== strpos($date_cron, '-') )
    {
        $arr = explode('-', $date_cron);
        if ( $arr[0] <= $date_this && $date_this <= $arr[1] )
        {
            return true;
        }
        return false;
    }
    //  fold
    else if ( false !== strpos($date_cron, '/') )
    {
        $arr = explode('/', $date_cron);
        if ( $date_this % $arr[1] )
        {
            return false;
        }
        return true;
    }
    //  list
    else if ( false !== strpos($date_cron, ',') )
    {
        $arr = explode(',', $date_cron);
        if ( in_array($date_this, $arr) )
        {
            return true;
        }
        return false;
    }
    else
    {
        return false;
    }
}

//  Connecting application
require __DIR__ . '/component/App.php';

//  Work console task
if ( count($_SERVER['argv']) > 1 )
{
    Zero_App::Init('console_' . $_SERVER['argv'][1]);
    Zero_App::$Config->Log_Output_Display = false;
    set_time_limit(36000);

    $module = explode('_', $_SERVER['argv'][1])[0];

    $arr = Zero_Lib_FileSystem::Get_Config($module, 'console');
    if ( !isset($arr[$_SERVER['argv'][1]]) )
    {
        Zero_Logs::Set_Message('undefined console script: ' . $_SERVER['argv'][1]);
        exit;
    }
    $arr = explode('-', $_SERVER['argv'][1]);
    $Object = Zero_Controller::Make($arr[0]);
    $Object->$arr[1]();
}
//  Launch Manager console task
else
{
    Zero_App::Init('console');
    Zero_App::$Config->Log_Output_Display = false;
    $week = date('w');
    $month = date('n');
    $day = date('j');
    $hour = date('G');
    $minute = date('i') * 1;

    // check whether the process is running on a server
    exec("ps ax | grep -v 'grep' | grep -v 'cd ' | grep -v 'sudo ' | grep 'console.php '", $result);
    $result = join("\n", $result);
    foreach (Zero_Lib_FileSystem::Get_Config('', 'console') as $module)
    {
        foreach ($module as $sys_demon => $sys_cron)
        {
            if ( !$sys_cron['IsActive'] || false !== strpos($result, Zero_App::$Config->System_PathPhp . ' ' . ZERO_PATH_ZERO . '/console.php ' . $sys_demon) )
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
            exec(Zero_App::$Config->System_PathPhp . ' ' . ZERO_PATH_ZERO . '/console.php ' . $sys_demon . ' > /dev/null 2>&1 &');
        }
    }
}
exit;
