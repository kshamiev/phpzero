<?php

/**
 * Plugin. Output meta tags.
 *
 * {plugin "Zero_Section_SeoTag"}
 *
 * @package Zero.Section.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Section_SeoTag extends Zero_Plugin
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
     * Create views meta tags.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        $seo_data = [
            'Title' => Zero_App::Get_Variable('Title'),
            'Description' => Zero_App::Get_Variable('Description'),
            'Keywords' => Zero_App::Get_Variable('Keywords')
        ];

        $source_name = Zero_App::Get_Variable('SourceName');
        $object_id = Zero_App::Get_Variable('ObjectID');

        if ( $object_id && $source_name )
        {
            $Object = Zero_Model::Make($source_name, $object_id);
            if ( false == $seo = $Object->Cache->Get('seo') )
            {
                $Model = Zero_Model::Make('Zero_Seo');
                $Model->DB->Sql_Where('SourceName', '=', $source_name);
                $Model->DB->Sql_Where('ObjectID', '=', $object_id);
                $seo = $Model->DB->Select_Row('Title, Keywords, Description');
                $Object->Cache->Set('seo', $seo);
            }
            $seo_data = array_merge($seo_data, $seo);
        }

        if ( is_object(Zero_App::$Section) )
        {
            $seo_data = array_merge($seo_data, [
                'Title' => Zero_App::$Section->Title,
                'Description' => Zero_App::$Section->Description,
                'Keywords' => Zero_App::$Section->Keywords
            ]);
        }
        $this->View = new Zero_View(get_class($this));
        $this->View->Assign('seo_data', $seo_data);

        return true;
    }
}