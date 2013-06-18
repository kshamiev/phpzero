<?php

/**
 * Helper. A helper class for working with the modules.
 *
 * @package Zero.Helper
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
final class Zero_Helper_Modules
{
    /**
     * Version checking each other
     *
     * @param string $version_check Check the version
     * @param string $version_target The standard version
     * @return int -1 version down $version_target, 0 version equal, 1 version up $version_target
     */
    public static function Check_Version($version_check, $version_target)
    {
        if ( !$version_check )
            return -1;
        $arr1 = explode('.', $version_check);
        $arr2 = explode('.', $version_target);
        //  release
        if ( $arr1[0] < $arr2[0] )
            return -1;
        else if ( $arr1[0] > $arr2[0] )
            return 1;
        //  feature
        if ( $arr1[1] < $arr2[1] )
            return -1;
        else if ( $arr1[1] > $arr2[1] )
            return 1;
        //  bugfix
        if ( $arr1[2] < $arr2[2] )
            return -1;
        else if ( $arr1[2] > $arr2[2] )
            return 1;
        return 0;
    }

    /**
     * Getting the module configuration (Installed)
     *
     * @param string $module module
     * @param string $section section configure
     * @return array
     */
    public static function Get_Config_Set($module = '', $section = '')
    {
        return self::Get_Config(ZERO_PATH_APPLICATION, $module, $section, true);
    }

    /**
     * Getting the module configuration (All)
     *
     * @param string $module модуль
     * @param string $section section configure
     * @return array
     */
    public static function Get_Config_All($module = '', $section = '')
    {
        return self::Get_Config(ZERO_PATH_APPLICATION, $module, $section, false);
    }

    /**
     * Getting the module configuration
     *
     * @param string $area search area
     * @param string $module module
     * @param string $section section configure
     * @param bool $flag configuration different
     * @return array
     */
    protected static function Get_Config($area, $module = '', $section = '', $flag = true)
    {
        $configuration = [];
        if ( $module )
        {
            if ( true == $flag && !file_exists($area . '/' . $module . '/setup/INSTALL') )
                return $configuration;
            if ( $section )
            {
                if ( file_exists($path = $area . '/' . $module . '/config/' . $section . '.php') )
                    $configuration = require $path;
            }
            else if ( is_dir($area . '/' . $module . '/config') )
            {
                foreach (glob($area . '/' . $module . '/config/*.php') as $config)
                {
                    $section = substr(basename($config), 0, -4);
                    $configuration[$section] = require $config;
                }
            }
        }
        else
        {
            foreach (glob($area . '/*', GLOB_ONLYDIR) as $path)
            {
                $module = basename($path);
                if ( true == $flag && !file_exists($area . '/' . $module . '/setup/INSTALL') )
                    continue;
                if ( !is_dir($path . '/config') )
                    continue;
                if ( $section )
                {
                    if ( file_exists($path .= '/config/' . $section . '.php') )
                        $configuration[$module] = require $path;
                }
                else
                {
                    foreach (glob($path . '/config/*.php') as $config)
                    {
                        $section = substr(basename($config), 0, -4);
                        $configuration[$module][$section] = require $config;
                    }
                }
            }
        }
        return $configuration;
    }
}