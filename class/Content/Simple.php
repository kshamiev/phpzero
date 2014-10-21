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
