<?php

/**
 * Controller. User authentication.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Login extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
        $this->View = new Zero_View(get_class($this));
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        if ( 'Login' == $action )
        {
            if ( false == $this->Chunk_Login() )
                Zero_App::Redirect(Zero_App::$Config->Http . '/user');
        }
        else if ( 'Logout' == $action )
        {
            $this->Chunk_Logout();
            Zero_App::Redirect(Zero_App::$Config->Http);
        }
        $this->View->Assign('Users', Zero_App::$Users);
    }

    /**
     * User authentication.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Login()
    {
        if ( !$_REQUEST['Login'] || !$_REQUEST['Password'] )
            return true;

        $Users = Zero_Model::Make('Zero_Users');
        $Users->DB->Sql_Where('Login', '=', $_REQUEST['Login']);
        $Users->DB->Load('*');

        //  Check
        if ( 0 == $Users->ID )
            return $this->Set_Message("Error_Registration", 1);
        else if ( $Users->Password != md5($_REQUEST['Password']) )
            return $this->Set_Message("Error_Password", 1);
        else if ( !$Users->Zero_Groups_ID )
            return $this->Set_Message("Error_Groups", 1);

        Zero_App::$Users = $Users;
        Zero_App::$Users->Factory_Set();
        return false;
    }

    /**
     * User exit.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Logout()
    {
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        Zero_App::$Users = Zero_Model::Make('Zero_Users');
    }
}