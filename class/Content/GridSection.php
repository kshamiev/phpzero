<?php

/**
 * Controller. View a list of related objects by page.
 *
 * @package Zero.Content.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Content_GridSection extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Content';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Initialization of the input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Zero_Section_ID';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
        return true;
    }
}