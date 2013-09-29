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
    protected $Source = 'Zero_Section';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Take into account the conditions user
     *
     * @var boolean
     */
    protected $User_Condition = true;

    /**
     * Initialization of the input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        parent::Chunk_Init($action);
        //  Initializing the top level catalog
        if ( !isset($this->Params['obj_parent_prop']) )
        {
            $this->Params['obj_parent_prop'] = $this->Source . '_ID';
            $this->Params['obj_parent_id'] = 0;
            $this->Params['obj_parent_name'] = '';
            $this->Params['obj_parent_path'] = ['root'];
        }
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Action_CatalogMove()
    {
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_NotParam', 1, false);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Make($this->Source, $_REQUEST['obj_id']);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->DB->Update();
        return $this->Set_Message('Move', 0);
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
    protected function Action_Update_Url($section_id = null)
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