<?php

/**
 * Controller. Navigating the root sections of the site.
 *
 * - two level
 * Sample: {plugin "Zero_Section_NavigationMain" view=""}
 *
 * @package Zero.Section.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationMain extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $Section = Zero_Model::Make('Www_Section');
        /* @var $Section Zero_Section */
        $Section->Init_Url(Zero_App::$Config->Site_DomainSub . '/');
        //  шаблон
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $Section->Get_Navigation_Child());
        return $this->View;
    }
}