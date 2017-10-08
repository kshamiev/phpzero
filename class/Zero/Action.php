<?php

/**
 * Actions controllers and the access to them.
 *
 * @package Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 *
 * @property integer $Section_ID
 * @property integer $Groups_ID
 * @property string $Action
 */
class Zero_Action extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Action';

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
    protected static function Config_Prop($Model, $scenario = '')
    {
        return [
            /*BEG_CONFIG_PROP*/
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'ID', 'IsNull' => 'NO', 'Default' => '', 'Form' => ''],
            'Section_ID' => ['AliasDB' => 'z.Section_ID', 'DB' => 'ID', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Link'],
            'Groups_ID' => ['AliasDB' => 'z.Groups_ID', 'DB' => 'ID', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Link'],
            'Action' => ['AliasDB' => 'z.Action', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            /*END_CONFIG_PROP*/
        ];
    }

    /**
     * Dynamic configuration properties for the filter
     *
     * - 'Filter'=> 'Select, Radio, Checkbox, Datetime, Link, Linkmore, Number, Text, Textarea, Content
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
            'ID' => ['Visible' => true, 'AR' => true],
            'Section_ID' => ['Visible' => true, 'AR' => true],
            'Groups_ID' => ['Visible' => true, 'AR' => true],
            'Action' => ['Visible' => true, 'AR' => true],
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
            'Action' => [],
            /*END_CONFIG_GRID_PROP*/
        ];
    }

    /**
     * Dynamic configuration properties for the form
     *
     * - 'Form'=> [
     *      Number, Text, Select, Radio, Checkbox, Textarea, Date, Time, Datetime, Link,
     *      Hidden, Readonly, Password, File, Filedata, Img, ImgData, Content', Linkmore
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
            'ID' => [],
            'Section_ID' => [],
            'Groups_ID' => [],
            'Action' => [],
            /*END_CONFIG_FORM_PROP*/
        ];
    }
}