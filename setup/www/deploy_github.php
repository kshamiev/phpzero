<?php
/**
 * DEPLOY
 *
 * @package Deploy
 */

//  Connecting application
require __DIR__ . '/zero/class/App.php';
Zero_App::Init();

/**
 * ������������
 */
// ip ������ � ������� �������� ������
$configIpAccess = [
    '1.1.1.1',
];
// ����� ������ (����������� ����������)
$configBranch = 'master';
// ����� ������������ ������� ��� ������
$configDeploy = [
    __DIR__,
    ZERO_PATH_ZERO,
];
//  ������������ ������� ��������� ����������� ������
$configUsers = [
    'kshamiev' => 1,
];
//  �������� ����� (�����������) ������������ ������
$configKeys = [
    'deploy'
];

/**
 * �������������
 */
// ������
if ( $_SERVER['REQUEST_METHOD'] === "PUT" )
{
    $data = file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']);
    $_REQUEST = json_decode($data, true);
}
else if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($GLOBALS["HTTP_RAW_POST_DATA"]) )
{
    $_REQUEST = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
}
else
{
    Zero_Logs::Set_Message_Error('request invalid');
    Zero_App::ResponseConsole();
}

Zero_Logs::File('server', $_SERVER);
Zero_Logs::File('request', $_REQUEST);

// �����
if ( !isset($_REQUEST['ref']) )
{
    Zero_Logs::Set_Message_Error('request invalid');
    Zero_App::ResponseConsole();
}
$branch = explode('/', $_REQUEST['ref'])[2];
if ( $configBranch != $branch )
{
    Zero_Logs::Set_Message_Error($branch . ' not valid');
    Zero_App::ResponseConsole();
}
//  �����
if ( !isset($configUsers[$_REQUEST['pusher']['name']]) )
{
    Zero_Logs::Set_Message_Error('deploy user access denied');
    Zero_App::ResponseConsole();
}
//  �������� �����
if ( !in_array($_REQUEST['head_commit']['message'], $configKeys) )
{
    Zero_Logs::Set_Message_Error('deploy key commit access denied');
    Zero_App::ResponseConsole();
}

/**
 * ������
 */
//  ����������� ������
foreach ($configDeploy as $path)
{
    exec("cd {$path} && git pull", $arr);
    Zero_Logs::File('deploy', $arr);
}
// �������� ����
Zero_Helper_File::Folder_Move(ZERO_PATH_LOG, ZERO_PATH_LOG . date('Y-m-d-H'));
// ���������� ���
Zero_Helper_File::Folder_Remove(ZERO_PATH_CACHE);

/**
 * ����������
 */
Zero_Logs::Set_Message_Notice('deploy successFull');
Zero_App::ResponseConsole();
exit;
