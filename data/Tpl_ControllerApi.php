<?php

/**
 * <Comment>
 *
 * @package Api.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 * @link /api/.../...
 */
class Api_Controller_Sample extends Zero_Controller
{
    /**
     * Какой-то контроллер
     */
    public function Action_GET()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     */
    public function Action_PUT()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     */
    public function Action_POST()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Какой-то контроллер
     */
    public function Action_DELETE()
    {
        Zero_Response::JsonRest($_REQUEST);
    }

    /**
     * Описание реализованных запросов
     *
     * Параметры, опции
     */
    public function Action_OPTIONS()
    {
        if ( isset($_REQUEST['GET']) || isset($_REQUEST['get']) )
        {
            $response = [
                'Name' => 'Описание запроса',
                'Uri' => '?... Параметры uri',
                // ... тело запроса если есть
            ];
        }
        else if ( isset($_REQUEST['PUT']) || isset($_REQUEST['put']) )
        {
            $response = [
                'Name' => 'Описание запроса',
                'Uri' => '?... Параметры uri',
                // ... тело запроса если есть
            ];
        }
        else if ( isset($_REQUEST['POST']) || isset($_REQUEST['post']) )
        {
            $response = [
                'Name' => 'Описание запроса',
                'Uri' => '?... Параметры uri',
                // ... тело запроса если есть
            ];
        }
        else if ( isset($_REQUEST['DELETE']) || isset($_REQUEST['delete']) )
        {
            $response = [
                'Name' => 'Описание запроса',
                'Uri' => '?... Параметры uri',
                // ... тело запроса если есть
            ];
        }
        else
        {
            $response = [
                'GET' => 'Описание запроса',
                'PUT' => 'Описание запроса',
                'POST' => 'Описание запроса',
                'DELETE' => 'Описание запроса',
            ];
        }
        Zero_Response::Json($response);
    }
}
