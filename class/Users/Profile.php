<?php

/**
 * Controller. User Profile.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Profile extends Zero_Controller
{
    /**
     * Initialize the stack chunks.
     *
     * @param string $action action
     */
    protected function Init_Chunks($action)
    {
        $this->Set_Chunk('Init');
        $this->Set_Chunk('Action');
        $this->Set_Chunk('View');
    }

    /**
     * Initialization of the input parameters.
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Model = Zero_Model::Make('Zero_Users', Zero_App::$Users->ID, true);
        $this->View = new Zero_View(get_class($this));
        return true;
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View($action)
    {
        $this->View->Assign('Users', $this->Model);
        return true;
    }

    /**
     * Changing a user profile.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Action_Profile()
    {
        $this->Model->VL->Validate($_REQUEST['Users']);
        if ( 0 < count($this->Model->VL->Get_Errors()) )
        {
            $this->View->Assign('Error_Validator', $this->Model->VL->Get_Errors());
            return $this->Set_Message('Error_Validate', 1);
        }
        $this->Model->DB->Update();
        Zero_App::$Users = $this->Model;
        Zero_App::$Users->Factory_Set();
        return $this->Set_Message("Profile", 0);
    }
}
