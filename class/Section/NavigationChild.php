<?php

/**
 * Plugin. Navigating the subsections of the current section.
 *
 * Sample: {plugin "Zero_Section_NavigationChild" template=""}
 *
 * @package Zero.Section.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationChild extends Zero_Plugin
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
     * Navigating the subsections of the current section.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $navigation = Zero_App::$Section->Get_Navigation_Child();
        if ( 0 == count($navigation) )
        {
            $Section = Zero_Model::Make('Zero_Section', Zero_App::$Section->Zero_Section_ID);
            /* @var $Section Zero_Section */
            $navigation = $Section->Get_Navigation_Child();
        }
        if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $navigation);
        return true;
    }
}