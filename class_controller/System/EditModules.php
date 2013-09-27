<?php

/**
 * Controller. Modules
 *
 * @package Zero.System.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_System_EditModules extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('Action');
        $this->Set_Chunk('View');
        $this->View = new Zero_View(__CLASS__);
        if ( isset($_REQUEST['obj_id']) )
            $this->Params['module'] = $_REQUEST['obj_id'];
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
        $module_config = Zero_Utility_FileSystem::Get_Config($this->Params['module']);
        $this->View->Assign('module_config', $module_config);
        $this->View->Assign('Section', Zero_App::$Section);
        return true;
    }
}
