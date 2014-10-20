<?php

/**
 * Controller. Content Page.
 *
 * @package Zero.Content.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_Page extends Zero_Controller
{
    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(get_class($this));
        if ( ZERO_LANG == Zero_App::$Config->Site_Language )
        {
            $this->View->Assign('Name', Zero_App::$Section->Name);
            $this->View->Assign('Content', Zero_App::$Section->Content);
            Zero_App::Set_Variable("Title", Zero_App::$Section->Title);
            Zero_App::Set_Variable("Keywords", Zero_App::$Section->Keywords);
            Zero_App::Set_Variable("Description", Zero_App::$Section->Description);
        }
        else
        {
            $index = 'Content_' . ZERO_LANG;
            if ( false === $Content = Zero_App::$Section->Cache->Get($index) )
            {
                $Content = Zero_Model::Make('Zero_Content');
                $Content->AR->Sql_Where('Lang', '=', ZERO_LANG);
                $Content->AR->Sql_Where('Zero_Section_ID', '=', Zero_App::$Section->ID);
                $Content->AR->Sql_Where_In('Block', ['content', 'Content']);
                $Content->AR->Select('*');
                Zero_Cache::Set_Link('Zero_Content', $Content->ID);
                Zero_App::$Section->Cache->Set($index, $Content);
            }
            $this->View->Assign('Name', $Content->Name);
            $this->View->Assign('Content', $Content->Content);
            Zero_App::Set_Variable("Title", $Content->Title);
            Zero_App::Set_Variable("Keywords", $Content->Keywords);
            Zero_App::Set_Variable("Description", $Content->Description);
        }
        return $this->View;
    }
}
