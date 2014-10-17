<?php

/**
 * Component. Sistema pervodov.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_I18n
{
    /**
     * Massiv soderzhashchii` danny`e iazy`kovy`kh fai`lov perevoda
     *
     * @var array
     */
    private static $_I18n = [];

    /**
     * Poisk mestonahozhdeniia i podcliuchenie iazy`kovogo fai`la.
     *
     * @param array $folder_list spisok papok dlia poiska raspolozheniia iazy`kovogo fai`la
     * @param string $lang prefiks iazy`ka (esli ne ukazan beretsia tekushchii`)
     * @return string put` do iazy`kovogo fai`la
     */
    protected static function Search_Path_I18n($folder_list, $lang = '')
    {
        if ( '' == $lang )
            $lang = ZERO_LANG;
        $path = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
            return $path;
        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND FILE: ' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1]);
        return '';
    }

    /**
     * Perevod po cliuchevoi` stroke
     *
     * @param $file_name Imia iazy`kovogo fai`la (imia modeli, kontrollera)
     * @param $section string
     * @param $key string
     * @return string nai`denny`i` perevod
     */
    protected static function T($file_name, $section, $key)
    {
        $folder_list = explode('_', $file_name);
        $file_name = $folder_list[0] . '_' . $folder_list[1];
        // поиск в целевом фалйе перевода
        if ( !isset(self::$_I18n[$file_name]) )
        {
            self::$_I18n[$file_name] = [];
            if ( $path = self::Search_Path_I18n($folder_list) )
                self::$_I18n[$file_name] = include $path;
        }
        if ( isset(self::$_I18n[$file_name][$section][$key]) )
            return self::$_I18n[$file_name][$section][$key];
        // поиск в общем фалйе перевода
        $file_name_all = $folder_list[0] . '_All';
        if ( $file_name_all != $file_name )
        {
            if ( !isset(self::$_I18n[$file_name_all]) )
            {
                self::$_I18n[$file_name_all] = [];
                if ( $path = self::Search_Path_I18n([$folder_list[0], 'All']) )
                    self::$_I18n[$file_name_all] = include $path;
            }
            if ( isset(self::$_I18n[$file_name_all][$section][$key]) )
                return self::$_I18n[$file_name_all][$section][$key];
        }
        //
        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND KEY: ' . LANG . ' -> ' . $file_name . ' -> ' . $section . ' -> ' . $key);
        return $key;
    }

    public static function Model($file_name, $key)
    {
        return self::T($file_name, 'model', $key);
    }
    public static function ModelArr($file_name, $key)
    {
        $data = self::T($file_name, 'model', $key);
        if ( $data == $key )
            return [];
        return $data;
    }
    public static function View($file_name, $key)
    {
        return self::T($file_name, 'view', $key);
    }
    public static function ViewArr($file_name, $key)
    {
        $data = self::T($file_name, 'view', $key);
        if ( $data == $key )
            return [];
        return $data;
    }
    public static function Controller($file_name, $key)
    {
        return self::T($file_name, 'controller', $key);
    }
    public static function ControllerArr($file_name, $key)
    {
        $data = self::T($file_name, 'controller', $key);
        if ( $data == $key )
            return [];
        return $data;
    }
}
