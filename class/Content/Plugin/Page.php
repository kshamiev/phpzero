<?php
/**
 * Контент-блоки страницы.
 *
 * {plugin "Zero_Content_Plugin_Page" block="footer"}
 *
 * @package Zero.Content.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Content_Plugin_Page extends Zero_Controller
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
            $Content = Zero_Content::Make();
            $Content->Load_Page($this->Params['block'], Zero_App::$Section->ID);
            if ( 0 == $Content->ID )
            {
                $Content->Load_Page($this->Params['block']);
            }
            Zero_Cache::Set_Link('Content', $Content->ID);
            Zero_App::$Section->Cache->Set($index, $Content);
        }
        return $Content->Content;
    }
}
