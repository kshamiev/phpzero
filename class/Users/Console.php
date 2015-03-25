<?php
/**
 * Controller. User authentication.
 *
 * @package Console.Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Console extends Zero_Controller
{
    /**
     * Initialize the online status is not active users.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Offline()
    {
        Zero_Users::DB_Offline(Zero_App::$Config->Site_UsersTimeoutOnline);
        return $this->View;
    }
}