<?php

/**
 * Controller. Section list.
 *
 * To work with the catalog.
 *
 * @package Zero.Section.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Www_Section';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Initialization filters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        if ( !isset($this->Params['obj_parent_prop']) )
        {
            $this->Params['obj_parent_prop'] = 'Zero_Section_ID';
            $this->Params['obj_parent_id'] = 0;
            $this->Params['obj_parent_name'] = '';
            $this->Params['obj_parent_path'] = ['root'];
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
        $Object = Zero_Model::Make($this->ModelName, $_REQUEST['obj_id']);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->AR->Update();
        return $this->Set_Message('Move', 0);
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_UpdateUrl()
    {
        $this->Chunk_Init();
        $this->Chunk_UpdateUrl();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Correcting an absolute reference.
     *
     * Correcting an absolute reference catalog and all its subdirectories (usually when moving).
     * - After changing the links, move a catalog, the new installation
     *
     * @param integer $section_id ID of the parent directory
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_UpdateUrl($section_id = null)
    {
        if ( !$section_id )
        {
            if ( !$this->Params['obj_parent_id'] )
                return $this->Set_Message('Error_Update_Url', 1, false);
            $section_id = $this->Params['obj_parent_id'];
        }
        if ( true == Zero_Section::DB_Update_Url($section_id) )
            return $this->Set_Message('Update_Url', 0);
        else
            return $this->Set_Message('Error_Update_Url', 1, false);
    }
}