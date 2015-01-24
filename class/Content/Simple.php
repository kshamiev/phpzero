<?php

/**
 * Контент страницы. Простой контроллер.
 *
 * Контент-блоки страницы.
 * Главный контент страницы.
 *
 * {plugin "Zero_Content_Simple" block="footer"}
 *
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
