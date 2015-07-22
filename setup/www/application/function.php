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
    $Mode = Zero_App::MODE_WEB;

    // если запрос консольный
    if ( !isset($_SERVER['REQUEST_URI']) )
    {
        $Mode = Zero_App::MODE_CONSOLE;
        return [$Mode, $Lang, $Url];
    }

    // главная страница
    if ( $_SERVER['REQUEST_URI'] == '/' )
        return [$Mode, $Lang, $Url];

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
            return [$Mode, $Lang, $Url];
    }

    // api
    if ( Zero_App::MODE_API == $row[0] || Zero_App::MODE_API == Zero_App::$Config->Site_DomainSub )
    {
        $Mode = Zero_App::MODE_API;
        app_request_data_api();
    }

    // чпу параметры
    $p = array_pop($row);
    $p = explode('.', $p)[0];
    $p = explode('-', $p);
    Zero_App::$RequestParams = $p;
    $row[] = $p[0];

    // uri
    $Url .= '/' . implode('/', $row);
    return [$Mode, $Lang, $Url];
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
