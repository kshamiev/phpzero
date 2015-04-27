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

/**
 * The relative url path to the project (site)
 */
define('ZERO_URL', Zero_App::Get_Url());
define('URL', Zero_App::Get_Url());
/**
 * The language suffix
 */
define('ZERO_LANG', Zero_App::Get_Lang());
define('LANG', Zero_App::Get_Lang());
