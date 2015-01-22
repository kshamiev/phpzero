<?php

/**
 * Controller. View a list of cataloged objects by page.
 *
 * To work with the catalog.
 *
 * @package <Package>.<Subpackage>.Controller
 * @author
 * @version $Id$
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
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Initialization of the input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'TableName_ID';
        $this->Params['obj_parent_name'] = '';
        if ( !isset($this->Params['obj_parent_prop']) )
            $this->Params['obj_parent_path'] = ['root'];
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
                $ObjectGo = Zero_Model::Make($this->ModelName, $_GET['pid']);
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
            return $this->Set_Message('Error_NotParam', 1, false);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Make($this->Source, $_REQUEST['obj_id']);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->AR->Update();
        return $this->Set_Message('Move', 0);
    }
}