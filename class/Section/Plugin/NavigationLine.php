<?php
/**
 * Formation of bread crumbs.
 *
 * Sample: {plugin "Zero_Section_Plugin_NavigationLine" view=""}
 *
 * @package Zero.Section.Navigation
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_Plugin_NavigationLine extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $index = __CLASS__ . '_' . Zero_App::$Section->ID;
        if ( false === $navigation = Zero_App::$Section->Cache->Get($index) )
        {
            $navigation[] = [
                'Url' => URL,
                'Title' => Zero_App::$Section->Title,
                'Name' => Zero_App::$Section->Name
            ];
            $id = Zero_App::$Section->Section_ID;
            while ( 0 < $id )
            {
                $Zero_Section = Zero_Model::Makes('Zero_Section', $id);
                $Zero_Section->Load("Name, Title, SUBSTRING(Url, POSITION('/' IN Url)) as Url, Section_ID");
                $id = $Zero_Section->Section_ID;
                $navigation[] = ['Url' => $Zero_Section->Url, 'Name' => $Zero_Section->Name, 'Title' => $Zero_Section->Title];
            }
            $navigation = array_reverse($navigation);
            Zero_App::$Section->Cache->Set($index, $navigation);
        }
        //  шаблон
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('navigation', $navigation);
        return $this->View;
    }
}