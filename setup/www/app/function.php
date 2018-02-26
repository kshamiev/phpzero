<?php
/**
 * Функции общего назначения конкретного проекта
 *
 * @package General.Function
 */
function app_route()
{
    // главная страница
    if ( !$_SERVER['REQUEST_URI'] )
        return;

    // инициализация
    $row = explode('/', rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/'));

    // язык
    if ( Zero_App::$Config->Site_Language != $row[0] && isset(Zero_App::$Config->Site_Languages[$row[0]]) )
    {
        Zero_App::$Config->Site_Language = array_shift($row);
    }

    // чпу параметры
    $p = explode('-', explode('.', end($row))[0]);
    foreach ($p as $v)
    {
        if ( 0 < $v )
        {
            Zero_App::$RouteParams[1] = $p;
            array_pop($row);
            break;
        }
    }

    // uri
    Zero_App::$RouteParams[0] = '/' . implode('/', $row);
    return;
}

function app_request_data_api()
{
    if ( $_SERVER['REQUEST_METHOD'] === "PUT" || $_SERVER['REQUEST_METHOD'] === "POST" )
    {
        if ( isset($GLOBALS["HTTP_RAW_POST_DATA"]) )
            $data = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
        else
            $data = json_decode(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), true);
        if ( !is_array($data) )
            $data = [$data];
        $_REQUEST = array_merge_recursive($_REQUEST, $data);
    }
}
