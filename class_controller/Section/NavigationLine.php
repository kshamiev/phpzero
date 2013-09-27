<?php

/**
 * Controller. Formation of bread crumbs.
 *
 * Sample: {plugin "Zero_Section_NavigationLine" template=""}
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
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View($action)
    {
        $key = 0;
        $str = '';
        $navigation = [];
        foreach (explode('/', ltrim(Zero_App::$Route->Url, '/')) as $url)
        {
            if ( isset(Zero_App::$Config->Language[$url]) )
                continue;
            $key++;
            $str .= '/' . $url;
            $navigation[$key] = ['url' => $str, 'name' => $url];
        }

        if ( 0 < Zero_App::$Route->Param['pid'] )
        {
            $navigation[$key]['url'] .= '-pid-' . Zero_App::$Route->Param['pid'];
            $navigation[$key]['name'] .= '-pid-' . Zero_App::$Route->Param['pid'];
        }
        if ( 0 < Zero_App::$Route->Param['id'] )
        {
            $navigation[$key]['url'] .= '-id-' . Zero_App::$Route->Param['id'];
            $navigation[$key]['name'] .= '-id-' . Zero_App::$Route->Param['id'];
        }
        if ( 0 < Zero_App::$Route->Param['pg'] )
        {
            $navigation[$key]['url'] .= '-pg-' . Zero_App::$Route->Param['pg'];
            $navigation[$key]['name'] .= '-pg-' . Zero_App::$Route->Param['pg'];
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