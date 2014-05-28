<?php

/**
 * Controller. Page by page
 *
 * Sample: {plugin "Zero_Crud_Pager" view="" Count=$PagerCount Page=$PagerPage PageItem=$PagerPageItem PageStep=$PagerPageStep}
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_Pager extends Zero_Controller
{
    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        if ( null === $this->Params['PageItem'] )
        {
            $this->Params['PageItem'] = Zero_App::$Config->View_PageItem;
        }
        if ( null === $this->Params['PageStep'] )
        {
            $this->Params['PageStep'] = Zero_App::$Config->View_PageStep;
        }
        if ( 0 == $this->Params['Count'] )
        {
            return '';
        }
        $page_count = ceil($this->Params['Count'] / $this->Params['PageItem']);
        if ( $page_count < 2 || !$this->Params['Page'] )
        {
            return '';
        }
        //
        $page_mas = [$this->Params['Page']];
        $i = 0;
        while ( $page_count )
        {
            $i++;
            if ( 0 < $this->Params['Page'] - $i ) //  навигация в начало
            {
                $page_mas[] = $this->Params['Page'] - $i;
            }
            if ( 0 == $this->Params['PageStep'] - count($page_mas) || $page_count == count($page_mas) )
            {
                break;
            }
            if ( $this->Params['Page'] + $i <= $page_count ) //  навигация в конец
            {
                $page_mas[] = $this->Params['Page'] + $i;
            }
            if ( 0 == $this->Params['PageStep'] - count($page_mas) || $page_count == count($page_mas) )
            {
                break;
            }
        }
        sort($page_mas);
        $StepLeft = $this->Params['Page'] - $this->Params['PageStep'];
        if ( $StepLeft < 1 )
        {
            $StepLeft = 1;
        }
        $left = 0 < $this->Params['Page'] - 1 ? $this->Params['Page'] - 1 : 1;
        $right = $page_count < $this->Params['Page'] + 1 ? $this->Params['Page'] : $this->Params['Page'] + 1;
        $StepRight = $this->Params['Page'] + $this->Params['PageStep'];
        if ( $page_count < $StepRight )
        {
            $StepRight = $page_count;
        }
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else
            $this->View = new Zero_View(__CLASS__);
        $this->View->Assign('Page', $this->Params['Page']);
        $this->View->Assign('PageBeg', 1);
        $this->View->Assign('StepLeft', $StepLeft);
        $this->View->Assign('Left', $left);
        $this->View->Assign('PageList', $page_mas);
        $this->View->Assign('Right', $right);
        $this->View->Assign('StepRight', $StepRight);
        $this->View->Assign('PageEnd', $page_count);
        return $this->View;
    }
}