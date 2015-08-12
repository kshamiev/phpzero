<?php

/**
 * <Comment>
 *
 * {plugin "Zero_Section_SeoTag" view="Zero_Section_SeoTag"}
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Sample extends Zero_Controller
{
    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
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

    //// Пример контроллера для API
    protected function Action_GET()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    protected function Action_PUT()
    {
        Zero_App::ResponseJson($_REQUEST, 200, 0, []);
    }

    protected function Action_POST()
    {
        Zero_App::ResponseJson($_REQUEST, 200, 0, []);
    }

    protected function Action_DELETE()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    protected function Action_OPTIONS()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    //// Пример консольного контроллера
    /**
     * Initialize the online status is not active users.
     *
     * @return boolean flag stop execute of the next chunk
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
        return true;
    }

    ////
}
