<?php

/**
 * Plugin. Formation of bread crumbs.
 *
 * Sample: {plugin "Zero_Section_NavigationLine" template=""}
 *
 * @package Zero.Section.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_NavigationLine extends Zero_Plugin
{
    /**
     * Initialize the stack chunks
     *
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $key = 0;
        $str = '';
        $navigation = [];
        foreach (explode('/', ltrim(Zero_App::$Route->url, '/')) as $url)
        {
            if ( isset(Zero_App::$Config->Language[$url]) )
                continue;
            $key++;
            $str .= '/' . $url;
            $navigation[$key] = ['url' => $str, 'name' => $url];
        }

        if ( 0 < Zero_App::$Route->obj_parent_id )
        {
            $navigation[$key]['url'] .= '-pid-' . Zero_App::$Route->obj_parent_id;
            $navigation[$key]['name'] .= '-pid-' . Zero_App::$Route->obj_parent_id;
        }
        if ( 0 < Zero_App::$Route->obj_id )
        {
            $navigation[$key]['url'] .= '-id-' . Zero_App::$Route->obj_id;
            $navigation[$key]['name'] .= '-id-' . Zero_App::$Route->obj_id;
        }
        if ( 0 < Zero_App::$Route->page_id )
        {
            $navigation[$key]['url'] .= '-pg-' . Zero_App::$Route->page_id;
            $navigation[$key]['name'] .= '-pg-' . Zero_App::$Route->page_id;
        }
        //  шаблон
        if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $navigation);
        $this->View->Assign('action_message', Zero_App::Get_Variable('action_message'));
        return true;
    }
}