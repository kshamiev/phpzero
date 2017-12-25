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
        $_REQUEST['method'] = strtolower($_REQUEST['method']);
        if ( !$_REQUEST['method'] )
        {
            $response = [
                'GET' => 'Описание запроса',
                'PUT' => 'Описание запроса',
                'POST' => 'Описание запроса',
                'DELETE' => 'Описание запроса',
            ];
            Zero_Response::Json($response);
        }
        switch ( $_REQUEST['method'] )
        {
            case 'get':
                $response = [
                    'Name' => 'Описание запроса',
                    'Uri' => '?... Параметры uri',
                ];
                Zero_Response::Json($response);
                break;
            case 'put':
                $response = [
                    'Name' => 'Описание запроса',
                    'Uri' => '?... Параметры uri',
                ];
                Zero_Response::Json($response);
                break;
            case 'post':
                $response = [
                    'Name' => 'Описание запроса',
                    'Uri' => '?... Параметры uri',
                ];
                Zero_Response::Json($response);
                break;
            case 'delete':
                $response = [
                    'Name' => 'Описание запроса',
                    'Uri' => '?... Параметры uri',
                ];
                Zero_Response::Json($response);
                break;
            default:
                Zero_Response::Json('метод не реализован');
                break;
        }
        return true;
    }
}
