<?php
/**
 * The entry point to the deploy project.
 * Initialize and run.
 */

require __DIR__ . '/class/Zero/App.php';
Zero_App::Init('deploy');

/**
 * Проверки
 */
// Zero_Logs::Custom('deploy', $_REQUEST);
$deploy = json_decode($_REQUEST['payload'], true);
// branch
if ( !isset($deploy['ref']) )
{
    Zero_Logs::Set_Message_Error('empty ref');
    Zero_Response::Console();
}
if ( Zero_App::$Config->Deploy->Branch != explode('/', $deploy['ref'])[2] )
{
    Zero_Response::Console();
}
// Пользователь
if ( empty(Zero_App::$Config->Deploy->Users[$deploy['pusher']['name']]) )
{
    Zero_Logs::Set_Message_Error('deploy user access denied from: ' . $deploy['pusher']['name']);
    Zero_Response::Console();
}
// Ключевое сообщение
if ( strpos($deploy['head_commit']['message'], Zero_App::$Config->Deploy->CommitMessage) )
{
    Zero_Logs::Set_Message_Error('deploy key message not valid: ' . $deploy['head_commit']['message']);
    Zero_Response::Console();
}

/**
 * Работа
 */
$path = dirname(dirname(__DIR__));

// Выкладываем основной проект
exec("cd {$path}");
exec('git checkout -f');
exec('git clean -f -d');
exec('git pull', $buffer);
if ( !is_array($buffer) || 0 == count($buffer) )
{
    Zero_Logs::Set_Message_Error("error git pull ({$path})");
    Zero_Response::Console();
}
else
{
    Zero_Logs::Set_Message_Notice("git pull ({$path})");
}

// Выкладываем проект
foreach (Zero_App::$Config->Deploy->PathDeploy as $p)
{
    exec("cd {$path}{$p}");
    exec('git checkout -f');
    exec('git clean -f -d');
    exec('git pull', $buffer);
    if ( !is_array($buffer) || 0 == count($buffer) )
    {
        Zero_Logs::Set_Message_Error("error git pull ({$path})");
        Zero_Response::Console();
    }
    else
    {
        Zero_Logs::Set_Message_Notice("git pull ({$path})");
    }
}

// Сбрасываем кеш
Helper_File::Folder_Remove($path . '/cache');

// Завершение
Zero_Logs::Set_Message_Notice('deploy successFull ' . $_REQUEST['pusher']['name']);
Zero_Response::Console();

