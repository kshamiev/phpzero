<?php

/**
 * Plugin. Informer state of the user.
 *
 * Sample: {plugin "Www_Users_Informer"}
 *
 * @package Zero.Users.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Informer extends Zero_Plugin
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
        $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Users', Zero_App::$Users);
        return true;
    }
}