<?php

/**
 * Controller. Users list.
 *
 * To work with the catalog.
 *
 * @package Zero.Users.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Www_Users';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Users_ID';
        $this->Params['obj_parent_name'] = '';
        if ( !isset($this->Params['obj_parent_path']) )
        {
            $this->Params['obj_parent_path'] = [Zero_App::$Users->ID => 'root'];
            $this->Params['obj_parent_id'] = Zero_App::$Users->ID;
        }
        if ( isset($_GET['pid']) && $this->Params['obj_parent_id'] != $_GET['pid'] )
        {
            $this->Params['obj_parent_id'] = $_GET['pid'];
            //  move up
            if ( isset($this->Params['obj_parent_path'][$_GET['pid']]) )
            {
                $flag = true;
                foreach ($this->Params['obj_parent_path'] as $id => $name)
                {
                    if ( $id == $_GET['pid'] )
                        $flag = false;
                    else if ( false == $flag )
                        unset($this->Params['obj_parent_path'][$id]);
                }
            }
            //  move down
            else
            {
                $ObjectGo = Zero_Model::Makes($this->ModelName, $_GET['pid']);
                $ObjectGo->AR->Select('Name');
                $this->Params['obj_parent_path'][$_GET['pid']] = $ObjectGo->Name;
                unset($ObjectGo);
            }
            Zero_Filter::Factory($this->Model)->Reset();
        }
        parent::Chunk_Init();
        return true;
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_CatalogMove()
    {
        $this->Chunk_Init();
        $this->Chunk_CatalogMove();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_CatalogMove()
    {
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_NotParam', 1);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Makes($this->ModelName, $_REQUEST['obj_id']);
        /* @var $Object Zero_Users */
        if ( 0 == count($Object->Get_AR()->Select('ID')) )
            return $this->Set_Message('Error_NotFound', 1);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->AR->Update();
        return $this->Set_Message('Move', 0);
    }
}