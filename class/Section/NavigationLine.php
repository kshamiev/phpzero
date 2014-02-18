<?php

/**
 * Controller. Formation of bread crumbs.
 *
 * Sample: {plugin "Zero_Section_NavigationLine" view=""}
 *
 * @package Zero.Section.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationLine extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $url = '';
        if ( 0 < count(Zero_App::$Route->Param) )
            foreach (Zero_App::$Route->Param as $k => $v)
            {
                $url .= '-' . $k . ':' . $v;
            }
        $navigation[] = [
            'Url' => URL . $url,
            'Name' => Zero_App::$Section->Name
        ];
        $id = Zero_App::$Section->Zero_Section_ID;
        while ( 0 < $id )
        {
            $Zero_Section = Zero_Model::Make('Zero_Section', $id);
            $Zero_Section->DB->Select("Name, SUBSTRING(Url, POSITION('/' IN Url)) as Url, Zero_Section_ID");
            $id = $Zero_Section->Zero_Section_ID;
            $navigation[] = ['Url' => $Zero_Section->Url, 'Name' => $Zero_Section->Name];
        }
        $navigation = array_reverse($navigation);
        //  шаблон
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('navigation', $navigation);
        return $this->View;
    }
}