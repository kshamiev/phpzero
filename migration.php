<?php
/**
 * Manager daemons on schedule.
 *
 * @package Console
 */

// sample run http://site-f.hostke.ru/zero/migration.php

//  Connecting application
require __DIR__ . '/class/App.php';
Zero_App::Init();

$sql = "UPDATE Section SET Controllers_ID = NULL";
Zero_DB::Update($sql);
$sql = "DELETE FROM Controllers";
Zero_DB::Update($sql);

// Web
$sql = "SELECT ID, `Controller` FROM `Section`";
$sectionList = Zero_DB::Select_Array($sql);
foreach ($sectionList as $row)
{
    if ( !$row['Controller'] )
        continue;

    $sql = "
    INSERT INTO `Controllers` SET
      `Name` = '{$row['Controller']}',
      `Controller` = '{$row['Controller']}',
      `Typ` = 'Web',
      `IsActive` = 1
    ";
    $controller_ID = Zero_DB::Insert($sql);

    $sql = "
        UPDATE Section SET
          Controllers_ID = {$controller_ID}
        WHERE
          ID = {$row['ID']}
        ";
    Zero_DB::Update($sql);
}

// Api
foreach (Zero_App::$Config->Modules as $console)
{
    if ( isset($console['routeApi']) && is_object($console['routeApi']) )
        foreach ($console['routeApi']->Route as $url => $sys_cron)
        {
            $sql = "
            INSERT INTO `Controllers` SET
              `Name` = '{$sys_cron['Controller']}',
              `Controller` = '{$sys_cron['Controller']}',
              `Typ` = 'Api',
              `Url` = '{$url}',
              `IsActive` = 1
            ";
            Zero_DB::Insert($sql);
        }
}

// Console
foreach (Zero_App::$Config->Modules as $console)
{
    if ( isset($console['routeConsole']) && is_object($console['routeConsole']) )
        foreach ($console['routeConsole']->Task as $sys_demon => $sys_cron)
        {
            if ( $sys_cron['IsActive'] )
                $sys_cron['IsActive'] = 1;
            else
                $sys_cron['IsActive'] = 0;

            $sql = "
            INSERT INTO `Controllers` SET
              `Name` = '{$sys_demon}',
              `Controller` = '{$sys_demon}',
              `Typ` = 'Console',
              `Minute` = '{$sys_cron['Minute']}',
              `Hour` = '{$sys_cron['Hour']}',
              `Day` = '{$sys_cron['Day']}',
              `Month` = '{$sys_cron['Month']}',
              `Week` = '{$sys_cron['Week']}',
              `IsActive` = {$sys_cron['IsActive']}
            ";
            Zero_DB::Insert($sql);
        }
}

// End
Zero_App::ResponseHtml("OK", 200);
exit;
