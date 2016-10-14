<?php
/**
 * Функции общего назначения конкретного проекта
 *
 * @package General.Function
 */
function app_route()
{
    // главная страница
    if ( $_SERVER['REQUEST_URI'] == Zero_App::$Route )
        return;

    // инициализация
    $row = explode('/', strtolower(rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/')));

    // язык
    if ( Zero_App::$Config->Site_Language != $row[0] && isset(Zero_App::$Config->Language[$row[0]]) )
    {
        Zero_App::$Config->Site_Language = array_shift($row);
    }

    // чпу параметры
    $p = array_pop($row);
    $p = explode('.', $p)[0];
    $p = explode('_', $p);
    if ( 1 < count($p) )
        Zero_App::$RouteParams = explode('-', $p[1]);
    $row[] = $p[0];

    // uri
    Zero_App::$Route = '/' . implode('/', $row);
    return;
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
