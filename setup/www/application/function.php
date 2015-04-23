<?php
/**
 * Функции общего назначения конкретного проекта
 *
 * @package General.Function
 */
function app_route()
{
    $Url = '/';
    $Lang = Zero_App::$Config->Site_Language;
    Zero_App::$Mode = Zero_App::MODE_WEB;

    // если запрос консольный
    if ( !isset($_SERVER['REQUEST_URI']) )
    {
        Zero_App::$Mode = Zero_App::MODE_CONSOLE;
        return [$Lang, $Url];
    }

    // главная страница
    if ( $_SERVER['REQUEST_URI'] == '/' )
        return [$Lang, $Url];

    // инициализация
    $Url = '';
    if ( substr($_SERVER['REQUEST_URI'], -1) == '/' )
    {
        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, -1);
    }
    $row = explode('/', strtolower(rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/')));

    // язык
    if ( $Lang != $row[0] && isset(Zero_App::$Config->Language[$row[0]]) )
    {
        $Lang = array_shift($row);
        $Url = '/' . $Lang;
        if ( count($row) == 0 )
            return [$Lang, $Url];
    }

    // api
    if ( 'api' == $row[0] )
    {
        Zero_App::$Mode = Zero_App::MODE_API;
        app_request_data_api();
    }

    $Url .= '/' . implode('/', $row);
    return [$Lang, $Url];
}

function app_request_data_api()
{
    // Инициализация входных параметров и данных в случае api
    if ( $_SERVER['REQUEST_METHOD'] === "PUT" )
    {
        $data = file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']);
        $_REQUEST = json_decode($data, true);
    }
    else if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($GLOBALS["HTTP_RAW_POST_DATA"]) )
    {
        $_REQUEST = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
    }
}
