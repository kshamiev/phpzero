<?php

/**
 * Page content.
 *
 * @package Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 *
 * @property integer $Section_ID
 * @property string $Lang
 * @property string $Name
 * @property string $Title
 * @property string $Keywords
 * @property string $Description
 * @property string $Content
 * @property string $Target
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
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'ID', 'IsNull' => 'NO', 'Default' => '', 'Form' => ''],
            'Section_ID' => ['AliasDB' => 'z.Section_ID', 'DB' => 'ID', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Hidden'],
            'Lang' => ['AliasDB' => 'z.Lang', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => '', 'Form' => 'Select'],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Textarea'],
            'Target' => ['AliasDB' => 'z.Target', 'DB' => 'E', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Select'],
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
            'ID' => ['Visible' => true, 'AR' => true],
            'Section_ID' => ['Visible' => true, 'AR' => true],
            'Lang' => ['Visible' => true, 'AR' => true],
            'Name' => ['Visible' => true, 'AR' => true],
            'Content' => ['Visible' => true, 'AR' => true],
            'Target' => ['Visible' => true, 'AR' => true],
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
            'ID' => [],
            'Name' => [],
            'Target' => [],
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
     * @param string $scenario scenario forms
     * @return array
     */
    protected static function Config_Form($Model, $scenario = '')
    {
        if ( 'Zero_Content_Edit' == $scenario )
        {
            return [
                'Lang' => [],
                'Name' => [],
                'Content' => [],
                'Target' => [],
            ];
        }
        else
        {
            return [
                'Name' => [],
                'Content' => [],
                'Target' => [],
            ];
        }
    }

    /**
     * Загрузка контент-блока
     *
     * @param string $blockName имя блока
     * @param int $sectionId раздел - страница
     */
    public function Load_Page($blockName, $sectionId = 0)
    {
        if ( 0 < $sectionId )
            $sql = "SELECT * FROM {$this->Source} WHERE Target = '{$blockName}' AND Section_ID = {$sectionId}";
        else
            $sql = "SELECT * FROM {$this->Source} WHERE Target = '{$blockName}' AND Lang = '" . ZERO_LANG . "'";
        $row = Zero_DB::Select_Row($sql);
        $this->Set_Props($row);
    }

    /**
     * Фабрика по созданию объектов.
     *
     * @param integer $id идентификатор объекта
     * @param bool $flagLoad флаг полной загрузки объекта
     * @return Zero_Content
     */
    public static function Make($id = 0, $flagLoad = false)
    {
        return new self($id, $flagLoad);
    }

    /**
     * Фабрика по созданию объектов.
     *
     * Работает через сессию (Zero_Session).
     * Индекс имя класса
     *
     * @param integer $id идентификатор объекта
     * @param bool $flagLoad флаг полной загрузки объекта
     * @return Zero_Content
     */
    public static function Factory($id = 0, $flagLoad = false)
    {
        $index = __CLASS__ . (0 < $id ? '_' . $id : '');
        if ( !$result = Zero_Session::Get($index) )
        {
            $result = self::Make($id, $flagLoad);
            Zero_Session::Set($index, $result);
        }
        return $result;
    }
}