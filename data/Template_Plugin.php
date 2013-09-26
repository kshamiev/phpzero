<?php

/**
 * Plugin. <Comment>
 *
 * Sample: {plugin "Zero_Section_NavigationChild"  param="value" ...}
 *
 * @package <Package>.<Subpackage>.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Plugin_Sample extends Zero_Plugin
{
    /**
     * Initialize the stack chunks
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('Init');
        $this->Set_Chunk('View');
    }

    /**
     * Initialization of the input parameters
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->View = new Zero_View(__CLASS__);
        return true;
    }

    /**
     * Create views.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $this->View->Assign('variable', 'value');
        return true;
    }
}
