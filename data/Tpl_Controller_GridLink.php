<?php

/**
 * View a list of related objects by page.
 *
 * To work with the item. Relation one to many.
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Grid extends Zero_Crud_Grid
{
    /**
     * The compile tpl in string and out
     *
     * @var bool
     */
    protected $ViewTplOutString = false;

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
        $this->Params['obj_parent_prop'] = 'relation_prop';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
        return true;
    }
}