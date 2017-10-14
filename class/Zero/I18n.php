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
        if ( !isset(self::$_I18n['Message']) )
        {
            self::$_I18n['Message'] = [];
            if ( file_exists($path = ZERO_PATH_ZERO . '/i18n/' . ZERO_LANG . '/' . 'Message' . '.php') )
                self::$_I18n['Message'] = self::$_I18n['Message'] + include $path;
            if ( file_exists($path = ZERO_PATH_APP . '/i18n/' . ZERO_LANG . '/' . 'Message' . '.php') )
                self::$_I18n['Message'] = self::$_I18n['Message'] + include $path;
            if ( file_exists($path = ZERO_PATH_SITE . '/i18n/' . ZERO_LANG . '/' . 'Message' . '.php') )
                self::$_I18n['Message'] = self::$_I18n['Message'] + include $path;
        }
        // инициализация перевода
        if ( isset(self::$_I18n['Message'][$code]) )
        {
            array_unshift($params, self::$_I18n['Message'][$code]);
        }
        else if ( 0 < $code )
        {
            Zero_Logs::Set_Message_Warninng("I18N NOT FOUND MESSAGE: " . LANG . ' / ' . $code);
        }
        // перевод
        return strval(zero_sprintf($params));
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
        if ( !isset(self::$_I18n[$section]) )
        {
            self::$_I18n[$section] = [];
            if ( file_exists($path = ZERO_PATH_ZERO . '/i18n/' . ZERO_LANG . '/' . $section . '.php') )
                self::$_I18n[$section] = array_merge(self::$_I18n[$section], include $path);
            if ( file_exists($path = ZERO_PATH_APP . '/i18n/' . ZERO_LANG . '/' . $section . '.php') )
                self::$_I18n[$section] = array_merge(self::$_I18n[$section], include $path);
            if ( file_exists($path = ZERO_PATH_SITE . '/i18n/' . ZERO_LANG . '/' . $section . '.php') )
                self::$_I18n[$section] = array_merge(self::$_I18n[$section], include $path);
        }
        // перевод
        if ( isset(self::$_I18n[$section][$file_name . ' ' . $key]) )
        {
            return self::$_I18n[$section][$file_name . ' ' . $key];
        }
        else if ( isset(self::$_I18n[$section][$key]) )
        {
            return self::$_I18n[$section][$key];
        }
        Zero_Logs::Set_Message_Warninng("I18N NOT FOUND KEY: " . LANG . "->{$section} / " . $file_name . '->' . $key);
        return $file_name . ' ' . $key;
    }
}
