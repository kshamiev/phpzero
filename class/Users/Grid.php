<?php

/**
 * Controller. Users list.
 *
 * To work with the catalog.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_Users';

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
     * @return boolean flag run of the next chunk
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
        return true;
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag run of the next chunk
     */
    protected function Action_CatalogMove()
    {
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_NotParam', 1);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Make($this->Source, $_REQUEST['obj_id']);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->DB->Update();
        return $this->Set_Message('Move', 0);
    }
}