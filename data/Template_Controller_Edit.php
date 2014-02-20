<?php

/**
 * Controller. <Comment>
 *
 * @package <Package>.<Subpackage>.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Controller_Edit extends Zero_Crud_Edit
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
    protected $Template = 'Zero_Crud_Edit';

    /**
     * Initialization of the input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        //  relation transition one to many (CL)
        $this->Params['obj_parent_prop'] = 'relation_prop';
        $this->Params['obj_parent_name'] = '';
        //  relation transition many to many (CCL)
        $this->Params['obj_parent_table'] = 'relation_table';
        $this->Params['obj_parent_name'] = '';
        //
        parent::Chunk_Init();
        return true;
    }
}
