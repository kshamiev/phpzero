<?php
/**
 * Контент страницы. Простой контроллер.
 *
 * Контент-блоки страницы.
 * Главный контент страницы.
 *
 * {plugin "Zero_Content_Simple" block="footer"}
 *
 * @package Zero.Content.Page
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Content_Simple extends Zero_Controller
{
    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $view = new Zero_View(__CLASS__);
        $view->Assign('Name', 'Заголовок');
        $view->Assign('Content', 'Контент');
        return $view;
    }
}
