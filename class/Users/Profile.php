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
     * Vy`polnenie dei`stvii`
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Action_Profile()
    {
        $this->Chunk_Init();
        $this->Chunk_Profile();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Changing a user profile.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Profile()
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

    /**
     * Create views.
     *
     * @return bool
     */
    protected function Chunk_Init()
    {
        $this->Model = Zero_Model::Make('Www_Users', Zero_App::$Users->ID, true);
        $this->View = new Zero_View(get_class($this));
        return true;
    }

    /**
     * Create views.
     *
     * @return Zero_View or string
     */
    protected function Chunk_View()
    {
        $this->View->Assign('Users', $this->Model);
        return true;
    }
}
