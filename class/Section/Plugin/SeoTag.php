<?php

/**
 * Output meta tags.
 *
 * @sample {plugin "Zero_Section_Plugin_SeoTag" view="Zero_Section_SeoTag"}
 *
 * @package Zero.Section.Page
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_Plugin_SeoTag extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $seo_data = [
            'Title' => Zero_App::$Section->Title,
            'Keywords' => Zero_App::$Section->Keywords,
            'Description' => Zero_App::$Section->Description,
        ];
        $this->View->Assign('seo_data', $seo_data);
        if ( Zero_App::$Section->IsIndex == 'no' )
        {
            $this->View->Assign('seo_index', '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">' . "\n");
        }
        else
        {
            $this->View->Assign('seo_index', '');
        }
        return $this->View;
    }

    /**
     * ������������� �����������
     *
     * @return bool
     */
    protected function Chunk_Init()
    {
        // ������
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else if ( isset($this->Params['tpl']) )
            $this->View = new Zero_View($this->Params['tpl']);
        else if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        // ������ (������)
        // $this->Model = Zero_Model::Makes('Zero_Users');
        return true;
    }
}