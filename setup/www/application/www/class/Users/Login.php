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
class Www_Users_Login extends Zero_Controller
{
    /**
     * Vy`polnenie dei`stvii`
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
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
     * Recovery of user details.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Reminder()
    {
        $this->Chunk_Init();
        $this->Chunk_Reminder();
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
        $this->Model = Zero_Model::Make('Www_Users');
        $this->View = new Zero_View(get_class($this));
        if ( !isset($this->Params['url_history']) )
            $this->Params['url_history'] = ZERO_HTTPH;
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

        $Users = Zero_Model::Make('Www_Users');
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
        $url_history = $this->Params['url_history'];
        unset($this->Params['url_history']);
        Zero_App::ResponseRedirect($url_history);
        return false;
    }

    /**
     * Recovery of user details.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Reminder()
    {
        $this->Model->VL->Validate($_REQUEST['Users'], 'reminder');
        if ( 0 < count($this->Model->VL->Get_Errors()) )
        {
            $this->View->Assign('Error_Validator', $this->Model->VL->Get_Errors());
            return $this->Set_Message('Error_Validate', 1, false);
        }

        $this->Model->DB->Sql_Where('Email', '=', $_REQUEST['Users']['Email']);
        $this->Model->DB->Select('ID, Name, Login');

        $password = substr(md5(uniqid(mt_rand())), 0, 10);
        $this->Model->Password = md5($password);
        $this->Model->DB->Update();

        $subject = "Reminder access details " . HTTP;
        $View = new Zero_View(get_class($this) . 'ReminderMail');
        $View->Assign('Users', $this->Model);
        $View->Assign('password', $password);
        $message = $View->Fetch();
        Zero_Lib_Mail::Send(Zero_App::$Config->Site_Email, $this->Model->Email, $subject, $message);

        $this->Model = Zero_Model::Make('Zero_Users');

        return $this->Set_Message("Reminder", 0);
    }
}