<?php

/**
 * Интернационализация
 *
 * Переводы интефесой и виджетов на разные языки
 *
 * @package Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 */
class Zero_I18n
{
    /**
     * Massiv soderzhashchii` danny`e iazy`kovy`kh fai`lov perevoda
     *
     * @var array
     */
    private static $_I18n = [];

    public static function View($key, $lang = ZERO_LANG)
    {
        return self::T('View', $key, $lang);
    }

    public static function Model($key, $key1)
    {
        $res = self::T('Model', $key . ' ' . $key1);
        if ( $res == $key . ' ' . $key1 )
        {
            $res = self::T('Model', $key1);
        }
        return $res;
    }

    public static function Controller($key, $key1)
    {
        $res = self::T('Controller', $key . ' ' . $key1);
        if ( $res == $key . ' ' . $key1 )
        {
            $res = self::T('Controller', $key1);
        }
        return $res;
    }

    /**
     * Перевод по ключу
     *
     * @param $section string
     * @param $key string
     * @param $lang string
     * @return string|array найденный перевод
     */
    protected static function T($section, $key, $lang = ZERO_LANG)
    {
        // инициализация файла перевода
        if ( !isset(self::$_I18n[$lang][$section]) )
        {
            self::$_I18n[$lang][$section] = [];
            if ( file_exists($path = ZERO_PATH_ZERO . '/i18n/' . $lang . '/' . $section . '.php') )
                self::$_I18n[$lang][$section] = array_merge(self::$_I18n[$lang][$section], include $path);
            if ( file_exists($path = ZERO_PATH_APP . '/i18n/' . $lang . '/' . $section . '.php') )
                self::$_I18n[$lang][$section] = array_merge(self::$_I18n[$lang][$section], include $path);
            if ( file_exists($path = ZERO_PATH_SITE . '/i18n/' . $lang . '/' . $section . '.php') )
                self::$_I18n[$lang][$section] = array_merge(self::$_I18n[$lang][$section], include $path);
        }
        // перевод
        if ( isset(self::$_I18n[$lang][$section][$key]) )
        {
            return self::$_I18n[$lang][$section][$key];
        }
        Zero_Logs::Set_Message_Warning("I18N NOT FOUND KEY: " . $lang . "->{$section} / '" . $key . "'");
        return $key;
    }

    public static function Message($code, $params = [])
    {
        $lang = Zero_App::$Config->Site_Language;
        // инициализация файла перевода
        if ( !isset(self::$_I18n[$lang]['Message']) )
        {
            self::$_I18n[$lang]['Message'] = [];
            if ( file_exists($path = ZERO_PATH_SITE . '/i18n/' . $lang . '/' . 'Message' . '.php') )
                self::$_I18n[$lang]['Message'] = self::$_I18n[$lang]['Message'] + include $path;
            if ( file_exists($path = ZERO_PATH_APP . '/i18n/' . $lang . '/' . 'Message' . '.php') )
                self::$_I18n[$lang]['Message'] = self::$_I18n[$lang]['Message'] + include $path;
            if ( file_exists($path = ZERO_PATH_ZERO . '/i18n/' . $lang . '/' . 'Message' . '.php') )
                self::$_I18n[$lang]['Message'] = self::$_I18n[$lang]['Message'] + include $path;
        }
        // инициализация перевода
        if ( isset(self::$_I18n[$lang]['Message'][$code]) )
        {
            array_unshift($params, self::$_I18n[$lang]['Message'][$code]);
        }
        else if ( 0 < $code )
        {
            Zero_Logs::Set_Message_Warning("I18N NOT FOUND MESSAGE: " . $lang . ' / ' . $code);
        }
        // перевод
        return strval(zero_sprintf($params));
    }
}
