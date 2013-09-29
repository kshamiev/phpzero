<?php

/**
 * Controller. Abstract plug-in filters to form via ajax
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_JsonFilter extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create filter for ajax query to json format
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        $Model = Zero_Model::Make(zero_relation($_REQUEST['source_name']));
        $Model->DB->Sql_Where_Like('Name', $_REQUEST['search']);

        $this->View = new Zero_View;
        $this->View->Assign('filter', $Model->DB->Select_List_Index('ID, Name'));
        Zero_App::$Response = 'json';
        return false;
    }
}