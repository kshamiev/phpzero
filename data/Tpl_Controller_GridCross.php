<?php

/**
 * Controller. View a list of related objects by page.
 *
 * To work with the item. Relation many to many (cross).
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
        $this->Params['obj_parent_table'] = 'relation_table';
        $this->Params['obj_parent_prop'] = 'relation_prop';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
        return true;
    }

    /**
     * Making the connection (many to many) with the object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Link_Add()
    {
        if ( !$_REQUEST['id'] )
            return $this->Set_Message('Error_ParamNot', 1);
        //  target parent object
        $Object1 = Zero_Model::Makes($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        //  this object
        $Object2 = Zero_Model::Makes($this->ModelName, $_REQUEST['id']);
        //
        if ( !$Object1->AR->Insert_Cross($Object2) )
            return $this->Set_Message('Error_ParamNot', 1);
        return $this->Set_Message('Object_LinkAdd', 0);
    }

    /**
     * Unlink (many to many) with the object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Link_Rem()
    {
        if ( !$_REQUEST['id'] )
            return $this->Set_Message('Error_Link_Rem', 1);
        //  target parent object
        $Object1 = Zero_Model::Makes($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        //  this object
        $Object2 = Zero_Model::Makes($this->ModelName, $_REQUEST['id']);
        //
        if ( !$Object1->AR->Delete_Cross($Object2) )
            return $this->Set_Message('Error_Link_Rem', 1);
        return $this->Set_Message('Link_Rem', 0);
    }
}