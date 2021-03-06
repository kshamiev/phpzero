<?php

/**
 * View a list of cataloged objects by page.
 *
 * To work with the catalog.
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Model_Pattern';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Grid';

    /**
     * Initialization of the input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        parent::Chunk_Init();
        if ( !isset($this->Params['obj_parent_prop']) )
        {
            $this->Params['obj_parent_prop'] = 'TableName_ID';
            $this->Params['obj_parent_name'] = 'Название';
            $this->Params['obj_parent_path'] = ['root'];
            $this->Params['obj_parent_id'] = 0;
        }
        if ( isset($_REQUEST['pid']) )
        {
            $this->Params['obj_parent_id'] = $_REQUEST['pid'];
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
                $this->Params['obj_parent_path'][$_REQUEST['pid']] = $ObjectGo->NameMenu;
                unset($ObjectGo);
            }
            Zero_Filter::Factory($this->Model)->Reset();
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