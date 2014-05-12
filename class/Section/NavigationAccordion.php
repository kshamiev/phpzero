<?php

/**
 * Controller. A two-level navigation through the main sections of the site.
 *
 * - 2 и 3 уровень.
 * Sample: {plugin "Zero_Section_NavigationAccordion" view="" section_id="0"}
 *
 * @package Zero.Section.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationAccordion extends Zero_Controller
{
    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    public function Action_Default()
    {
        $index = __CLASS__ . Zero_App::$Users->Zero_Groups_ID . Zero_App::$Config->Site_DomainSub;
        $Section = Zero_Model::Make('Www_Section');
        /* @var $Section Zero_Section */
        if ( isset($this->Params['section_id']) && 0 < $this->Params['section_id'] )
        {
            $Section = Zero_Model::Make('Www_Section', $this->Params['section_id']);
            $index .= $this->Params['section_id'];
        }
        else
            $Section->Init_Url(Zero_App::$Config->Site_DomainSub . '/');

        if ( false === $navigation = $Section->Cache->Get($index) )
        {
            $navigation = Zero_Section::DB_Navigation_Child($Section->ID);
            foreach (array_keys($navigation) as $id)
            {
                $navigation[$id]['child'] = Zero_Section::DB_Navigation_Child($id);
            }
            $Section->Cache->Set($index, $navigation);
        }
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $navigation);
        return $this->View;
    }
}