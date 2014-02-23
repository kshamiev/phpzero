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
     * Vy`polnenie dei`stvii`
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        if ( Zero_App::$Users->Zero_Groups_ID != 2 )
            Zero_App::ResponseRedirect('/user/profile');
        // Инициализация чанков
        $this->Chunk_Init();
        $this->View->Assign('Users', Zero_App::$Users);
        return $this->View;
    }

    /**
     * User authentication.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Login()
    {
        $this->Chunk_Init();
        $this->Chunk_Login();
        $this->View->Assign('Users', Zero_App::$Users);
        return $this->View;
    }

    /**
     * User exit.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Logout()
    {
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        Zero_App::ResponseRedirect(ZERO_HTTP);
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->Model = Zero_Model::Make('Admin_Users');
        $this->View = new Zero_View(get_class($this));
    }

    /**
     * User authentication.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Login()
    {
        // Инициализация чанков
        if ( !$_REQUEST['Login'] || !$_REQUEST['Password'] )
            return true;

        $Users = Zero_Model::Make('Admin_Users');
        $Users->DB->Sql_Where('Login', '=', $_REQUEST['Login']);
        $Users->DB->Select('*');

        //  Check
        if ( 0 == $Users->ID )
            return $this->Set_Message("Error_Registration", 1);
        else if ( $Users->Password != md5($_REQUEST['Password']) )
            return $this->Set_Message("Error_Password", 1);
        else if ( !$Users->Zero_Groups_ID )
            return $this->Set_Message("Error_Groups", 1);

        Zero_App::$Users = $Users;
        Zero_App::$Users->Factory_Set();
        Zero_App::ResponseRedirect('/profile');
        return false;
    }
}