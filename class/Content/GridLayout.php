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
class Zero_Content_GridLayout extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_Content';

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
        if ( empty($this->Params['obj_parent_id']) || $this->Params['obj_parent_id'] != Zero_App::$Route->obj_parent_id )
            Zero_Filter::Factory($this->Model)->Reset();
        $this->Params['obj_parent_prop'] = 'Zero_Layout_ID';
        $this->Params['obj_parent_id'] = Zero_App::$Route->obj_parent_id;
        $this->Params['obj_parent_name'] = '';
        return true;
    }
}