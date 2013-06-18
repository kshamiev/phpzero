<?php

/**
 * Model. Users.
 *
 * @package Zero.Users.Model
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 *
 * <BEG_CONFIG_PROPERTY>
 * @property integer $Zero_Groups_ID
 * @property integer $Zero_Users_ID
 * @property string $Name
 * @property string $Login
 * @property string $Password
 * @property string $IsAccess
 * @property string $Email
 * @property string $Phone
 * @property string $Skype
 * @property string $IsCondition
 * @property string $ImgAvatar
 * @property string $IsOnline
 * @property string $DateOnline
 * @property string $Date
 * <END_CONFIG_PROPERTY>
 */
class Zero_Users extends Zero_Model
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'Zero_Users';

    /**
     * KCAPTCacheA
     *
     * @var string
     */
    public $Keystring = '';

    /**
     * Usloviia pol`zovatelia.
     * Prava po gorizotali ( na stroki )
     *
     * @var array('Users_ID'=>23, ...)
     */
    private $_Condition = null;

    /**
     * Vremia proshedshee v sekundakh ot nachala e`pohi Unix (TIMESTAMP)
     *
     * @var integer
     */
    public $Timeout = 0;

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
            'Language' => false,
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
            'Zero_Groups_ID' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => '2'],
            'Zero_Users_ID' => ['DB' => 'I', 'IsNull' => 'YES', 'Default' => ''],
            'Name' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Login' => ['DB' => 'T', 'IsNull' => 'NO', 'Default' => ''],
            'Password' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'IsAccess' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'open'],
            'Email' => ['DB' => 'T', 'IsNull' => 'NO', 'Default' => ''],
            'Phone' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'Skype' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'IsCondition' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'yes'],
            'ImgAvatar' => ['DB' => 'T', 'IsNull' => 'YES', 'Default' => ''],
            'IsOnline' => ['DB' => 'E', 'IsNull' => 'NO', 'Default' => 'no'],
            'DateOnline' => ['DB' => 'D', 'IsNull' => 'YES', 'Default' => ''],
            'Date' => ['DB' => 'D', 'IsNull' => 'YES', 'Default' => ''],
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
            'Zero_Groups_ID' => ['Filter' => 'LinkMore', 'Search' => '', 'Sort' => false],
            'Zero_Users_ID' => ['Filter' => 'Link', 'Search' => '', 'Sort' => false],
            'Name' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'Login' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'IsAccess' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'Email' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'Phone' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'Skype' => ['Filter' => '', 'Search' => 'Text', 'Sort' => true],
            'IsCondition' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'IsOnline' => ['Filter' => 'Radio', 'Search' => '', 'Sort' => false],
            'Date' => ['Filter' => 'DateTime', 'Search' => '', 'Sort' => true],
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
            'Email' => ['Grid' => 'z.Email'],
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
            'ID' => array('Form' => 'Hidden', 'IsNull' => 'NO'),
            'Zero_Groups_ID' => array('Form' => 'LinkMore', 'IsNull' => 'YES'),
            'Zero_Users_ID' => array('Form' => 'Link', 'IsNull' => 'YES'),
            'Name' => array('Form' => 'Text', 'IsNull' => 'YES'),
            'Login' => array('Form' => 'Text', 'IsNull' => 'NO'),
            'Password' => array('Form' => 'Password', 'IsNull' => 'YES'),
            'IsAccess' => array('Form' => 'Radio', 'IsNull' => 'NO'),
            'Email' => array('Form' => 'Text', 'IsNull' => 'NO'),
            'Phone' => array('Form' => 'Text', 'IsNull' => 'YES'),
            'Skype' => array('Form' => 'Text', 'IsNull' => 'YES'),
            'IsCondition' => array('Form' => 'Radio', 'IsNull' => 'NO'),
            'ImgAvatar' => array('Form' => 'Img', 'IsNull' => 'YES'),
            'IsOnline' => array('Form' => 'ReadOnly', 'IsNull' => 'NO'),
            'DateOnline' => array('Form' => 'ReadOnly', 'IsNull' => 'YES'),
            'Date' => array('Form' => 'Date', 'IsNull' => 'YES'),
            /*END_CONFIG_FORM_PROP*/
        ];
    }

    /**
     * Dinahmicheskii` fabrichny`i` metod dlia sozdanii ob``ekta cherez fabriku.
     */
    protected function Init()
    {
        $this->Zero_Groups_ID = 2;
        $this->Login = 'guest';
    }

    /**
     * Poluchenie uslovii` pol`zovatelia
     *
     * @return array - massiv uslovii` pol`zovaetia (cliuch svoi`stvo, znachenie uslovie)
     */
    public function Get_Condition()
    {
        if ( is_null($this->_Condition) )
        {
            $this->_Condition = [];
            //  uslovie pol`zovatelia
            if ( 'yes' == $this->IsCondition )
            {
                $prop = 'Zero_Users_' . $this->Zero_Groups_ID . '_ID';
                $this->DB->Sql_Where('Zero_Users_ID', '=', $this->ID);
                $this->_Condition[$prop] = $this->DB->Select_List('ID, Name');
                $this->_Condition[$prop][$this->ID] = $this->Name;
            }
            //  uslovie gruppy`
            if ( 1 != $this->Zero_Groups_ID )
                $this->_Condition['Zero_Groups_ID'][$this->Zero_Groups_ID] = $this->Zero_Groups_ID()->Name;
            //  dopolnitel`ny`e usloviia
            foreach ($this->Get_Props(1) as $prop => $value)
            {
                if ( 'Zero_Groups_ID' == $prop || 'Zero_Users_ID' == $prop )
                    continue;
                if ( '_ID' == substr($prop, -3) && 0 < $value )
                {
                    $Model = Zero_Model::Make(zero_relation($prop), $value);
                    $this->_Condition[$prop][$value] = $Model->Name;
                }
            }
        }
        return $this->_Condition;
    }

    /**
     * Initcializatciia onlai`n statusa ne aktivny`kh pol`zovatelei`.
     *
     * Tai`maut ne aktivny`kh pol`zovatelei` 10 minut.
     * V dal`nei`shem e`tot parametr mozhno zavesti v konfiguratciiu sai`ta i regulirovat`.
     * @param integer $seconds Tai`maut v sekundakh po istechenii kotorogo pol`zovatel` schitaetsia pokinuvshim sai`t
     * @return boolean flag run of the next chunk
     */
    public static function DB_Offline($seconds = 600)
    {
        $sql = "UPDATE Zero_Users SET IsOnline = 'no' WHERE DateOnline < NOW() - INTERVAL {$seconds} SECOND";
        Zero_DB::Query_Set($sql);
    }

    /**
     * Validatciia logina.
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_Login($value, $scenario)
    {
        if ( 0 < $this->ID )
            $this->DB->Sql_Where('ID', '!=', $this->ID);
        $this->DB->Sql_Where('Login', '=', $value);
        $cnt = $this->DB->Select_Count();
        if ( 0 < $cnt )
            return 'Error_Exists';
        $this->Login = $value;
    }

    /**
     * Validatciia e`lektronnogo pochtovogo adresa.
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_Email($value, $scenario)
    {
        if ( !preg_match(Zero_Validator::PATTERN_EMAIL, $value) )
            return 'Error_NotValid';
        if ( 'reminder' == $scenario )
        {
            $this->DB->Sql_Where('Email', '=', $value);
            $cnt = $this->DB->Select_Count();
            if ( !$cnt )
                return 'Error_NotRegistration';
        }
        else
        {
            $this->DB->Sql_Where('Email', '=', $value);
            if ( 0 < $this->ID )
                $this->DB->Sql_Where('ID', '!=', $this->ID);
            $cnt = $this->DB->Select_Count();
            if ( 0 < $cnt )
                return 'Error_Registration';
        }
        $this->Email = $value;
    }

    /**
     * Validatciia parolia
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_Password($value, $scenario)
    {
        if ( !$value )
            return '';
        if ( isset($_REQUEST['Users']['PasswordR']) && $value != $_REQUEST['Users']['PasswordR'] )
            return 'Error_PasswordValid';
        $this->Password = md5($value);
    }

    /**
     * Dppolnitel`naia validatciia parolia
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_PasswordR($value, $scenario)
    {
        if ( !$value )
            return '';
        if ( $value != $_REQUEST['Users']['Password'] )
            return 'Error_PasswordValid';
    }

    /**
     * Validatciia kontrol`noi` stroki.
     *
     * Zashchita ot botov
     *
     * @param mixed $value value to check
     * @param string $scenario scenario validation
     * @return string
     */
    public function VL_Keystring($value, $scenario)
    {
        if ( Zero_App::$Users->Keystring != $value )
            return 'Error_Keystring';
    }
}