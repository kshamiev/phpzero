<?php

/**
 * Page content.
 *
 * @package Zero.Content
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 *
 * @property integer $Section_ID
 * @property string $Lang
 * @property string $Name
 * @property string $Title
 * @property string $Keywords
 * @property string $Description
 * @property string $Content
 * @property string $Block
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
     * Configuration links many to many
     *
     * - 'table_target' => ['table_link', 'prop_this', 'prop_target']
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Link($Model, $scenario = '')
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
    protected static function Config_Prop($Model, $scenario = '')
    {
        return [
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => '', 'Form' => ''],
            'Section_ID' => ['AliasDB' => 'z.Section_ID', 'DB' => 'I', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Hidden'],
            'Lang' => ['AliasDB' => 'z.Lang', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => '', 'Form' => 'Select'],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Textarea'],
            'Block' => ['AliasDB' => 'z.Block', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => 'content', 'Form' => 'Select'],
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
            'ID' => ['Visible' => true],
            'Section_ID' => ['Visible' => true],
            'Lang' => ['Visible' => true],
            'Name' => ['Visible' => true],
            'Content' => ['Visible' => true],
            'Block' => ['Visible' => false],
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
            'Block' => [],
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
                'ID' => [],
                'Lang' => [],
                'Name' => [],
                'Content' => [],
                'Block' => [],
            ];
        }
        else
        {
            return [
                'ID' => [],
                'Name' => [],
                'Content' => [],
                'Block' => [],
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
            $sql = "SELECT * FROM {$this->Source} WHERE Block = '{$blockName}' AND Section_ID = {$sectionId}";
        else
            $sql = "SELECT * FROM {$this->Source} WHERE Block = '{$blockName}' AND Lang = '" . ZERO_LANG . "'";
        $row = Zero_DB::Select_Row($sql);
        $this->Set_Props($row);
    }

    /**
     * Фильтр для блока и его значения
     *
     * @return array
     */
    public function FL_Block()
    {
        return [
            'header_Left' => 'header_Left',
            'header_Center' => 'header_Center',
            'header_Right' => 'header_Right',
            'content_Left' => 'content_Left',
            'content_Center' => 'content_Center',
            'content_Right' => 'content_Right',
            'footer_Left' => 'footer_Left',
            'footer_Center' => 'footer_Center',
            'footer_Right' => 'footer_Right',
        ];
    }

    /**
     * Динамический фабричный метод длиа создании объекта через фабрику и инстанс.
     */
    protected function Init()
    {
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
     * Сохраниаетсиа в {$тис->_Инстанcе}
     *
     * @param integer $id идентификатор объекта
     * @param bool $flagLoad флаг полной загрузки объекта
     * @return Zero_Content
     */
    public static function Instance($id = 0, $flagLoad = false)
    {
        $index = __CLASS__ . (0 < $id ? '_' . $id : '');
        if ( !isset(self::$Instance[$index]) )
        {
            $result = self::Make($id, $flagLoad);
            $result->Init();
            self::$Instance[$index] = $result;
        }
        return self::$Instance[$index];
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
    public static function Factor($id = 0, $flagLoad = false)
    {
        if ( !$result = Zero_Session::Get(__CLASS__) )
        {
            $result = self::Make($id, $flagLoad);
            $result->Init();
            Zero_Session::Set(__CLASS__, $result);
        }
        return $result;
    }
}