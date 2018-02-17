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
if ( empty($_REQUEST['payload']) )
{
    Zero_Logs::Set_Message_Error('not param payload');
    Zero_Response::Console();
}
$deploy = json_decode($_REQUEST['payload'], true);
file_put_contents(ZERO_PATH_LOG . '/deployRequest.log', date("[d.m.Y H:i:s]\n") . print_r($deploy, true));
// branch
if ( empty($deploy['ref']) )
{
    Zero_Logs::Set_Message_Error('empty ref');
    Zero_Response::Console();
}
if ( Zero_App::$Config->Deploy->Branch != explode('/', $deploy['ref'])[2] )
{
    Zero_Logs::Set_Message_Error('branch not valid: ' . Zero_App::$Config->Deploy->Branch . ' != ' . explode('/', $deploy['ref'])[2]);
    Zero_Response::Console();
}
// Пользователь
if ( empty(Zero_App::$Config->Deploy->Users[$deploy['pusher']['name']]) )
{
    Zero_Logs::Set_Message_Error('deploy user access denied from: ' . $deploy['pusher']['name']);
    Zero_Response::Console();
}
// Ключевое сообщение
if ( Zero_App::$Config->Deploy->CommitMessage && false === strpos(trim($deploy['head_commit']['message']), Zero_App::$Config->Deploy->CommitMessage) )
{
    Zero_Logs::Set_Message_Error('deploy key message not valid: ' . $deploy['head_commit']['message']);
    Zero_Response::Console();
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

    exec("cd {$p}");
//    exec('git checkout -f');
//    exec('git clean -f -d');
    exec("cd {$p} && git pull", $buffer, $code);
    Zero_Logs::Custom_DateTime('AAAAAA', [$buffer, $code, $p]);
    if ( !is_array($buffer) || 0 == count($buffer) )
    {
        Zero_Logs::Set_Message_Error("error git pull '{$p}'");
        Zero_Response::Console();
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
Zero_Response::Console();

