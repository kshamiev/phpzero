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
require __DIR__ . '/zero/class/App.php';

//  Work console task
if ( count($_SERVER['argv']) > 1 )
{
    Zero_App::Init('console_' . $_SERVER['argv'][1]);
    set_time_limit(36000);

    $arr = explode('-', $_SERVER['argv'][1]);
    if ( 2 != count($arr) )
    {
        throw new Exception('консольная задача определена не правильно: ' . $_SERVER['argv'][1], 409);
    }

    $arr[1] = 'Action_' . $arr[1];
    $Controller = Zero_Controller::Make($arr[0]);
    $Controller->$arr[1]();
}
//  Launch Manager console task
else
{
    Zero_App::Init('console');
    $week = date('w');
    $month = date('n');
    $day = date('j');
    $hour = date('G');
    $minute = date('i') * 1;

    // check whether the process is running on a server
    exec("ps ax | grep -v 'grep' | grep -v 'cd ' | grep -v 'sudo ' | grep 'console.php '", $result);
    $result = join("\n", $result);
    $modules = Zero_Lib_FileSystem::Get_Modules();
    foreach ($modules as $module)
    {
        $config = Zero_Lib_FileSystem::Get_Config($module);
        if ( !isset($config['console']) )
            continue;
        // Zero_Logs::Set_Message_Notice($config)
        foreach ($config['console'] as $sys_demon => $sys_cron)
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
            exec(Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_SITE . '/console.php ' . $sys_demon);
        }
    }
}

Zero_App::ResponseConsole();

exit;