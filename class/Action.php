<?php

/**
 * Model. Actions controllers and the access to them.
 *
 * @package Zero.Action.Model
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 *
 * <BEG_CONFIG_PROPERTY>
 * @property integer $Zero_Section_ID
 * @property integer $Zero_Groups_ID
 * @property string $Action
 * <END_CONFIG_PROPERTY>
 */
class Zero_Action extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Zero_Action';

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
            'ID' => ['DB' => 'I', 'IsNull' => 'NO', 'Default' => ''],
            'Zero_Section_ID' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => ''],
            'Zero_Groups_ID' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => ''],
            'Action' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
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
            'ID' => ['Filter' => '', 'Search' => 'Number', 'Sort' => true],
            'Zero_Section_ID' => ['Filter' => 'Link', 'Search' => '', 'Sort' => false],
            'Zero_Groups_ID' => ['Filter' => 'Link', 'Search' => '', 'Sort' => false],
            'Action' => ['Filter' => 'Select', 'Search' => '', 'Sort' => false],
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
            'ID' => ['Grid' => 'z.ID'],
            'Action' => ['Grid' => 'z.Action'],
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
     * @param string $scenario scenario
     * @return array
     */
    protected static function Config_Form($Model, $scenario = '')
    {
        return [
            /*BEG_CONFIG_FORM_PROP*/
            'ID' => array('Form' => 'Hidden', 'IsNull' => 'NO'),
            'Zero_Section_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
            'Zero_Groups_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
            'Action' => array('Form' => 'Text', 'IsNull' => 'YES'),
            /*END_CONFIG_FORM_PROP*/
        ];
    }
}