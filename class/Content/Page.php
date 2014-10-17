<?php

/**
 * Controller. Content Page.
 *
 * {plugin "Zero_Content_Page" block="footer"}
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
        $index = 'Content_' . $this->Params['block'] . '_' . LANG_ID;
        if ( false === $Content = Zero_App::$Section->Cache->Get($index) )
        {
            $Content = Zero_Model::Make('Zero_Content');
            $Content->AR->Sql_Where('Zero_Language_ID', '=', LANG_ID);
            $Content->AR->Sql_Where('Zero_Section_ID', '=', Zero_App::$Section->ID);
            $Content->AR->Sql_Where('Block', '=', $this->Params['block']);
            $Content->AR->Select('*');
            if ( 0 == $Content->ID )
            {
                $Content = Zero_Model::Make('Zero_Content');
                $Content->AR->Sql_Where('Zero_Language_ID', '=', LANG_ID);
                $Content->AR->Sql_Where_IsNull('Zero_Section_ID');
                $Content->AR->Sql_Where('Block', '=', $this->Params['block']);
                $Content->AR->Select('*');
            }
            Zero_Cache::Set_Link('Zero_Content', $Content->ID);
            Zero_App::$Section->Cache->Set($index, $Content);
        }
//        $this->View = $Content->Content;
        return $Content->Content;
    }
}
