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

$controllers = Zero_DB::Select_Array("SELECT `ID`, `Name`, `Controller`, `Url` FROM `Controllers` WHERE `Typ` = 'Api'");
foreach ($controllers as $con)
{
    $response = Zero_App::$Request->Test('OPTIONS', $con['Url']);

    $tpl .= "<tr>\n";
    if ( 200 != $response->Head['http_code'] )
    {
        $tpl .= "<td>{$response->Code}</td>\n";
        $tpl .= "<td>{$response->Message}</td>\n";
        $tpl .= "<td>{$con['Controller']}</td>\n";
        $tpl .= "<td>[OPTIONS] {$response->Head['url']}</td>\n";
    }
    else
    {
        $message = '';
        foreach ($response->Body as $met => $desc)
        {
            $message .= "[{$met}] {$desc}\n<br>";
        }
        $tpl .= "<td>{$response->Code}</td>\n";
        $tpl .= "<td>{$message}</td>\n";
        $tpl .= "<td>{$con['Controller']}</td>\n";
        $tpl .= "<td>[OPTIONS] {$response->Head['url']}</td>\n";
    }
    $tpl .= "</tr>\n";
}
$tpl .= '
</table>
';

Zero_Response::Html($tpl);

