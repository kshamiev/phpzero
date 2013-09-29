<?php

/**
 * Controller. Recovery of user details.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Reminder extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('Action');
        $this->Set_Chunk('View');
        $this->Model = Zero_Model::Make('Zero_Users');
        $this->View = new Zero_View(get_class($this));
    }

    /**
     * Create views.
     *
     * @param string $action действие контроллера
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        $this->View->Assign('UsersReminder', $this->Model);
    }

    /**
     * Recovery of user details.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Action_Reminder()
    {
        $this->Model->VL->Validate($_REQUEST['Users'], 'reminder');
        if ( 0 < count($this->Model->VL->Get_Errors()) )
        {
            $this->View->Assign('Error_Validator', $this->Model->VL->Get_Errors());
            return $this->Set_Message('Error_Validate', 1, false);
        }

        $this->Model->DB->Sql_Where('Email', '=', $_REQUEST['Users']['Email']);
        $this->Model->DB->Load('ID, Name, Login');

        $password = substr(md5(uniqid(mt_rand())), 0, 10);
        $this->Model->Password = md5($password);
        $this->Model->DB->Update();

        $subject = "Reminder access details " . HTTP;
        $View = new Zero_View(get_class($this) . 'Mail');
        $View->Assign('Users', $this->Model);
        $View->Assign('password', $password);
        $message = $View->Fetch();
        Zero_Utility_Mail::Send(Zero_App::$Config->Site_Email, $this->Model->Email, $subject, $message);

        $this->Model = Zero_Model::Make('Zero_Users');

        return $this->Set_Message("Reminder", 0);
    }
}