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
    protected $ModelName = 'Zero_Users';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Grid';

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    protected function Chunk_Init()
    {
        parent::Chunk_Init();
        //
        $this->Params['obj_parent_prop'] = 'Users_ID';
        $this->Params['obj_parent_name'] = '';
        if ( !isset($this->Params['obj_parent_path']) )
        {
            $this->Params['obj_parent_path'] = [Zero_App::$Users->ID => 'root'];
            $this->Params['obj_parent_id'] = Zero_App::$Users->ID;
        }
        if ( isset($_REQUEST['pid']) )
        {
            //  move up
            if ( isset($this->Params['obj_parent_path'][$_REQUEST['pid']]) )
            {
                $flag = true;
                foreach ($this->Params['obj_parent_path'] as $id => $name)
                {
                    if ( $id == $_REQUEST['pid'] )
                        $flag = false;
                    else if ( false == $flag )
                        unset($this->Params['obj_parent_path'][$id]);
                }
            }
            //  move down
            else
            {
                $ObjectGo = Zero_Model::Makes($this->ModelName, $_REQUEST['pid']);
                $ObjectGo->Load('Name');
                $this->Params['obj_parent_path'][$_REQUEST['pid']] = $ObjectGo->Name;
                unset($ObjectGo);
            }
        }
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
        if ( !$_REQUEST['id'] )
            return $this->SetMessage(5303);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Makes($this->ModelName, $_REQUEST['id']);
        if ( 0 == count($Object->Load('ID')) )
            return $this->SetMessage(5303);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->Save();
        return $this->SetMessage();
    }
}