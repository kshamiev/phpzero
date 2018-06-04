<?php

/**
 * Деплой или обновление проекта
 *
 * @package Zero.System.Console
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2018.05.18
 */
class Zero_System_Console_Deploy extends Zero_Controller
{
    /**
     * Формирование документации проекта
     *
     * @return int
     */
    public function Action_Default()
    {
        $path = dirname(ZERO_PATH_SITE);

        // Выкладываем проект
        foreach (Zero_App::$Config->Deploy->PathDeploy as $k => $p)
        {
            if ( !$p = trim($p) )
                unset(Zero_App::$Config->Deploy->PathDeploy[$k]);
            else
                Zero_App::$Config->Deploy->PathDeploy[$k] = $p;
        }
        foreach (Zero_App::$Config->Deploy->PathDeploy as $k => $p)
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
                return false;
            }
            else
            {
                Zero_Logs::Set_Message_Notice("git pull '{$p}'");
            }
        }
        Helper_File::Folder_Remove(ZERO_PATH_CACHE);
        return true;
    }
}