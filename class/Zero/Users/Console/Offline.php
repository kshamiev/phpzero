<?php
/**
 * Оперделение не  активных пользователей.
 *
 * Определение неактивных пользоватлей в системе
 *
 * @package Zero.Console.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Console_Offline extends Zero_Controller
{
    /**
     * Оперделение не  активных пользователей.
     *
     * @return int
     */
    public function Action_Default()
    {
        Zero_Users::DB_Offline(Zero_App::$Config->Site_UsersTimeoutOnline);
        return 0;
    }
}