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
 * @property integer $Section_ID
 * @property string $Lang
 * @property string $Name
 * @property string $Title
 * @property string $Keywords
 * @property string $Description
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
    protected $Source = 'Content';

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
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => '', 'Form' => ''],
            'Section_ID' => ['AliasDB' => 'z.Section_ID', 'DB' => 'I', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Hidden'],
            'Lang' => ['AliasDB' => 'z.Lang', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => '', 'Form' => 'Select'],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Title' => array('AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'),
            'Keywords' => array('AliasDB' => 'z.Keywords', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'),
            'Description' => array('AliasDB' => 'z.Description', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Textarea'),
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Content'],
            'Block' => ['AliasDB' => 'z.Block', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => 'content', 'Form' => 'Text'],
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
            'ID' => ['Visible' => true],
            'Section_ID' => ['Visible' => true],
            'Lang' => ['Visible' => true],
            'Name' => ['Visible' => true],
            'Content' => ['Visible' => true],
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
            'ID' => [],
            'Section_ID' => [],
            'Lang' => [],
            'Name' => [],
            'Title' => [],
            'Keywords' => [],
            'Description' => [],
            'Content' => [],
            'Block' => [],
            /*END_CONFIG_FORM_PROP*/
        ];
    }
}