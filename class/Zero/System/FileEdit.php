<?php
/**
 * Controller. Editing a text file.
 *
 * @package Zero.Controller.System
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_FileEdit extends Zero_Controller
{
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View->Fetch();
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->View = new Zero_View(__CLASS__);
        if ( isset($_REQUEST['path']) )
            $this->Params['obj_parent_path'] = $_REQUEST['path'];
        if ( isset($_REQUEST['file_name']) )
            $this->Params['file_name'] = $_REQUEST['file_name'];
    }

    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        //  в шаблон
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('path', $this->Params['obj_parent_path'] . '/' . $this->Params['file_name']);
        $this->View->Assign('name', $this->Params['file_name']);
        $this->View->Assign('data', file_get_contents($this->Params['obj_parent_path'] . '/' . $this->Params['file_name']));
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
    }

    public function Action_FileSave()
    {
        $this->Chunk_Init();
        $this->Chunk_FileSave();
        $this->Chunk_View();
        return $this->View->Fetch();
    }

    /**
     * Save a text file.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Chunk_FileSave()
    {
        $_REQUEST['Prop']['Content'] = str_replace("\r\n", "\n", $_REQUEST['Prop']['Content']);
        file_put_contents($this->Params['obj_parent_path'] . '/' . $this->Params['file_name'], $_REQUEST['Prop']['Content']);
        $this->SetMessage(0, ['FileSave']);
        return true;
    }
}