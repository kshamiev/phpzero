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
 * @package Zero.Section
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 *
 * @property integer $Section_ID
 * @property string $Url
 * @property string $UrlThis
 * @property string $UrlRedirect
 * @property string $Layout
 * @property string $Controller
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
     * List subsection
     *
     * @var array
     */
    private $_Navigation_Child = null;

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
            /*BEG_CONFIG_PROP*/
            'ID' => ['AliasDB' => 'z.ID', 'DB' => 'I', 'IsNull' => 'NO', 'Default' => '', 'Form' => ''],
            'Section_ID' => ['AliasDB' => 'z.Section_ID', 'DB' => 'I', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Link'],
            'Url' => ['AliasDB' => 'z.Url', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'ReadOnly'],
            'UrlThis' => ['AliasDB' => 'z.UrlThis', 'DB' => 'T', 'IsNull' => 'NO', 'Default' => '', 'Form' => 'Text'],
            'UrlRedirect' => ['AliasDB' => 'z.UrlRedirect', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Layout' => ['AliasDB' => 'z.Layout', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Select'],
            'Controller' => ['AliasDB' => 'z.Controller', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'IsAuthorized' => ['AliasDB' => 'z.IsAuthorized', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no', 'Form' => 'Radio'],
            'IsVisible' => ['AliasDB' => 'z.IsVisible', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no', 'Form' => 'Radio'],
            'IsEnable' => ['AliasDB' => 'z.IsEnable', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes', 'Form' => 'Radio'],
            'IsIndex' => ['AliasDB' => 'z.IsIndex', 'DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes', 'Form' => 'Radio'],
            'Sort' => ['AliasDB' => 'z.Sort', 'DB' => 'I', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Number'],
            'Name' => ['AliasDB' => 'z.Name', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Title' => ['AliasDB' => 'z.Title', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Keywords' => ['AliasDB' => 'z.Keywords', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Text'],
            'Description' => ['AliasDB' => 'z.Description', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Textarea'],
            'Content' => ['AliasDB' => 'z.Content', 'DB' => 'T', 'IsNull' => 'YES', 'Default' => '', 'Form' => 'Content'],
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
            'Controller' => ['Visible' => true],
            'IsAuthorized' => ['Visible' => true],
            'Layout' => [],
            'IsVisible' => ['Visible' => true],
            'IsEnable' => ['Visible' => true],
            'IsIndex' => ['Visible' => true],
            'Name' => ['Visible' => true],
            'Title' => ['Visible' => true],
            'Keywords' => ['Visible' => true],
            'Description' => ['Visible' => true],
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
            'Controller' => [],
            'Url' => [],
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
            'ID' => [],
            'Section_ID' => [],
            'Url' => [],
            'UrlThis' => [],
            'UrlRedirect' => [],
            'Layout' => [],
            'Controller' => [],
            'IsAuthorized' => [],
            'IsVisible' => [],
            'IsEnable' => [],
            'IsIndex' => [],
            'Sort' => [],
            'Name' => [],
            'Title' => [],
            'Keywords' => [],
            'Description' => [],
            'Content' => [],
        ];
    }

    /**
     * Dynamic factory method to create an object through the factory.
     */
    protected function Init()
    {
        if ( $this->ID == 0 )
        {
            $this->Init_Url(Zero_App::$Config->Site_DomainSub . ZERO_URL);
        }
    }

    /**
     * Initialization section on his Url.
     *
     * @param string $url full reference to the section (www.domainname.ru/article)
     */
    public function Init_Url($url)
    {
        $index = $this->Source . '/' . $url . '/' . LANG . '/url';
        if ( false === $row = Zero_Cache::Get_Data($index) )
        {
            $this->AR->Sql_Where('Url', '=', $url);
            $row = $this->AR->Select('*');
            Zero_Cache::Set_Link('Zero_Section', $this->ID);
            Zero_Cache::Set_Data($index, $row);
        }
        else
            $this->Set_Props($row);
    }

    /**
     * Getting a controller actions with regard to the rights section.
     *
     * @return array ist of actions controllers section
     */
    public function Get_Action_List()
    {
        if ( 0 == $this->ID )
            return [];
        else if ( !is_null($this->_Action_List) )
            return $this->_Action_List;

        $controllerName = $this->Controller;
        $index_cache = 'ControllerList_' . Zero_App::$Users->Groups_ID . '_' . $controllerName;
        if ( false !== $this->_Action_List = $this->Cache->Get($index_cache) )
            return $this->_Action_List;

        $this->_Action_List = [];
        if ( 'yes' == $this->IsAuthorized && 1 < Zero_App::$Users->Groups_ID )
        {
            $Model = Zero_Model::Makes('Zero_Action');
            $Model->AR->Sql_Where('Section_ID', '=', $this->ID);
            $Model->AR->Sql_Where('Groups_ID', '=', Zero_App::$Users->Groups_ID);
            $this->_Action_List = $Model->AR->Select_Array_Index('Action');
            foreach ($this->_Action_List as $action => $row)
            {
                $this->_Action_List[$action] = ['Name' => Zero_I18n::Controller($controllerName, 'Action_' . $action)];
            }
        }
        else if ( '' != $controllerName )
        {
            if ( false == Zero_App::Autoload($controllerName) )
                throw new Exception('Класс не найден: ' . $controllerName, 500);

            $reflection = new ReflectionClass($controllerName);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
            {
                $name = $method->getName();
                $arr = explode('_', $name);
                if ( $arr[0] == 'Action' )
                {
                    array_shift($arr);
                    $index = join('_', $arr);
                    $this->_Action_List[$index] = ['Name' => Zero_I18n::Controller($controllerName, $name)];
                }
            }
        }
        Zero_Cache::Set_Link('Zero_Groups', Zero_App::$Users->Groups_ID);
        $this->Cache->Set($index_cache, $this->_Action_List);
        return $this->_Action_List;
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
            if ( false === $this->_Navigation_Child = $this->Cache->Get($index) )
            {
                $this->_Navigation_Child = self::DB_Navigation_Child($this->ID);
                $this->Cache->Set($index, $this->_Navigation_Child);
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
        //  Translation
        if ( LANG != Zero_App::$Config->Site_Language )
        {
            $sql = "
            SELECT
              s.ID, l.Name, SUBSTRING(s.Url, POSITION('/' IN s.Url)) AS Url, s.UrlThis, l.Title
            FROM Section AS s
                INNER JOIN Content AS l ON l.Section_ID = s.ID AND l.Lang = " . ZERO_LANG . " AND l.Block = 'Content'
                LEFT JOIN Action AS a ON a.`Section_ID` = s.`ID`
            WHERE
                {$sql_where}
            ORDER BY
              s.`Sort` ASC
            ";
        }
        else
        {
            $sql = "
            SELECT
              s.ID, s.Name, SUBSTRING(s.Url, POSITION('/' IN s.Url)) AS Url, UrlThis, Title
            FROM Section AS s
                LEFT JOIN Action AS a ON a.`Section_ID` = s.`ID`
            WHERE
                {$sql_where}
            ORDER BY
              s.`Sort` ASC
            ";
        }
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
        $url = Zero_DB::Select_Row($sql);
        if ( !isset($url['Url']) )
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
        if ( !$value )
            return 'Error_Prop';
        $this->UrlThis = Zero_Helper_Strings::Transliteration_Url($value);
        if ( 0 < $this->Section_ID )
        {
            $Object = Zero_Model::Makes(__CLASS__, $this->Section_ID);
            $this->Url = rtrim($Object->Url, '/') . '/' . $this->UrlThis;
        }
        else
            $this->Url = $this->UrlThis . '/';
        return '';
    }

    /**
     * Custom controller
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_Controller($value, $scenario)
    {
        if ( !$value )
            return $this->Controller = null;
        $arr = explode('_', $value);
        $path = ZERO_PATH_APPLICATION . '/' . strtolower(array_shift($arr)) . '/class/' . implode('/', $arr) . '.php';
        if ( !file_exists($path) )
            return 'Error_Path_Class';
        if ( !preg_match("~\nclass {$value}~si", file_get_contents($path)) )
            return 'Error_Class_Exists';
        $this->Controller = $value;
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
}