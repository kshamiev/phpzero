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
    //// Пример контроллера для WEB
    /**
     * Контроллер по умолчанию.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    //// Пример консольного контроллера

    /**
     * Какой-то контроллер
     *
     * @return boolean flag статус выполнения
     */
    public function Action_DefaultRename()
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

    //// Пример контроллера для API

    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     */
    public function Action_GET()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     */
    public function Action_PUT()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     */
    public function Action_POST()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     */
    public function Action_DELETE()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Опции запроса
     *
     * @sample /api/v1/sample?param=value...
     */
    public function Action_OPTIONS()
    {
        settype($_REQUEST['method'], 'string');
        if ( !$_REQUEST['method'] )
            Zero_App::ResponseJson200(null, 0, ['?method=[GET,POST,PUT,DELETE]']);

        $_REQUEST['method'] = strtolower($_REQUEST['method']);
        switch ( $_REQUEST['method'] )
        {
            case 'get':
                $response = [];
                Zero_Response::JsonRest($response);
                break;
            case 'post':
                $response = [];
                Zero_Response::JsonRest($response);
                break;
            case 'put':
                $response = [];
                Zero_Response::JsonRest($response);
                break;
            case 'delete':
                $response = [];
                Zero_Response::JsonRest($response);
                break;
            default:
                Zero_Response::JsonRest('метод не реализован');
                break;
        }
        return true;
    }

    ////

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

    /**
     * Fabrika po sozdaniiu kontrollerov.
     *
     * Rabotaet cherez sessiiu. Indeks: $class_name
     *
     * @param array $properties vhodny`e parametry` plagina
     * @return Zero_Controller
     */
    public static function Factory($properties = [])
    {
        if ( !$result = Zero_Session::Get(__CLASS__) )
        {
            $result = self::Make($properties);
            Zero_Session::Set(__CLASS__, $result);
        }
        return $result;
    }
}
