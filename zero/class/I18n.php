<?php

/**
 * Sistema pervodov.
 *
 * @package Zero.Component Интернационализация
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
        $index = $folder_list[0] . '_' . $folder_list[1];
        if ( !isset(self::$_I18n[$index]) )
        {
            self::$_I18n[$index] = self::Search_Path_I18n([strtolower($folder_list[0]), $folder_list[1]]);
        }
        // инициализация перевода
        $codeGlobal = $code;
        if ( isset(self::$_I18n[$index][$code]) )
        {
            array_unshift($params, self::$_I18n[$index][$code]);
            //
            $codeGlobal = 10000 + $code;
        }
        else if ( 0 < $code )
        {
            Zero_Logs::Set_Message_Warninng("I18N NOT FOUND MESSAGE ({$folder_list[0]}_Message): " . LANG . ' -> ' . $index . ' -> ' . $code);
        }
        // перевод
        return [$codeGlobal, strval(zero_sprintf($params))];
    }

    /**
     * Perevod po cliuchevoi` stroke
     *
     * @param $file_name string Имиа языкового файла (имиа модели, контроллера)
     * @param $section string
     * @param $key string
     * @return string|array найденный перевод
     */
    protected static function T($file_name, $section, $key)
    {
        // инициализация файла перевода
        $module = explode('_', $file_name)[0];
        $index = $module . '_' . $section;
        if ( !isset(self::$_I18n[$index]) )
        {
            self::$_I18n[$index] = self::Search_Path_I18n([strtolower($module), $section]);
        }
        // перевод
        if ( isset(self::$_I18n[$index][$file_name . ' ' . $key]) )
        {
            return self::$_I18n[$index][$file_name . ' ' . $key];
        }
        else if ( isset(self::$_I18n[$index][$key]) )
        {
            return self::$_I18n[$index][$key];
        }
        Zero_Logs::Set_Message_Warninng("I18N NOT FOUND KEY ({$module}_{$section}): " . LANG . ' -> ' . $file_name . ' -> ' . $key);
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
        //
        $path = ZERO_PATH_SITE . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
        {
            return include $path;
        }
        //
        $path = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
        {
            return include $path;
        }
        //
        $path = ZERO_PATH_ZERO . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
        if ( file_exists($path) )
        {
            return include $path;
        }
        Zero_Logs::Set_Message_Warninng('I18N NOT FOUND FILE: ' . $path);
        return [];
    }
}
