<?php
/**
 * Content Page.
 *
 * @package Zero.Section.Page
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
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
        }
        else
        {
            $index = 'Content_' . ZERO_LANG;
            if ( false === $Content = Zero_App::$Section->Cache->Get($index) )
            {
                $Content = Zero_Model::Makes('Zero_Content');
                $Content->AR->Sql_Where('Lang', '=', ZERO_LANG);
                $Content->AR->Sql_Where('Section_ID', '=', Zero_App::$Section->ID);
                $Content->AR->Sql_Where_In('Block', ['content', 'Content']);
                $Content->AR->Select('*');
                Zero_Cache::Set_Link('Zero_Content', $Content->ID);
                Zero_App::$Section->Cache->Set($index, $Content);
            }
            $this->View->Assign('Name', $Content->Name);
            $this->View->Assign('Content', $Content->Content);
            Zero_App::$Section->Title = $Content->Title;
            Zero_App::$Section->Keywords = $Content->Keywords;
            Zero_App::$Section->Description = $Content->Description;
        }
        return $this->View;
    }
}
