<?php
/**
 * The installer of the system.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */

error_reporting(-1);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_CTYPE, 'ru_RU.UTF-8');
setlocale(LC_COLLATE, 'ru_RU.UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('magic_quotes_gpc', 0);

require dirname(__DIR__) . '/class/Config.php';
require dirname(__DIR__) . '/class/App.php';
require dirname(__DIR__) . '/class/Lib/FileSystem.php';

if ( !isset($_REQUEST['site_name']) )
    $_REQUEST['site_name'] = '';
if ( !isset($_REQUEST['site_email']) )
    $_REQUEST['site_email'] = '';
if ( !isset($_REQUEST['db_login']) )
    $_REQUEST['db_login'] = '';
if ( !isset($_REQUEST['db_password']) )
    $_REQUEST['db_password'] = '';
if ( !isset($_REQUEST['db_name']) )
    $_REQUEST['db_name'] = '';
if ( !isset($_REQUEST['domain_www']) )
    $_REQUEST['domain_www'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);
if ( !isset($_REQUEST['db_host']) )
    $_REQUEST['db_host'] = 'localhost';
if ( !isset($_REQUEST['db_use']) )
    $_REQUEST['db_use'] = 0;
if ( !isset($_REQUEST['lang']) )
    $_REQUEST['lang'] = '';
$error_init_list = [];
$message_install_list = [];
/**
 * Checking the software environment
 */

//  Check the version of php
$arr = explode('.', phpversion());
if ( $arr[0] < 5 || $arr[1] < 4 || $arr[2] < 2 )
    $error_init_list[] = 'Installed version of php (' . phpversion() . ') below the permissible (5.4.2)';

//  Checking the necessary system functions
if ( !function_exists('spl_autoload_register') )
    $error_init_list[] = 'function spl_autoload_register not exists';
if ( !function_exists('set_error_handler') )
    $error_init_list[] = 'function set_error_handler not exists';
if ( !function_exists('set_exception_handler') )
    $error_init_list[] = 'function set_exception_handler not exists';
if ( !function_exists('register_shutdown_function') )
    $error_init_list[] = 'function register_shutdown_function not exists';

//  Verify access rights to create and delete folders and files
if ( !mkdir(ZERO_PATH_SITE . '/test') )
    $error_init_list[] = 'Error access creating folder';
else
    rmdir(ZERO_PATH_SITE . '/test');
if ( !file_put_contents(ZERO_PATH_SITE . '/test.php', '!') )
    $error_init_list[] = 'Error access creating file';
else
    unlink(ZERO_PATH_SITE . '/test.php');

//  Checking  already installed system
if ( file_exists(ZERO_PATH_SITE . '/config.php') )
    $error_init_list[120] = 'system is already installed (remove /config.php)';

/**
 * Installation System
 */
while ( isset($_REQUEST['act']) && 'Install_System' == $_REQUEST['act'] && 0 == count($error_init_list) )
{
    //  Checking the input parameters
    if ( !$_REQUEST['site_name'] || !$_REQUEST['site_email'] || !$_REQUEST['domain_www'] || !$_REQUEST['lang'] )
    {
        $message_install_list[] = "Request empty";
        break;
    }
    $_REQUEST['domain_www'] = strtolower($_REQUEST['domain_www']);

    // installation database
    // file_put_contents('db_create.sql', "CREATE DATABASE IF NOT EXISTS `{$_REQUEST['db_name']}`;");
    // exec("mysql -h {$_REQUEST['db_host']} -u {$_REQUEST['db_login']} -p{$_REQUEST['db_password']} < db_create.sql", $arr1, $arr2);
    // exec("mysql -h {$_REQUEST['db_host']} -u {$_REQUEST['db_login']} -p{$_REQUEST['db_password']} {$_REQUEST['db_name']} < schema/mysql_{$_REQUEST['lang']}.sql", $arr1, $arr2);
    if ( $_REQUEST['db_use'] )
    {
        $db = mysqli_connect($_REQUEST['db_host'], $_REQUEST['db_login'], $_REQUEST['db_password'], $_REQUEST['db_name']);
        if ( !$db )
        {
            $message_install_list[] = "Error create DB (Access Denied)";
            break;
        }
    }
    $arr = ini_get_all();

    //  Creating a filesystem structure. Copy the system and base  module
    Zero_Lib_FileSystem::Folder_Copy(__DIR__ . "/www", ZERO_PATH_SITE);
    $index = file_get_contents(ZERO_PATH_SITE . '/index.php');
    if ( $_REQUEST['db_use'] )
    {
        $index = str_replace('//--USE--//', '', $index);
        $index = str_replace('//--NOT--//', '// ', $index);
    }
    else
    {
        $index = str_replace('//--USE--//', '// ', $index);
        $index = str_replace('//--NOT--//', '', $index);
    }
    file_put_contents(ZERO_PATH_SITE . '/index.php', $index);

    //  Baseline configuration
    $config = file_get_contents(ZERO_PATH_SITE . '/config.php');
    $config = str_replace('<PATH_SESSION>', $arr['session.save_path']['local_value'], $config);
    $config = str_replace('<SITE_NAME>', $_REQUEST['site_name'], $config);
    $config = str_replace('<SITE_EMAIL>', $_REQUEST['site_email'], $config);
    $config = str_replace('<DOMAIN>', $_REQUEST['domain_www'], $config);
    $config = str_replace('<DB_HOST>', $_REQUEST['db_host'], $config);
    $config = str_replace('<DB_LOGIN>', $_REQUEST['db_login'], $config);
    $config = str_replace('<DB_PASSWORD>', $_REQUEST['db_password'], $config);
    $config = str_replace('<DB_NAME>', $_REQUEST['db_name'], $config);
    $config = str_replace('<SITE_LANGDEFAULT>', $_REQUEST['lang'], $config);
    file_put_contents(ZERO_PATH_SITE . '/config.php', $config);

    if ( !symlink(ZERO_PATH_ZERO, ZERO_PATH_APPLICATION . '/zero') )
    {
        $message_install_list[] = "Error create symlink from module zero";
        break;
    }

    $message_install_list[110] = "System install success full";
    $error_init_list[120] = 'system is already installed (remove /config.php)';
    break;
}
?>
<table width="600px" cellspacing="1" cellpadding="4" border="1" align="center">
    <form action="index.php" method="post">
        <tr>
            <td colspan="2" height="50px">
                Перед инсталяцией нужно установить БД и получить доступ к оной.<br>
                После инсталяции <a href="/" target="_blank">сюда</a> логин и пароль: "dev" "dev"<br>
                <?php foreach ($error_init_list as $kod => $error)
                {
                    if ( 100 < $kod )
                        echo '<font color="gren">' . $error . "</font><br>\n";
                    else
                        echo '<font color="red">' . $error . "</font><br>\n";
                } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="50px">
                <?php foreach ($message_install_list as $kod => $message)
                {
                    if ( 100 < $kod )
                        echo '<font color="gren">' . $message . "</font><br>\n";
                    else
                        echo '<font color="red">' . $message . "</font><br>\n";
                } ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">Installation</th>
        </tr>
        <tr>
            <td width="300px">Site name (project name) (*)</td>
            <td>
                <input type="text" name="site_name" value="<?= $_REQUEST['site_name'] ?>" style="width: 99%;">
            </td>
        </tr>

        <tr>
            <td width="300px">Email the site (by default) (*)<br>(name@domain.com)</td>
            <td>
                <input type="text" name="site_email" value="<?= $_REQUEST['site_email'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Domain site (*)<br>(<strong><font color="blue">site-name.com</font></strong>)</td>
            <td>
                <input type="text" name="domain_www" value="<?= $_REQUEST['domain_www'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px"><b>Use Mysql</b></td>
            <td>
                <input type="checkbox" name="db_use" value="1" <?= $_REQUEST['db_use'] ? ' checked' : '' ?> style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - The host and port or socket (*)</td>
            <td>
                <input type="text" name="db_host" value="<?= $_REQUEST['db_host'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - User (* if use mysql)</td>
            <td>
                <input type="text" name="db_login" value="<?= $_REQUEST['db_login'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - Password (* if use mysql)</td>
            <td>
                <input type="text" name="db_password" value="<?= $_REQUEST['db_password'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - DB name (* if use mysql)</td>
            <td>
                <input type="text" name="db_name" value="<?= $_REQUEST['db_name'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Default language (*)</td>
            <td>
                <select name="lang" style="width: 99%;">
                    <option value="ru-ru">русский</option>
                    <option value="en-en">english</option>
                </select>
            </td>
        </tr>
        <?php if ( 0 == count($error_init_list) )
        {
            ?>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="act" value="Install_System">
                </td>
            </tr>
        <?php
        }
        else
        {
            ?>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="act" value="Refresh">
                </td>
            </tr>
        <?php } ?>
    </form>
</table>