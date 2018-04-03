<?php
/**
 * The entry point to the deploy project.
 * Initialize and run.
 */

require __DIR__ . '/class/Zero/App.php';
Zero_App::Init('deploy');
Zero_App::$Config->Log_Output_Display = true;

/**
 * Проверки
 */
if ( empty($_REQUEST['key']) || Zero_App::$Config->Site_Token != $_REQUEST['key'] )
{
    Zero_Logs::Set_Message_Error('access denied');
    Zero_Response::Html('access denied');
}

/**
 * Работа
 */
$path = dirname(dirname(__DIR__));

// Выкладываем проект
foreach (Zero_App::$Config->Deploy->PathDeploy as $p)
{
    if ( '/' == $p || '.' == $p || '/.' == $p || './' == $p )
        $p = '';
    $p = $path . $p;

    $code = 0;
    $buffer = [];
    exec("cd {$p} && git checkout -f");
    exec("cd {$p} && git clean -f -d");
    exec("cd {$p} && git pull", $buffer, $code);
    if ( 0 < $code )
    {
        Zero_Logs::Set_Message_Error("error git pull '{$p}'");
        Zero_Response::Html("error git pull '{$p}'");
    }
    else
    {
        Zero_Logs::Set_Message_Notice("git pull '{$p}'");
    }
}

// Сбрасываем кеш
Helper_File::Folder_Remove($path . '/cache');

// Завершение
Zero_Logs::Set_Message_Notice('deploy successFull ' . $deploy['pusher']['name']);
Zero_Response::Html('deploy successFull ' . $deploy['pusher']['name']);

