<?php
/**
 * The installer module
 */

//  Security (from a direct call)
if ( !class_exists('Zero_App') )
    return 'Access Denied';

if ( file_exists(ZERO_PATH_APPLICATION . '/' . $module . '/setup/INSTALL') )
    return '';

$config = Zero_Helper_Modules::Get_Config_All($module, 'main');
$this_module = $module;

//  Install of dependent modules
foreach ($config['Modules'] as $module)
{
    $subj = '';
    if ( file_exists($path = ZERO_PATH_APPLICATION . '/' . $module . '/setup/install.php') )
        $subj = include $path;
    if ( $subj )
        return $subj;
}

//  Install module from DB
if ( file_exists($path = ZERO_PATH_APPLICATION . '/' . $module . '/setup/schema/mysql.sql') )
{
    $host = Zero_App::$Config->Db['Host'];
    $user = Zero_App::$Config->Db['Login'];
    $pass = Zero_App::$Config->Db['Password'];
    $name = Zero_App::$Config->Db['Name'];
    exec("mysql -h {$host} -u {$user} -p{$pass} {$name} < {$path}", $arr1, $arr2);
    if ( 0 < $arr2 )
        return 'Error installing the module in the database';
}

//  Status module installer
file_put_contents(ZERO_PATH_APPLICATION . '/' . $module . '/setup/INSTALL', $config['Version']);
return '';