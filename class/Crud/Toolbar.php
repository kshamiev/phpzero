<?php

/**
 * Plugin. Formation of abstract panel controllers actions.
 *
 * @package Zero.Crud.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_Toolbar extends Zero_Plugin
{
    /**
     * Initialize the stack chunks
     *
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $this->View = new Zero_View(Zero_App::$Section->Controller . 'Toolbar');
        $this->View->Template_Add('Zero_Crud_Toolbar');
        foreach ($this->Params as $prop => $value)
        {
            $this->View->Assign($prop, $value);
        }
        return true;
    }
}