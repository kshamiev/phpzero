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
            Zero_Response::Json(['?method=[GET,POST,PUT,DELETE]']);

        $_REQUEST['method'] = strtolower($_REQUEST['method']);
        switch ( $_REQUEST['method'] )
        {
            case 'get':
                $response = [
                    'Uri' => '',
                ];
                Zero_Response::Json($response);
                break;
            case 'post':
                $response = [
                    'Uri' => '',
                ];
                Zero_Response::Json($response);
                break;
            case 'put':
                $response = [
                    'Uri' => '',
                ];
                Zero_Response::Json($response);
                break;
            case 'delete':
                $response = [
                    'Uri' => '',
                ];
                Zero_Response::Json($response);
                break;
            default:
                Zero_Response::Json('метод не реализован');
                break;
        }
        return true;
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
