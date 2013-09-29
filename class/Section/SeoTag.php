<?php

/**
 * Controller. Output meta tags.
 *
 * {plugin "Zero_Section_SeoTag"}
 *
 * @package Zero.Section.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_SeoTag extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views meta tags.
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        $seo_data = [
            'Title' => Zero_App::Get_Variable('Title'),
            'Description' => Zero_App::Get_Variable('Description'),
            'Keywords' => Zero_App::Get_Variable('Keywords')
        ];

        if ( is_object(Zero_App::$Section) && 0 < Zero_App::$Section->ID )
        {
            $seo_data = array_merge($seo_data, [
                'Title' => Zero_App::$Section->Title,
                'Description' => Zero_App::$Section->Description,
                'Keywords' => Zero_App::$Section->Keywords
            ]);
        }
        $this->View = new Zero_View(get_class($this));
        $this->View->Assign('seo_data', $seo_data);
    }
}