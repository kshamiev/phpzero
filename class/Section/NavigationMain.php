<?php

/**
 * Plugin. Navigating the root sections of the site.
 *
 * - two level
 * Sample: {plugin "Zero_Section_NavigationMain" template=""}
 *
 * @package Zero.Section.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationMain extends Zero_Plugin
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
     * Формирование двух уровневой навигации по главным разделам сайта.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $Section = Zero_Model::Make('Zero_Section');
        /* @var $Section Zero_Section */
        $Section->Init_Url(Zero_App::$Config->Host);
        //  шаблон
        if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $Section->Get_Navigation_Child());
        return true;
    }
}