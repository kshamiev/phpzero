<?php

/**
 * Model. Site Section.
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
 * @package Zero.Section.Model
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 *
 * <BEG_CONFIG_PROPERTY>
 * @property integer $Zero_Section_ID
 * @property integer $Zero_Layout_ID
 * @property string $Url
 * @property string $UrlThis
 * @property string $UrlRedirect
 * @property string $Layout
 * @property string $ContentType
 * @property string $Controller
 * @property string $IsAuthorized
 * @property string $IsVisible
 * @property string $IsEnable
 * @property integer $Sort
 * @property string $Name
 * @property string $Title
 * @property string $Keywords
 * @property string $Description
 * <END_CONFIG_PROPERTY>
 */
class Zero_Section extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Zero_Section';

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
     * The configuration language properties
     *
     * @param Zero_Model $Model The exact working model
     * @return string
     */
    protected static function Config_Prop_Lang($Model)
    {
        return 'Name, Title, Keywords, Description';
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
            'Zero_Layout_ID' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => ''],
            'Url' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'UrlThis' => ['DB' => 'T', 'IsNull' => 'NO', 'Default' => ''],
            'UrlRedirect' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Layout' => ['DB' => 'T', 'IsNull' => 'NO', 'Default' => 'index'],
            'ContentType' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'html'],
            'Controller' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'IsAuthorized' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no'],
            'IsVisible' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no'],
            'IsEnable' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes'],
            'Sort' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => ''],
            'Name' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Title' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Keywords' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Description' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
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
            'z.ID' => ['Filter' => '', 'Search' => 'Number', 'Sort' => true],
            'z.Zero_Layout_ID' => ['Filter' => 'Link', 'Search' => '', 'Sort' => false],
            'z.Controller' => ['Filter' => '', 'Search' => 'Text', 'Sort' => false],
            'z.IsAuthorized' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'z.IsVisible' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'z.IsEnable' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'z.Name' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'z.Title' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'z.Keywords' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'z.Description' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'z.Sort' => ['Filter' => '', 'Search' => '', 'Sort' => true],
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
            'Name' => ['Grid' => 'z.Name'],
            'Controller' => ['Grid' => 'z.Controller'],
            'Url' => ['Grid' => 'z.Url'],
            'Sort' => ['Grid' => 'z.Sort'],
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
        if ( 1 == Zero_App::$Users->Zero_Groups_ID )
            return [
                /*BEG_CONFIG_FORM_PROP*/
                'ID' => array('Form' => 'Hidden', 'IsNull' => 'NO'),
                'Zero_Section_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
                'Zero_Layout_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
                'Url' => array('Form' => 'ReadOnly', 'IsNull' => 'YES'),
                'UrlThis' => array('Form' => 'Text', 'IsNull' => 'NO'),
                'UrlRedirect' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Layout' => array('Form' => 'Text', 'IsNull' => 'NO'),
                'ContentType' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'Controller' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'IsAuthorized' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'IsVisible' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'IsEnable' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'Sort' => array('Form' => 'Number', 'IsNull' => 'YES'),
                'Name' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Title' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Keywords' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Description' => array('Form' => 'Textarea', 'IsNull' => 'YES'),
                /*END_CONFIG_FORM_PROP*/
            ];
        else
            return [
                /*BEG_CONFIG_FORM_PROP*/
                'ID' => array('Form' => 'Hidden', 'IsNull' => 'NO'),
                'Zero_Section_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
                'Zero_Layout_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
                'Url' => array('Form' => 'ReadOnly', 'IsNull' => 'YES'),
                'UrlThis' => array('Form' => 'Text', 'IsNull' => 'NO'),
                'UrlRedirect' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'IsAuthorized' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'IsVisible' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'IsEnable' => array('Form' => 'Radio', 'IsNull' => 'NO'),
                'Sort' => array('Form' => 'Number', 'IsNull' => 'YES'),
                'Name' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Title' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Keywords' => array('Form' => 'Text', 'IsNull' => 'YES'),
                'Description' => array('Form' => 'Text', 'IsNull' => 'YES'),
                /*END_CONFIG_FORM_PROP*/
            ];
    }

    /**
     * Dynamic factory method to create an object through the factory.
     */
    protected function Init()
    {
        $this->Init_Url(Zero_App::$Config->Host . Zero_App::$Route->UrlSection);
    }

    /**
     * Initialization section on his Url.
     *
     * @param string $url full reference to the section (www.domainname.ru/article)
     */
    public function Init_Url($url)
    {
        $index = $this->Source . '/' . $url . '/' . Zero_App::$Route->Lang . '/url';
        if ( false === $row = Zero_Cache::Get_Data($index) )
        {
            $this->DB->Sql_Where('Url', '=', $url);
            $row = $this->DB->Load('*', true);
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
            return $this->_Action_List = [];
        $index_cache = 'Action_List_' . Zero_App::$Users->Zero_Groups_ID . '_' . $this->Controller;
        if ( false !== $this->_Action_List = $this->Cache->Get($index_cache) )
            return $this->_Action_List;

        if ( 'yes' == $this->IsAuthorized && 1 < Zero_App::$Users->Zero_Groups_ID )
        {
            $Model = Zero_Model::Make('Zero_Action');
            $Model->DB->Sql_Where('Zero_Section_ID', '=', $this->ID);
            $Model->DB->Sql_Where('Zero_Groups_ID', '=', Zero_App::$Users->Zero_Groups_ID);
            $this->_Action_List = $Model->DB->Select_Array_Index('Action');
            foreach ($this->_Action_List as $action => $row)
            {
                if ( 'AccessAllow' == $action )
                    $index = "controller action {$action}";
                else
                    $index = "controller {$this->Controller} action {$action}";
                $this->_Action_List[$action] = ['Name' => Zero_I18n::T($this->Controller, $index, $action)];
            }
        }
        else
        {
            $reflection = new ReflectionClass($this->Controller);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PROTECTED) as $method)
            {
                $name = $method->getName();
                if ( 'Action' == substr($name, 0, 6) )
                {
                    $name = str_replace('Action_', '', $name);
                    if ( 'AccessAllow' == $name )
                        $index = "controller action {$name}";
                    else
                        $index = "controller {$this->Controller} action {$name}";
                    $this->_Action_List[$name] = ['Name' => Zero_I18n::T($this->Controller, $index, $name)];
                }
            }
        }
        Zero_Cache::Set_Link('Zero_Groups', Zero_App::$Users->Zero_Groups_ID);
        $this->Cache->Set($index_cache, $this->_Action_List);
        return $this->_Action_List;
    }

    /**
     * Getting subsections, taking into account the rights and visibility.
     *
     * @return array subsection
     */
    public function Get_Navigation_Child()
    {
        if ( 0 == $this->ID )
        {
            Zero_Logs::Set_Message('#{MODEL.Zero_Section} parent section not defined');
            return [];
        }
        if ( is_null($this->_Navigation_Child) )
        {
            $index = 'Section_Child_' . Zero_App::$Users->Zero_Groups_ID;
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
        if ( 1 < Zero_App::$Users->Zero_Groups_ID )
            $sql_where = "
            s.Zero_Section_ID = {$id} AND s.IsVisible = 'yes' AND
            (
                s.IsAuthorized = 'no'
                OR
                ( s.IsAuthorized = 'yes' AND a.`Zero_Groups_ID` = " . Zero_App::$Users->Zero_Groups_ID . " )
            )
            ";
        else
            $sql_where = "
            s.Zero_Section_ID = {$id} AND s.IsVisible = 'yes'
            ";
        //  Translation
        if ( Zero_App::$Route->Lang != Zero_App::$Config->Site_Language )
        {
            $sql = "
            SELECT
              s.ID, l.Name, SUBSTRING(s.Url, POSITION('/' IN s.Url)) AS Url, UrlThis, Title
            FROM Zero_Section AS s
                INNER JOIN Zero_SectionLanguage AS l ON l.Zero_Section_ID = s.ID AND l.Zero_Language_ID = " . Zero_App::$Route->LangId . "
                LEFT JOIN Zero_Action AS a ON a.`Zero_Section_ID` = s.`ID`
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
            FROM Zero_Section AS s
                LEFT JOIN Zero_Action AS a ON a.`Zero_Section_ID` = s.`ID`
            WHERE
                {$sql_where}
            ORDER BY
              s.`Sort` ASC
            ";
        }
        return Zero_DB::Sel_Array_Index($sql);
    }

    /**
     * Update absolute reference in child partitions.
     *
     * @param integer $section_id ID of the parent section
     * @return bool
     */
    public static function DB_Update_Url($section_id)
    {
        $sql = "SELECT Url FROM Zero_Section WHERE ID = {$section_id}";
        $url = Zero_DB::Sel_Agg($sql);
        if ( !$url )
            return false;
        // Update absolute reference in child partitions
        $sql = "
        UPDATE Zero_Section
        SET
          Url = CONCAT('" . $url . "', '/', UrlThis)
        WHERE
            Zero_Section_ID = {$section_id}
        ";
        Zero_DB::Set($sql);
        //  recurses
        $sql = "SELECT ID FROM Zero_Section WHERE Zero_Section_ID = " . $section_id;
        foreach (Zero_DB::Sel_List($sql) as $section_id)
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
        $this->UrlThis = Zero_Lib_String::Transliteration_Url($value);
        if ( 0 < $this->Zero_Section_ID )
        {
            $Object = Zero_Model::Make($this->Source, $this->Zero_Section_ID);
            $this->Url = $Object->Url . '/' . $this->UrlThis;
        }
        else
            $this->Url = $this->UrlThis;
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
        $module = strtolower(array_shift($arr));
        $class = implode('/', $arr);
        if ( !file_exists($path = ZERO_PATH_APPLICATION . '/' . $module . '/class/' . $class . '.php') )
            return 'Error_Path_Class';
        if ( !preg_match("~\nclass {$value}~si", file_get_contents($path)) )
            return 'Error_Class_Exists';
        $this->Controller = $value;
    }
}