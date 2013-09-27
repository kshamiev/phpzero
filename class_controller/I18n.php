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
    public static function Search_Path_I18n($folder_list, $lang = '')
    {
        if ( '' == $lang )
            $lang = Zero_App::$Route->Lang;
        $path = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
            return $path;
        //        $path = ZERO_PATH_PHPZERO . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        //        if ( file_exists($path) )
        //            return $path;
        if ( Zero_App::$Config->Log_Profile_Warning )
            Zero_Logs::Set_Message('I18N NOT FOUND FILE: ' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1], 'warning');
        return '';
    }

    /**
     * Perevod po cliuchevoi` stroke
     *
     * @param $file_name Imia iazy`kovogo fai`la (imia modeli, kontrollera)
     * @param $key string
     * @param string $value_default znachenie po umolchaniiu (esli ne nai`detsia perevod)
     * @return string nai`denny`i` perevod
     */
    public static function T($file_name, $key, $value_default = '')
    {
        $folder_list = explode('_', $file_name);
        $file_name = $folder_list[0] . '_' . $folder_list[1];
        if ( !isset(self::$_I18n[$file_name]) )
        {
            self::$_I18n[$file_name] = [];
            if ( $path = self::Search_Path_I18n($folder_list) )
                self::$_I18n[$file_name] = include $path;
        }
        if ( isset(self::$_I18n[$file_name][$key]) )
            return self::$_I18n[$file_name][$key];
        if ( isset(self::$_I18n[$file_name]['translation ' . $key]) )
            return self::$_I18n[$file_name]['translation ' . $key];
        //
        if ( 'App' != $folder_list[1] )
        {
            $folder_list = [$folder_list[0], 'App'];
            $file_name1 = $folder_list[0] . '_' . $folder_list[1];
            if ( !isset(self::$_I18n[$file_name1]) )
            {
                self::$_I18n[$file_name1] = [];
                if ( $path = self::Search_Path_I18n($folder_list) )
                    self::$_I18n[$file_name1] = include $path;
            }
            if ( isset(self::$_I18n[$file_name1][$key]) )
                return self::$_I18n[$file_name1][$key];
            if ( isset(self::$_I18n[$file_name1]['translation ' . $key]) )
                return self::$_I18n[$file_name1]['translation ' . $key];
        }
        //
        if ( Zero_App::$Config->Log_Profile_Warning )
            Zero_Logs::Set_Message('I18N NOT FOUND KEY: ' . Zero_App::$Route->Lang . ' -> ' . $file_name . '->' . $key, 'warning');
        return $value_default;
    }
}
