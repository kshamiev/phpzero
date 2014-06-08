<?php

/**
 * Controller. Content Page.
 *
 * {plugin "Www_Content_Page" view="" block="content"}
 *
 * @package Zero.Content.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Content_Page extends Zero_Controller
{
    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        if ( empty($this->Params['block']) )
            $this->Params['block'] = 'content';
        $index = 'Content_' . $this->Params['block'] . LANG_ID;
        pre($index);
        if ( false === $Content = Zero_App::$Section->Cache->Get($index) )
        {
            $Content = Zero_Model::Make('Zero_Content');
            $Content->DB->Sql_Where('Zero_Language_ID', '=', LANG_ID);
            $Content->DB->Sql_Where('Zero_Section_ID', '=', Zero_App::$Section->ID);
            $Content->DB->Sql_Where('Block', '=', $this->Params['block']);
            $Content->DB->Select('*');
            if ( 0 == $Content->ID )
            {
                $Content = Zero_Model::Make('Zero_Content');
                $Content->DB->Sql_Where('Zero_Language_ID', '=', LANG_ID);
                $Content->DB->Sql_Where('Layout', '=', Zero_App::$Section->Layout);
                $Content->DB->Sql_Where('Block', '=', $this->Params['block']);
                $Content->DB->Select('*');
            }
            Zero_Cache::Set_Link('Zero_Content', $Content->ID);
            Zero_App::$Section->Cache->Set($index, $Content);
        }
        if ( 'content' == $this->Params['block'] )
        {
            if ( isset($this->Params['view']) )
                $this->View = new Zero_View($this->Params['view']);
            else
                $this->View = new Zero_View(get_class($this));
            $this->View->Assign('Name', $Content->Name);
            $this->View->Assign('Content', $Content->Content);
        }
        else
        {
            $this->View = $Content->Content;
        }
        return $this->View;
    }
}
