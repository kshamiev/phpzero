<?php
/**
 * The entry point to the deploy project.
 * Initialize and run.
 */
require __DIR__ . '/class/Zero/App.php';
Zero_App::Init('CheckApiController');

$tpl = '
<table width="90%" cellspacing="2" cellpadding="4" border="1">
    <tr>
        <th>Код ответа</th>
        <th>Сообщение</th>
        <th>Контроллер</th>
        <th>Запрос</th>
    </tr>
';

$controllers = Zero_DB::Select_Array("SELECT `ID`, `Name`, `Controller`, `Url` FROM `Controllers` WHERE `Typ` = 'Api' ORDER BY 3 ASC");
foreach ($controllers as $con)
{
    $response = Zero_App::$Request->Test('OPTIONS', $con['Url']);
    if ( 200 != $response->Head['http_code'] )
    {
        $tpl .= "<tr bgcolor='#FFCCCC'>\n";
        $tpl .= "<td>{$response->Code}</td>\n";
        $tpl .= "<td>{$response->Message}</td>\n";
        $tpl .= "<td>{$con['Controller']}</td>\n";
        $tpl .= "<td>[OPTIONS] {$response->Head['url']}</td>\n";
        $tpl .= "</tr>\n";
    }
    else
    {
        $message = '';
        foreach ($response->Body as $met => $desc)
        {
            $response = Zero_App::$Request->Test('OPTIONS', $con['Url']);

            $message .= "[{$met}] {$desc}\n<br>";
        }
        $tpl .= "<tr>\n";
        $tpl .= "<td>{$response->Code}</td>\n";
        $tpl .= "<td>{$message}</td>\n";
        $tpl .= "<td>{$con['Controller']}</td>\n";
        $tpl .= "<td>{$response->Head['url']}</td>\n";
        $tpl .= "</tr>\n";
    }
}
$tpl .= '
</table>
';

Zero_Response::Html($tpl);

