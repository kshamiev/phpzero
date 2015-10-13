<?php
/**
 * Controller. Development and maintenance of the system.
 *
 * @package Zero.Developer
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_GridService extends Zero_Controller
{
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View->Fetch($this->ViewTplOutString);
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
        $this->View->Assign('modules_db', array_keys(Zero_App::$Config->Db));
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
    }

    public function Action_EngineModulesDB()
    {
        $this->Chunk_Init();
        $this->Chunk_EngineModulesDB();
        $this->Chunk_View();
        return $this->View->Fetch($this->ViewTplOutString);
    }

    /**
     * Engineering models.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_EngineModulesDB()
    {
        $_REQUEST['connectDb'] = trim($_REQUEST['connectDb']);
        if ( !$_REQUEST['connectDb'] )
            return $this->SetMessage(5301);
        $_REQUEST['flag_gird'] = isset($_REQUEST['flag_gird']) ? true : false;
        $_REQUEST['flag_edit'] = isset($_REQUEST['flag_edit']) ? true : false;
        $Controller_Factory = new Zero_Engine;
        if ( $Controller_Factory->Factory_Modules_DB($_REQUEST['connectDb'], $_REQUEST['flag_gird'], $_REQUEST['flag_edit']) )
            return $this->SetMessage(-1, "Engine_Modules_DB");
        else
            return $this->SetMessage(-1, "Error_Engine_Modules_DB");
    }

    public function Action_CacheReset()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        $this->Chunk_CacheReset();
        return $this->View->Fetch($this->ViewTplOutString);
    }

    /**
     * Full reset cache
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_CacheReset()
    {
        Zero_Cache::Reset_All();
        return $this->SetMessage(2302);
    }

    public function Action_SessionReset()
    {
        $this->Chunk_Init();
        $this->Chunk_SessionReset();
        $this->Chunk_View();
        return $this->View->Fetch($this->ViewTplOutString);
    }

    /**
     * Full reset session
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_SessionReset()
    {
        Zero_Helper_File::File_Remove(ini_get('session.save_path'));
        return $this->SetMessage(2301);
    }
}
