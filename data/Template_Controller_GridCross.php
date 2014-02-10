<?php

/**
 * Controller. View a list of related objects by page.
 *
 * To work with the item. Relation many to many (cross).
 *
 * @package <Package>.<Subpackage>.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Controller_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_Model_Pattern';

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
        $this->Params['obj_parent_table'] = 'relation_table';
        $this->Params['obj_parent_id'] = Zero_App::$Route->Param['pid'];
        $this->Params['obj_parent_name'] = '';
        return true;
    }

    /**
     * Making the connection (many to many) with the object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Link_Add()
    {
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_ParamNot', 1, false);
        //  target parent object
        $Object1 = Zero_Model::Make($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        //  this object
        $Object2 = Zero_Model::Make($this->Source, $_REQUEST['obj_id']);
        //
        if ( !$Object1->DB->Insert_Cross($Object2) )
            return $this->Set_Message('Error_ParamNot', 1, false);
        return $this->Set_Message('Object_LinkAdd', 0);
    }

    /**
     * Unlink (many to many) with the object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Link_Rem()
    {
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_Link_Rem', 1, false);
        //  target parent object
        $Object1 = Zero_Model::Make($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        //  this object
        $Object2 = Zero_Model::Make($this->Source, $_REQUEST['obj_id']);
        //
        if ( !$Object1->DB->Delete_Cross($Object2) )
            return $this->Set_Message('Error_Link_Rem', 1, false);
        return $this->Set_Message('Link_Rem', 0);
    }
}