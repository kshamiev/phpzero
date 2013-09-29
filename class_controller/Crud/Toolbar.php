<?php

/**
 * Controller. Formation of abstract panel controllers actions.
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_Toolbar extends Zero_Controller
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
     * Create views.
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        $this->View = new Zero_View(Zero_App::$Section->Controller . 'Toolbar');
        $this->View->Template_Add('Zero_Crud_Toolbar');
        foreach ($this->Params as $prop => $value)
        {
            $this->View->Assign($prop, $value);
        }
    }
}