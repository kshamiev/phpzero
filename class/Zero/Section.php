<?php

/**
 * Site Section.
 *
 * Section or page of the site. Determined on the of routing.
 * Object section contains all the information on the basis of the page:
 * - The main controller
 * - Controller action with regard to the rights of access
 * - Subsections with the rights of access
 * - Page Layout
 * - Visibility in the navigation
 * - Seo
 *
 * @package Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 *
 * @property integer $Section_ID
 * @property integer $Controllers_ID
 * @property string $Url
 * @property string $UrlThis
 * @property string $UrlAlias
 * @property string $UrlRedirect
 * @property string $Layout
 * @property string $IsAuthorized
 * @property string $IsVisible
 * @property string $IsEnable
 * @property string $IsIndex
 * @property integer $Sort
 * @property string $Name
 * @property string $Title
 * @property string $Keywords
 * @property string $Description
 * @property string $Content
 * @property bool $Access
 */
class Zero_Section extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Section';

    /**
     * Action List
     *
     * @var array
     */
    private $_Action_List = null;

    /**
     * Право на страницу сайта
     *
     * @var bool
     */
    private $_Access = null;

    /**
     * List subsection
     *
     * @var array
     */
    private $_Navigation_Child = null;

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
            'Controllers_ID' => ['AliasDB' => 'z.Controllers_ID', 'DB' => 'ID', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Link'],
            'Url' => ['AliasDB' => 'z.Url', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Readonly'],
            'UrlThis' => ['AliasDB' => 'z.UrlThis', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => '', 'Form' => 'Text'],
            'UrlAlias' => ['AliasDB' => 'z.UrlAlias', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'UrlRedirect' => ['AliasDB' => 'z.UrlRedirect', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Layout' => ['AliasDB' => 'z.Layout', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Select'],
            'IsAuthorized' => ['AliasDB' => 'z.IsAuthorized', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no', 'Form' => 'Radio'],
            'IsVisible' => ['AliasDB' => 'z.IsVisible', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no', 'Form' => 'Radio'],
            'IsEnable' => ['AliasDB' => 'z.IsEnable', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes', 'Form' => 'Radio'],
            'IsIndex' => ['AliasDB' => 'z.IsIndex', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes', 'Form' => 'Radio'],
            'Sort' => ['AliasDB' => 'z.Sort', 'DB' => 'I', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Number'],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'NameMenu' => ['AliasDB' => 'z.NameMenu', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Title' => ['AliasDB' => 'z.Title', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Keywords' => ['AliasDB' => 'z.Keywords', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Description' => ['AliasDB' => 'z.Description', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Textarea'],
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Content'],
            'Img' => ['AliasDB' => 'z.Img', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Img'],
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
            'IsAuthorized' => ['Visible' => true, 'AR' => true],
            'Layout' => ['AR' => true],
            'IsVisible' => ['Visible' => true, 'AR' => true],
            'IsEnable' => ['Visible' => true, 'AR' => true],
            'IsIndex' => ['Visible' => true, 'AR' => true],
            'Name' => ['Visible' => true, 'AR' => true],
            'NameMenu' => ['Visible' => true, 'AR' => true],
            'Title' => ['Visible' => true, 'AR' => true],
            'Keywords' => ['Visible' => true, 'AR' => true],
            'Description' => ['Visible' => true, 'AR' => true],
            'Controllers_ID' => ['Visible' => false, 'AR' => false],
            'Sort' => ['Visible' => true],
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
            'Url' => [],
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
     * @param string $scenario scenario forms
     * @return array
     */
    protected static function Config_Form($Model, $scenario = '')
    {
        return [
            'Url' => [],
            'UrlThis' => [],
            'UrlAlias' => [],
            'UrlRedirect' => [],
            'Layout' => [],
            'Controllers_ID' => [],
            'IsAuthorized' => [],
            'IsVisible' => [],
            'IsEnable' => [],
            'IsIndex' => [],
            'Sort' => [],
            'NameMenu' => [],
            'Name' => [],
            'Title' => [],
            'Keywords' => [],
            'Description' => [],
            'Content' => [],
            'Img' => [],
        ];
    }

    /**
     * Иициализация раздела по указанному url
     *
     * @param string $url
     */
    public function Load_Url($url = ZERO_URL)
    {
        if ( $this->ID != 0 )
            return;
        $u = Zero_DB::EscT($url);
        $row = Zero_DB::Select_Row("SELECT * FROM Section WHERE UrlAlias = {$u} OR Url = {$u}");
        if ( 0 == count($row) )
            return;
        $this->Set_Props($row);
    }

    /**
     * Получение прав на страницу сайта
     *
     * @return bool
     */
    public function Get_Access()
    {
        if ( !is_null($this->_Access) )
            return $this->_Access;
        if ( 1 == Zero_App::$Users->Groups_ID || 'yes' != $this->IsAuthorized )
        {
            $this->_Access = true;
            return $this->_Access;
        }
        $sql = "SELECT COUNT(*) FROM Action WHERE `Action` = 'Default' AND Section_ID = {$this->ID} AND Groups_ID = " . Zero_App::$Users->Groups_ID;
        if ( 0 < Zero_DB::Select_Field($sql) )
            $this->_Access = true;
        else
            $this->_Access = false;
        return $this->_Access;
    }

    /**
     * Getting subsections, taking into account the rights and visibility.
     *
     * @return array|mixed
     * @throws Exception
     */
    public function Get_Navigation_Child()
    {
        if ( 0 == $this->ID )
        {
            throw new Exception('#{MODEL.Zero_Section} parent section not defined', 409);
        }
        if ( is_null($this->_Navigation_Child) )
        {
            $index = 'Section_Child_' . Zero_App::$Users->Groups_ID;
            if ( false === $this->_Navigation_Child = $this->CH->Get($index) )
            {
                $this->_Navigation_Child = self::DB_Navigation_Child($this->ID);
                $this->CH->Set($index, $this->_Navigation_Child);
            }
        }
        return $this->_Navigation_Child;
    }

    /**
     * Getting subsections, taking into account the rights and visibility.
     *
     * @param integer $id section ID
     * @return array subsections
     */
    public static function DB_Navigation_Child($id)
    {
        //  Access
        if ( 1 < Zero_App::$Users->Groups_ID )
            $sql_where = "
            s.Section_ID = {$id} AND s.IsVisible = 'yes' AND
            (
                s.IsAuthorized = 'no'
                OR
                ( s.IsAuthorized = 'yes' AND a.`Groups_ID` = " . Zero_App::$Users->Groups_ID . " )
            )
            ";
        else
            $sql_where = "
            s.Section_ID = {$id} AND s.IsVisible = 'yes'
            ";
        //
        $sql = "
        SELECT
          s.ID, s.Name, s.NameMenu, SUBSTRING(s.Url, POSITION('/' IN s.Url)) AS Url, UrlThis, Title
        FROM Section AS s
            LEFT JOIN Action AS a ON a.`Section_ID` = s.`ID`
        WHERE
            {$sql_where}
        ORDER BY
          s.`Sort` ASC
        ";
        return Zero_DB::Select_Array_Index($sql);
    }

    /**
     * Update absolute reference in child partitions.
     *
     * @param integer $section_id ID of the parent section
     * @return bool
     */
    public static function DB_Update_Url($section_id)
    {
        $sql = "SELECT Url FROM Section WHERE ID = {$section_id}";
        $url = Zero_DB::Select_Field($sql);
        if ( !$url )
            return false;
        // Update absolute reference in child partitions
        $sql = "
        UPDATE Section
        SET
          Url = CONCAT('" . rtrim($url, '/') . "', '/', UrlThis)
        WHERE
            Section_ID = {$section_id}
        ";
        Zero_DB::Update($sql);
        //  recurses
        $sql = "SELECT ID FROM Section WHERE Section_ID = " . $section_id;
        foreach (Zero_DB::Select_List($sql) as $section_id)
        {
            self::DB_Update_Url($section_id);
        }
        return true;
    }

    /**
     * Url Section
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_UrlThis($value, $scenario)
    {
        if ( 0 < $this->Section_ID )
        {
            $Object = Zero_Model::Makes(__CLASS__, $this->Section_ID);

            $u1 = Helper_Strings::Transliteration_Url($value);
            $u2 = rtrim($Object->Url, '/') . '/' . ltrim($u1, '/');

            $u = Zero_DB::EscT($u2);
            $id = Zero_DB::Select_Field("SELECT ID FROM Section WHERE ( UrlAlias = {$u} OR Url = {$u} ) AND ID != {$this->ID}");
            if ( 0 < $id )
                return 'error_exists: ' . $id;

            $this->UrlThis = $u1;
            $this->Url = $u2;
            return '';
        }
        else
        {
            $this->UrlThis = Helper_Strings::Transliteration_Url($value);
            $this->Url = Helper_Strings::Transliteration_Url($value);
            return '';
        }
    }

    /**
     * UrlAlias Section
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_UrlAlias($value, $scenario)
    {
        if ( !$value )
        {
            $this->UrlAlias = '';
            return '';
        }
        $u = Zero_DB::EscT($value);
        $id = Zero_DB::Select_Field("SELECT ID FROM Section WHERE ( UrlAlias = {$u} OR Url = {$u} ) AND ID != {$this->ID}");
        if ( 0 < $id )
            return 'error_exists: ' . $id;

        $this->UrlAlias = $value;
        return '';
    }

    /**
     * Sample. Filter for property.
     *
     * @return array
     */
    public function FL_Layout()
    {
        $arr = [];
        foreach (glob(ZERO_PATH_ZERO . "/view/*", GLOB_ONLYDIR) as $dir)
        {
            $mod = ucfirst(basename($dir));
            $row = glob($dir . "/*.html");
            foreach ($row as $r)
            {
                $index = $mod . '_' . substr(basename($r), 0, -5);
                $arr[$index] = $index;
            }
        }
        foreach (glob(ZERO_PATH_ZERO . "/*", GLOB_ONLYDIR) as $dir)
        {
            $mod = ucfirst(basename($dir));
            $row = glob($dir . "/view/*.html");
            foreach ($row as $r)
            {
                $index = $mod . '_' . substr(basename($r), 0, -5);
                $arr[$index] = $index;
            }
        }
        foreach (glob(ZERO_PATH_APP . "/view/*", GLOB_ONLYDIR) as $dir)
        {
            $mod = basename($dir);
            $row = glob($dir . "/*.html");
            foreach ($row as $r)
            {
                $index = $mod . '_' . substr(basename($r), 0, -5);
                $arr[$index] = $index;
            }
        }
        foreach (glob(ZERO_PATH_APP . "/view/*.html") as $r)
        {
                $index = substr(basename($r), 0, -5);
                $arr[$index] = $index;
        }
        foreach (glob(ZERO_PATH_APPLICATION . "/*", GLOB_ONLYDIR) as $dir)
        {
            $mod = ucfirst(basename($dir));
            $row = glob($dir . "/view/*.html");
            foreach ($row as $r)
            {
                $index = $mod . '_' . substr(basename($r), 0, -5);
                $arr[$index] = $index;
            }
        }
        return $arr;
    }

    public function FL_Controllers_ID()
    {
        $sql = "SELECT `ID`, `Name` FROM Controllers WHERE `Typ` = 'Web' ORDER BY `Name` ASC";
        return Zero_DB::Select_List_Index($sql);
    }

    /**
     * Фабрика по созданию объектов.
     *
     * @param integer $id идентификатор объекта
     * @param bool $flagLoad флаг полной загрузки объекта
     * @return Zero_Section
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
     * @return Zero_Section
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