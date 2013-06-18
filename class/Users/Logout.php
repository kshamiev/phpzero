<?php

/**
 * Plugin. User exit.
 *
 * @package Zero.Users.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Logout extends Zero_Plugin
{
    /**
     * Initialize the stack chunks
     *
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('View');
    }

    /**
     * User exit.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        Zero_App::$Users = Zero_Model::Make('Zero_Users');
        //        Zero_App::Redirect(Zero_App::$Config->Http);
        return true;
    }
}