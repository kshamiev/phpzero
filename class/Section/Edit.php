<?php

/**
 * Controller. Section edit.
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
class Zero_Section_Edit extends Zero_Crud_Edit
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
    protected $Template = 'Zero_Crud_Edit';

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
        $this->Params['obj_parent_prop'] = 'Zero_Section_ID';
        $this->Params['obj_parent_id'] = Zero_App::$Route->obj_parent_id;
        $this->Params['obj_parent_name'] = '';
        //
        return true;
    }

    /**
     * Working with the administrative section of the module
     *
     * @return boolean flag run of the next chunk
     */
    protected function Action_Modules()
    {
        return true;
    }
}