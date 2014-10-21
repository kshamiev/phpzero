<?php

/**
 * Controller. Development and maintenance of the system.
 *
 * @package Zero.System.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_System_GridService extends Zero_Controller
{
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->View = new Zero_View(__CLASS__);
    }

    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        $Interface_List = Zero_App::$Section->Get_Navigation_Child();
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('Interface', $Interface_List);
        $this->View->Assign('Params', $this->Params);
        $this->View->Assign('modules_db', Zero_Engine::Get_Modules_DB());
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
    }

    public function Action_EngineModulesDB()
    {
        $this->Chunk_Init();
        $this->Chunk_EngineModulesDB();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Engineering models.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_EngineModulesDB()
    {
        $_REQUEST['paket'] = trim($_REQUEST['paket']);
        if ( !$_REQUEST['paket'] )
            return $this->Set_Message('Error_NotParam', 1, false);
        $_REQUEST['flag_gird'] = isset($_REQUEST['flag_gird']) ? true : false;
        $_REQUEST['flag_edit'] = isset($_REQUEST['flag_edit']) ? true : false;
        $Controller_Factory = new Zero_Engine;
        if ( $Controller_Factory->Factory_Modules_DB($_REQUEST['paket'], $_REQUEST['flag_gird'], $_REQUEST['flag_edit']) )
            return $this->Set_Message("Engine_Modules_DB", 0);
        else
            return $this->Set_Message("Error_Engine_Modules_DB", 1, false);
    }

    public function Action_CacheReset()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        $this->Chunk_CacheReset();
        return $this->View;
    }

    /**
     * Full reset cache
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_CacheReset()
    {
        Zero_Cache::Reset_All();
        return $this->Set_Message("Cache_Reset", 0);
    }

    public function Action_SessionReset()
    {
        $this->Chunk_Init();
        $this->Chunk_SessionReset();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Full reset session
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_SessionReset()
    {
        Zero_Lib_FileSystem::File_Remove(ZERO_PATH_SESSION);
        return $this->Set_Message("Session_Reset", 0);
    }
}
