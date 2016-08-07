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
class Zero_Controller_Grid extends Zero_Web_Crud_Grid
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
    protected $ViewName = 'Zero_Web_Crud_Grid';

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
            return $this->SetMessage(5301);
        //  target parent object
        $Object1 = Zero_Model::Makes($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        if ( method_exists($Object1, $method = 'DB_Cross_' . $this->Params['obj_parent_table']) )
        {
            if ( false === $Object1->$method($_REQUEST['id']) )
                return $this->SetMessage(5301);
        }
        return $this->SetMessage();
    }

    /**
     * Unlink (many to many) with the object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Link_Rem()
    {
        if ( !$_REQUEST['id'] )
            return $this->SetMessage(5302);
        //  target parent object
        $Object1 = Zero_Model::Makes($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
        if ( method_exists($Object1, $method = 'DB_Cross_' . $this->Params['obj_parent_table']) )
        {
            if ( false === $Object1->$method($_REQUEST['id'], false) )
                return $this->SetMessage(5302);
        }
        return $this->SetMessage();
    }
}