<?php
/**
 * Системные константы общего назначения
 *
 * @package General.Constant
 */

/**
 * The absolute http path to the project (site)
 */
define('ZERO_HTTP', 'http://' . Zero_App::$Config->Site_DomainAlias);
define('HTTP', ZERO_HTTP);
/**
 * http location of static data
 */
define('ZERO_HTTPA', 'http://' . Zero_App::$Config->Site_DomainAssets . '/assets');
define('HTTPA', ZERO_HTTPA);
/**
 * http location of binary data
 */
define('ZERO_HTTPD', 'http://' . Zero_App::$Config->Site_DomainUpload . '/upload/data');
define('HTTPD', ZERO_HTTPD);
/**
 * http location of history
 */
define('ZERO_HTTPH', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ZERO_HTTP);
define('HTTPH', ZERO_HTTPH);

$arr = app_route();
/**
 * The relative url path to the project (site)
 */
define('ZERO_URL', $arr[1]);
define('URL', $arr[1]);
/**
 * The language suffix
 */
define('ZERO_LANG', $arr[0]);
define('LANG', $arr[0]);
