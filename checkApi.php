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

$controllers = Zero_DB::Select_Array("SELECT `ID`, `Name`, `Controller`, `Url`, `Sort` FROM `Controllers` WHERE `Typ` = 'Api' AND 0 < `Sort` ORDER BY 5 ASC");
foreach ($controllers as $con)
{
    $response = Zero_App::$Request->Test('OPTIONS', $con['Url']);
    if ( 200 != $response->Head['http_code'] )
    {
        $tpl .= "<tr>\n";
        $tpl .= "<td>{$response->Code}</td>\n";
        $tpl .= "<td><font color='#996600'>{$response->Message}</font></td>\n";
        $tpl .= "<td>{$con['Controller']}</td>\n";
        $tpl .= "<td>[OPTIONS] {$response->Head['url']}</td>\n";
        $tpl .= "</tr>\n";

        Zero_Logs::Set_Message_Error("ERROR [OPTIONS] {$con['Controller']}");
    }
    else
    {
        $message = '';
        foreach ($response->Body as $method => $desc)
        {
            $res = Zero_App::$Request->Test('OPTIONS', $con['Url'] . "?{$method}=1");
            if ( 200 != $res->Head['http_code'] || !isset($res->Body['Uri']) || !isset($res->Body['Name']) )
            {
                $message .= "<font color='#996600'>[{$method}] {$desc}</font>\n<br>";
                $message .= "options error\n<br>";

                Zero_Logs::Set_Message_Error("ERROR [OPTIONS] [{$method}] {$con['Controller']}");
            }
            else
            {
                $res = Zero_App::$Request->Test($method, $con['Url'] . $res->Body['Uri'], $res->Body);
                if ( 200 != $res->Head['http_code'] )
                {
                    $message .= "<font color='#FF0000'>[{$method}] {$desc}</font>\n<br>";
                    $message .= "[{$res->Code}] {$res->Message}\n<br>";

                    Zero_Logs::Set_Message_Error("ERROR [{$method}] {$con['Controller']}");
                }
                else
                {
                    $message .= "[{$method}] {$desc}\n<br>";

                    Zero_Logs::Set_Message_Error("OK [{$method}] {$con['Controller']}");
                }
            }
            //            pre($response->Body);
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

