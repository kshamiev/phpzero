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
        //
        self::$_I18n[$folder_list[0] . '_' . $folder_list[1]] = [];
        if ( file_exists($path) )
        {
            self::$_I18n[$folder_list[0] . '_' . $folder_list[1]] = include $path;
            return $path;
        }
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
            self::Search_Path_I18n($folder_list);
            //            if ( $path = self::Search_Path_I18n($folder_list) )
            //                self::$_I18n[$file_name] = include $path;
        }
        if ( isset(self::$_I18n[$file_name][$section][$key]) )
            return self::$_I18n[$file_name][$section][$key];
        // поиск в общем фалйе перевода
        $file_name_all = $folder_list[0] . '_General';
        if ( !isset(self::$_I18n[$file_name_all]) )
        {
//            self::$_I18n[$file_name_all] = [];
            self::Search_Path_I18n([$folder_list[0], 'General']);
            //            if ( $path = self::Search_Path_I18n([$folder_list[0], 'All']) )
            //                self::$_I18n[$file_name_all] = include $path;
        }
        if ( isset(self::$_I18n[$file_name_all][$section][$key]) )
            return self::$_I18n[$file_name_all][$section][$key];
        //
        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND KEY: ' . LANG . ' -> ' . $file_name . ' -> ' . $section . ' -> ' . $key);
        return $key;
    }

    public static function Model($file_name, $key)
    {
        return self::T($file_name, 'model', $key);
    }

    public static function View($file_name, $key)
    {
        return self::T($file_name, 'view', $key);
    }

    public static function Controller($file_name, $key)
    {
        return self::T($file_name, 'controller', $key);
    }


    public static function CodeMessage($file_name, $code, $params = [])
    {
        // инициализация файла перевода
        $folder_list = explode('_', $file_name);
        $folder_list[1] = 'MessageResponse';
        $file_name = $folder_list[0] . '_' . $folder_list[1];
        if ( !isset(self::$_I18n[$file_name]) )
        {
            self::Search_Path_I18n($folder_list);
        }
        // инициализация шаблона и глобального кода собщения
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
            Zero_Logs::Set_Message_Warninng('I18N NOT FOUND CODE MESSAGE: ' . LANG . ' -> ' . $file_name . ' -> ' . $code);
        }
        return [$codeGlobal, zero_sprintf($params)];
    }
}
