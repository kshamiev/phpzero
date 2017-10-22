<?php

/**
 * Controller. Management of access rights.
 *
 * @package Zero.Section
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017-10-08
 */
class Zero_Section_Access extends Zero_Controller
{
    /**
     * Модель
     *
     * @var Zero_Section
     */
    protected $Model = null;

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Preservation of the rights of access
     *
     * @return Zero_View
     */
    public function Action_Save()
    {
        $this->Chunk_Init();

        if ( empty($_REQUEST['access']) )
            $_REQUEST['access'] = [];

        Zero_DB::Update("DELETE FROM Action WHERE Section_ID = {$this->Params['obj_parent_id']}");
        foreach ($_REQUEST['access'] as $grId => $method)
        {
            foreach ($method as $m)
            {
                $sql = "INSERT INTO Action SET Groups_ID = {$grId}, Section_ID = {$this->Params['obj_parent_id']}, Action = '{$m}'";
                Zero_DB::Insert($sql);
            }
        }

        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean статус выполнения чанка
     */
    protected function Chunk_Init()
    {
        if ( isset($_REQUEST['pid']) )
            $this->Params['obj_parent_id'] = $_REQUEST['pid'];
        else if ( empty($this->Params['obj_parent_id']) )
            $this->Params['obj_parent_id'] = 0;
        $this->View = new Zero_View(get_class($this));
        $this->Model = Zero_Section::Make($this->Params['obj_parent_id'], true);
        return true;
    }

    /**
     * Create views.
     *
     * @return boolean статус выполнения чанка
     * @throws Exception
     */
    protected function Chunk_View()
    {
        $this->View->Assign('Section', $this->Model);

        $sql = "SELECT ID, `Name` FROM Groups WHERE ID != 1 ORDER BY `Name`";
        $this->View->Assign('groups', Zero_DB::Select_List_Index($sql));

        $access = [];
        $sql = "SELECT Groups_ID, Action FROM Action  WHERE Section_ID = {$this->Params['obj_parent_id']}";
        $result = Zero_DB::Select_Array($sql);
        foreach ($result as $row)
        {
            $access[$row['Groups_ID']][$row['Action']] = 1;
        }
        $this->View->Assign('access', $access);
        return true;
    }
}