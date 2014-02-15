<?php

/**
 * Model. Page content.
 *
 * @package Zero.Content.Model
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 *
 * <BEG_CONFIG_PROPERTY>
 * @property integer $Zero_Section_ID
 * @property string $Name
 * @property string $Content
 * @property string $Block
 * <END_CONFIG_PROPERTY>
 */
class Zero_Content extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Zero_Content';

    /**
     * Configuration model
     *
     * - 'Comment' => 'Comment'
     * - 'Language' => '0, 1'
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Model($Model)
    {
        return [
            /*BEG_CONFIG_MODEL*/
            'Language' => '0'
            /*END_CONFIG_MODEL*/
        ];
    }

    /**
     * Configuration links many to many
     *
     * - 'table_target' => ['table_link', 'prop_this', 'prop_target']
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Link($Model)
    {
        return [
            /*BEG_CONFIG_LINK*/
            /*END_CONFIG_LINK*/
        ];
    }

    /**
     * The configuration properties
     *
     * - 'DB'=> 'T, I, F, E, S, D, B'
     * - 'IsNull'=> 'YES, NO'
     * - 'Default'=> 'mixed'
     * - 'Value'=> [] 'Values ​​for Enum, Set'
     * - 'Comment' = 'PropComment'
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Prop($Model)
    {
        return [
            /*BEG_CONFIG_PROP*/
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => ''],
            'Zero_Section_ID' => ['AliasDB' => 'z.Zero_Section_ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => ''],
            'Zero_Language_ID' => ['AliasDB' => 'z.Zero_Language_ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => 1],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Block' => ['AliasDB' => 'z.Block', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => ''],
            /*END_CONFIG_PROP*/
        ];
    }

    /**
     * Dynamic configuration properties for the filter
     *
     * - 'Filter'=> 'Select, Radio, Checkbox, DateTime, Link, LinkMore, Number, Text, Textarea, Content
     * - 'Search'=> 'Number, Text'
     * - 'Sort'=> false, true
     * - 'Comment' = 'PropComment'
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario
     * @return array
     */
    protected static function Config_Filter($Model, $scenario = '')
    {
        return [
            /*BEG_CONFIG_FILTER_PROP*/
            'ID' => ['AliasDB' => '', 'Filter' => '', 'Search' => 'Number', 'Sort' => true],
            'Zero_Section_ID' => ['AliasDB' => '', 'Filter' => '', 'Search' => '', 'Sort' => false],
            'Zero_Language_ID' => ['AliasDB' => '', 'Filter' => 'Link', 'Search' => '', 'Sort' => false],
            'Name' => ['AliasDB' => '', 'Filter' => '', 'Search' => 'Text', 'Sort' => false],
            'Content' => ['AliasDB' => '', 'Filter' => '', 'Search' => 'Text', 'Sort' => false],
//            'Block' => ['Filter' => 'Select', 'Search' => '', 'Sort' => false],
            /*END_CONFIG_FILTER_PROP*/
        ];
    }

    /**
     * Dynamic configuration properties for the Grid
     *
     * - 'Grid' = 'AliasName.PropName'
     * - 'Comment' = 'PropComment'
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario
     * @return array
     */
    protected static function Config_Grid($Model, $scenario = '')
    {
        return [
            /*BEG_CONFIG_GRID_PROP*/
            'ID' => [],
            'Name' => [],
            'Block' => [],
            /*END_CONFIG_GRID_PROP*/
        ];
    }

    /**
     * Dynamic configuration properties for the form
     *
     * - 'Form'=> [
     *      Number, Text, Select, Radio, Checkbox, Textarea, Date, Time, DateTime, Link,
     *      Hidden, ReadOnly, Password, File, FileData, Img, ImgData, Content', LinkMore
     *      ]
     * - 'Comment' = 'PropComment'
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario forms
     * @return array
     */
    protected static function Config_Form($Model, $scenario = '')
    {
        return [
            /*BEG_CONFIG_FORM_PROP*/
            'ID' => array('Form' => 'Hidden'),
            'Zero_Section_ID' => array('Form' => 'Hidden'),
            'Zero_Language_ID' => array('Form' => 'Link'),
            'Name' => array('Form' => 'Text'),
            'Content' => array('Form' => 'Content'),
            'Block' => array('Form' => 'Text'),
            /*END_CONFIG_FORM_PROP*/
        ];
    }

    /**
     * Getting a list of blocks for template
     *
     * @return array
     * @throws Exception
     */
    public function FL_Block()
    {
        $Model = Zero_Model::Make('Zero_Section', Zero_App::$Section->Zero_Section_ID);
        if ( !$Model->Layout )
        {
            Zero_Logs::Set_Message('Layout not Set');
            return [];
        }
        $template = Zero_View::Search_Template($Model->Layout, true);
        if ( !$template )
        {
            Zero_Logs::Set_Message("Layout '{$Model->Layout}' Not Found");
            return [];
        }

        $result = [];
        $result['content'] = 'content';
        $template = file_get_contents($template);
        if ( preg_match_all(Zero_View::PATTERN_PLUGIN, $template, $match, PREG_SET_ORDER) )
        {
            foreach ($match as $row)
            {
                if ( 'Zero_Content_Page' == $row[1] )
                {
                    if ( isset($row[2]) )
                    {
                        $block = explode('"', $row[2])[1];
                        $result[$block] = $block;
                    }
                }
            }
        }
        return $result;
    }
}