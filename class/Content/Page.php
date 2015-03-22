<?php
/**
 * Контент страницы.
 *
 * Контент-блоки страницы.
 * Главный контент страницы.
 *
 * {plugin "Zero_Content_Page" block="footer"}
 *
 * @package Zero.Content.Page
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
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
        {
            Zero_Logs::Set_Message_Error('Контент блок не указан');
            return '';
        }
        $index = 'Content_' . $this->Params['block'] . '_' . ZERO_LANG;
        if ( false === $Content = Zero_App::$Section->Cache->Get($index) )
        {
            $Content = Zero_Model::Make('Zero_Content');
            $Content->AR->Sql_Where('Lang', '=', ZERO_LANG);
            $Content->AR->Sql_Where('Section_ID', '=', Zero_App::$Section->ID);
            $Content->AR->Sql_Where('Block', '=', $this->Params['block']);
            $Content->AR->Select('*');
            if ( 0 == $Content->ID )
            {
                $Content = Zero_Model::Make('Zero_Content');
                $Content->AR->Sql_Where('Lang', '=', ZERO_LANG);
                $Content->AR->Sql_Where_IsNull('Section_ID');
                $Content->AR->Sql_Where('Block', '=', $this->Params['block']);
                $Content->AR->Select('*');
            }
            Zero_Cache::Set_Link('Zero_Content', $Content->ID);
            Zero_App::$Section->Cache->Set($index, $Content);
        }
        return $Content->Content;
    }
}
