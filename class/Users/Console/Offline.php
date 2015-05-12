<?php
/**
 * Контроль активности пользователя.
 *
 * Определение неактивных пользоватлей в системе
 *
 * @package Console.Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Console_Offline extends Zero_Controller
{
    /**
     * Контроль активности пользователя.
     */
    public function Action_Default()
    {
        Zero_Users::DB_Offline(Zero_App::$Config->Site_UsersTimeoutOnline);
    }
}