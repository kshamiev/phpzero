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
function pre($str)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

error_reporting(E_ALL | E_NOTICE | E_STRICT);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_CTYPE, 'ru_RU.UTF-8');
setlocale(LC_COLLATE, 'ru_RU.UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('magic_quotes_gpc', 0);

require dirname(__DIR__) . '/class/App.php';
require dirname(__DIR__) . '/class/Helper/FileSystem.php';
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
    if ( !isset($_REQUEST['site_name']) || !$_REQUEST['site_name'] || !isset($_REQUEST['site_email']) || !$_REQUEST['site_email'] || !isset($_REQUEST['domain_www']) || !$_REQUEST['domain_www'] || !isset($_REQUEST['domain_sub']) || !$_REQUEST['domain_sub'] || !isset($_REQUEST['db_host']) || !$_REQUEST['db_host'] || !isset($_REQUEST['db_login']) || !$_REQUEST['db_login'] || !isset($_REQUEST['db_password']) || !$_REQUEST['db_password'] || !isset($_REQUEST['db_name']) || !$_REQUEST['db_name'] || !isset($_REQUEST['lang']) || !$_REQUEST['lang'] )
    {
        $message_install_list[] = "Request empty";
        break;
    }

    $_REQUEST['domain_www'] = strtolower($_REQUEST['domain_www']);
    $_REQUEST['domain_sub'] = strtolower($_REQUEST['domain_sub']);

    //  Domain Checker
    $file = ZERO_PATH_SITE . '/' . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . '.txt';
    file_put_contents($file, 'check');
    $file_check = 'http://' . $_REQUEST['domain_www'] . '/' . basename($file);
    if ( 'check' != @file_get_contents($file_check) )
    {
        unlink($file);
        $message_install_list[] = "Domain Not Work";
        break;
    }
    $file_check = 'http://' . $_REQUEST['domain_sub'] . '.' . $_REQUEST['domain_www'] . '/' . basename($file_check);
    if ( 'check' != @file_get_contents($file_check) )
    {
        unlink($file);
        $message_install_list[] = "SubDomain Not Work";
        break;
    }
    unlink($file);

    //  installation database
    file_put_contents('db_create.sql', "CREATE DATABASE IF NOT EXISTS `{$_REQUEST['db_name']}`;");
    exec("mysql -h {$_REQUEST['db_host']} -u {$_REQUEST['db_login']} -p{$_REQUEST['db_password']} < db_create.sql", $arr1, $arr2);
    if ( 0 < $arr2 )
    {
        $message_install_list[] = "Error create DB (Access Denied)";
        unlink('db_create.sql');
        break;
    }
    unlink('db_create.sql');
    exec("mysql -h {$_REQUEST['db_host']} -u {$_REQUEST['db_login']} -p{$_REQUEST['db_password']} {$_REQUEST['db_name']} < schema/mysql_{$_REQUEST['lang']}.sql", $arr1, $arr2);
    if ( 0 < $arr2 )
    {
        $message_install_list[] = "Error import DB (Access Denied)";
        break;
    }

    $arr = ini_get_all();

    //  Creating a filesystem structure. Copy the system and base  module
    if ( !is_dir(ZERO_PATH_SITE . '/application') )
        Zero_Helper_FileSystem::Folder_Copy(ZERO_PATH_PHPZERO . '/setup/application', ZERO_PATH_APPLICATION);
    if ( !is_dir(ZERO_PATH_SITE . '/assets') )
        mkdir(ZERO_PATH_SITE . '/assets', 0777, true);
    if ( !is_dir(ZERO_PATH_SITE . '/cache') )
        mkdir(ZERO_PATH_SITE . '/cache', 0777, true);
    if ( !is_dir(ZERO_PATH_SITE . '/exchange') )
        mkdir(ZERO_PATH_SITE . '/exchange', 0777, true);
    if ( !is_dir(ZERO_PATH_SITE . '/log') )
        mkdir(ZERO_PATH_SITE . '/log', 0777, true);
    if ( !is_dir(ZERO_PATH_SITE . '/themes') )
        mkdir(ZERO_PATH_SITE . '/themes', 0777, true);
    if ( !is_dir(ZERO_PATH_SITE . '/upload/data') )
        mkdir(ZERO_PATH_SITE . '/upload/data', 0777, true);

    //  .htaccess for apache
    copy('.htaccess', ZERO_PATH_SITE . '/.htaccess');

    //  robots.txt for index
    copy('robots.txt', ZERO_PATH_SITE . '/robots.txt');

    //  Enter point
    copy('config/index.php', ZERO_PATH_SITE . '/index.php');

    //  Baseline configuration
    $config = file_get_contents('config/config.php');
    $config = str_replace('<PATH_SESSION>', $arr['session.save_path']['local_value'], $config);
    $config = str_replace('<SITE_NAME>', $_REQUEST['site_name'], $config);
    $config = str_replace('<SITE_EMAIL>', $_REQUEST['site_email'], $config);
    $config = str_replace('<DOMAIN>', $_REQUEST['domain_www'], $config);
    $config = str_replace('<DOMAIN_SUB>', $_REQUEST['domain_sub'], $config);
    $config = str_replace('<DB_HOST>', $_REQUEST['db_host'], $config);
    $config = str_replace('<DB_LOGIN>', $_REQUEST['db_login'], $config);
    $config = str_replace('<DB_PASSWORD>', $_REQUEST['db_password'], $config);
    $config = str_replace('<DB_NAME>', $_REQUEST['db_name'], $config);
    $config = str_replace('<SITE_LANGDEFAULT>', $_REQUEST['lang'], $config);
    file_put_contents(ZERO_PATH_SITE . '/config.php', $config);

    $message_install_list[110] = "System install success full";
    $error_init_list[120] = 'system is already installed (remove /config.php)';

    $_REQUEST = [];
    break;
}

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
if ( !isset($_REQUEST['domain_sub']) )
    $_REQUEST['domain_sub'] = 'zero';
if ( !isset($_REQUEST['db_host']) )
    $_REQUEST['db_host'] = 'localhost';
?>
<table width="600px" cellspacing="1" cellpadding="4" border="1" align="center">
    <form action="index.php" method="post">
        <tr>
            <td colspan="2" height="50px">
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
            <td width="300px">Domain site (*)<br>(www.<strong><font color="blue">site-name.com</font></strong>)</td>
            <td>
                <input type="text" name="domain_www" value="<?= $_REQUEST['domain_www'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Subdomain management system (*)<br>(<strong><font color="blue">zero</font></strong>.site-name.com)</td>
            <td>
                <input type="text" name="domain_sub" value="<?= $_REQUEST['domain_sub'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - The host and port or socket (*)</td>
            <td>
                <input type="text" name="db_host" value="<?= $_REQUEST['db_host'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - User (*)</td>
            <td>
                <input type="text" name="db_login" value="<?= $_REQUEST['db_login'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - Password (*)</td>
            <td>
                <input type="text" name="db_password" value="<?= $_REQUEST['db_password'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Mysql - DB name (*)</td>
            <td>
                <input type="text" name="db_name" value="<?= $_REQUEST['db_name'] ?>" style="width: 99%;">
            </td>
        </tr>
        <tr>
            <td width="300px">Default language (*)</td>
            <td>
                <select name="lang" style="width: 99%;">
                    <option value="en-en">english</option>
                    <option value="ru-ru">русский</option>
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