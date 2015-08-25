<?php

/**
 * Sistema pervodov.
 *
 * @package General.Component Интернационализация
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_I18n
{

    /**
     * Massiv soderzhashchii` danny`e iazy`kovy`kh fai`lov perevoda
     *
     * @var array
     */
    private static $_I18n = [];

    public static function Model($file_name, $key)
    {
        return self::T($file_name, 'Model', $key);
    }

    public static function View($file_name, $key)
    {
        return self::T($file_name, 'View', $key);
    }

    public static function Controller($file_name, $key)
    {
        return self::T($file_name, 'Controller', $key);
    }


    public static function Message($file_name, $code, $params = [])
    {
        // инициализация файла перевода
        $folder_list = explode('_', $file_name);
        $folder_list[1] = 'Message';
        $file_name = $folder_list[0] . '_' . $folder_list[1];
        if ( !isset(self::$_I18n[$file_name]) )
        {
            self::Search_Path_I18n($folder_list);
        }
        // инициализация перевода
        $codeGlobal = $code;
        if ( isset(self::$_I18n[$file_name][$code]) )
        {
            array_unshift($params, self::$_I18n[$file_name][$code]);
            //
            $config = Zero_Config::Get_Config($folder_list[0], 'config');
            settype($config['GlobalCodeMessage'], 'int');
            $codeGlobal = $config['GlobalCodeMessage'] * 10000 + $code;
        }
        else if ( 0 < $code )
        {
            Zero_Logs::Set_Message_Warninng('I18N NOT FOUND MESSAGE: ' . LANG . ' -> ' . $file_name . ' -> ' . $code);
        }
        // перевод
        return [$codeGlobal, strval(zero_sprintf($params))];
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
        // инициализация файла перевода
        $folder_list = explode('_', $file_name);
        $folder_list[1] = $section;
        $file_name = $folder_list[0] . '_' . $folder_list[1];
        if ( !isset(self::$_I18n[$file_name]) )
        {
            self::Search_Path_I18n($folder_list);
        }
        // перевод
        if ( isset(self::$_I18n[$file_name][$key]) )
        {
            return self::$_I18n[$file_name][$key];
        }
//        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND KEY: ' . LANG . ' -> ' . $file_name . ' -> ' . $key);
        return $key;
    }

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
        self::$_I18n[$folder_list[0] . '_' . $folder_list[1]] = [];
        //
        $path = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
        {
            self::$_I18n[$folder_list[0] . '_' . $folder_list[1]] = include $path;
            return $path;
        }
        $path = ZERO_PATH_SITE . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
        {
            self::$_I18n[$folder_list[0] . '_' . $folder_list[1]] = include $path;
            return $path;
        }
        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND FILE: ' . $path);
        return '';
    }
}
