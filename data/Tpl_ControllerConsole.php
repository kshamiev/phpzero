<?php

/**
 * <Comment>
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Sample extends Zero_Controller
{
    /**
     * Какой-то контроллер
     *
     * @return int статус выполнения (0 - ok, 1 - error)
     */
    public function Action_Default()
    {
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        foreach (glob($path . '/.+') as $file)
        {
            $timeOutMinute = (time() - filemtime($file)) * 60;
            if ( 60 < $timeOutMinute )
                unlink($file);
        }
        return 0;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры контроллера (обычно в режиме плагина)
     * @return Zero_Controller
     */
    public static function Make($properties = [])
    {
        $Controller = new self();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * Работает через сессию. Indeks: __CLASS__
     *
     * @param array $properties входные параметры контроллера (обычно в режиме плагина)
     * @return Zero_Controller
     */
    public static function Factory($properties = [])
    {
        if ( !$Controller = Zero_Session::Get(__CLASS__) )
        {
            $Controller = self::Make($properties);
            Zero_Session::Set(__CLASS__, $Controller);
        }
        return $Controller;
    }
}
