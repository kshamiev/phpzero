<?php

/**
 * Component. Fil`tr.
 *
 * Fil`try`, poisk, sortirovka, nomer tekushchei` stranitcy`.
 * Fil`tr vedetsia po poliam sviazei`, perechisleniiam, mnozhestvam i vremenny`m (data i vremia).
 * Poik i sortirovka po vsem ostal`ny`m chislovy`m i strokovy`m poliam.
 * Reazlizuet fil`tratciiu pri vy`vode ob``ektov v gride.
 * V forme vy`stupaet kak spravochnik variantov znachenii` dlia svoi`stv.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Filter
{
    /**
     * Model` dlia kotoroi` sozdaiutsia fil`try`
     *
     * @var Zero_Model
     */
    protected $Model;

    /**
     * Konfiguratciia svoi`stv dlia fil`trov.
     *
     * - [$prop]['Comment'] Nazvanie fil`tra
     * - [$prop]['Filter'] Tip fil`tra
     * - [$prop]['Visible'] Vidimost` fil`tra
     * - [$prop]['Value'] Ustanovlennoi` znachenie
     * - [$prop]['List'] Spisok vozmozhny`kh znachenii`
     *
     * @var array
     */
    protected $Filter = [];

    /**
     * Svoi`stva po kotory`m ishchem
     *
     * - Spisok svoi`stv
     * - Ikh tipy`
     * - Ikh Znacheniia
     *
     * @var array
     */
    protected $Search = [];

    /**
     * Ssy`lki dlia zaprosa v BD
     *
     * Formiruiutsia na osnove peredanny`kh svoi`tsv
     *
     * @var array
     */
    protected $Alias = [];

    /**
     * Svoi`stva po kotory`m sortiruem
     *
     * - Spisok svoi`stv
     * - Ikh Znacheniia
     *
     * @var array
     */
    protected $Sort = [];

    /**
     * Tekushchaia vy`brannaia stranitca
     *
     * @var integer
     */
    public $Page = 1;

    /**
     * Kolichetsvo elementov na stranitce
     *
     * @var integer
     */
    public $Page_Item;

    /**
     * Flag initcializatcii fil`tra
     *
     * @var boolean
     */
    public $IsInit = false;

    /**
     * Sozdanie i initcializatciia fil`tra.
     *
     * @param Zero_Model $Model  Delegirovannaia model` dlia kotoroi` sozdaiutsia fil`try`
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
        $this->Reset();
    }

    /**
     * Sozdanie i initcializatciia fil`tra.
     *
     * Rabotaet cherez sessiiu (Zero_Session)
     *
     * @param Zero_Model $Model Delegirovannaia model` dlia kotoroi` sozdaiutsia fil`try`
     * @return Zero_Filter
     */
    public static function Factory($Model)
    {
        $index = '_Filter' . '_' . $Model->Get_Source();
        if ( !$result = Zero_Session::Get($index) )
        {
            $result = new self($Model);
            Zero_Session::Set($index, $result);
        }
        return $result;
    }

    /**
     * Get alias from property
     *
     * @return array alias
     */
    public function Get_Alias()
    {
        return $this->Alias;
    }

    /**
     * Pervichnaia obrabotka svoi`stv fil`trov, poiska i sortirovki
     *
     * @param string $prop property [TableName.PropName]
     * @return string property [PropName]
     */
    protected function Set_Alias($prop)
    {
        $arr = explode('.', $prop);
        if ( 1 < count($arr) )
        {
            $this->Alias[$arr[1]] = $arr[0] . '.';
            return $arr[1];
        }
        else
        {
            $this->Alias[$arr[0]] = 'z.';
            return $arr[0];
        }
    }

    /**
     * Dobavlenie fil`tra sviazi (s bol`shim chislom ob``ektov)
     *
     * Fil`tr po poliu iavliaiushchimsia sviaz`iu s drugim ob``ektov
     *
     * @param string $prop Svoi`stvo sviazi dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @return bool
     */
    public function Add_Filter_LinkMore($prop, $is_visible = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'LinkMore';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        return true;
    }

    /**
     * Dobavlenie fil`tra sviazi (s nebol`shim chislom ob``ektov)
     *
     * @param string $prop Svoi`stvo sviazi dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Link($prop, $is_visible = 0, $load = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'Link';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( is_array($load) )
        {
            $this->Filter[$prop]['List'] = $load;
        }
        else if ( 0 < $load )
        {
            if ( 'Zero_Language_ID' == $prop )
                $this->Filter[$prop]['List'] = $this->FL_Zero_Language_ID();
            else if ( method_exists($this->Model, $method = 'FL_' . $prop) )
                $this->Filter[$prop]['List'] = $this->Model->$method();
            else
            {
                $Model = Zero_Model::Make(zero_relation($prop));
                if ( 0 < $id = explode('_', $prop)[2] )
                    $Model->DB->Sql_Where('Zero_Groups_ID', '=', $id);
                $Model->DB->Sql_Order('Name', 'ASC');
                $this->Filter[$prop]['List'] = $Model->DB->Select_List_Index('ID, Name');
            }
        }
        return true;
    }

    /**
     * Zagruzka fil`tra iazy`kov
     *
     * @return array
     */
    public static function FL_Zero_Language_ID()
    {
        $result = [];
        foreach (Zero_App::$Config->Language as $val)
        {
            $result[$val['ID']] = $val['Name'];
        }
        return $result;
    }

    /**
     * Dobavlenie fil`tra perchisleniia
     *
     * @param string $prop Svoi`stvo perechisleniia dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - Avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Select($prop, $is_visible = 0, $load = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'Select';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( is_array($load) )
        {
            $this->Filter[$prop]['List'] = $load;
        }
        else if ( 0 < $load )
        {
            if ( method_exists($this->Model, $method = 'FL_' . $prop) )
                $this->Filter[$prop]['List'] = $this->Model->$method();
            else
                $this->Filter[$prop]['List'] = $this->Model->Get_Config_Prop()[$prop]['Value'];
        }
        return true;
    }

    /**
     * Dobavlenie fil`tra perchisleniia
     *
     * @param string $prop Svoi`stvo perechisleniia dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Radio($prop, $is_visible = 0, $load = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'Radio';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( is_array($load) )
        {
            $this->Filter[$prop]['List'] = $load;
        }
        else if ( 0 < $load )
        {
            if ( method_exists($this->Model, $method = 'FL_' . $prop) )
                $this->Filter[$prop]['List'] = $this->Model->$method();
            else
                $this->Filter[$prop]['List'] = $this->Model->Get_Config_Prop()[$prop]['Value'];
        }
        return true;
    }

    /**
     * Dobavlenie fil`tra mnozhestva
     *
     * @param string $prop Svoi`stvo mnozhestva dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Checkbox($prop, $is_visible = 0, $load = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'Checkbox';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( is_array($load) )
        {
            $this->Filter[$prop]['List'] = $load;
        }
        else if ( 0 < $load )
        {
            if ( method_exists($this->Model, $method = 'FL_' . $prop) )
                $this->Filter[$prop]['List'] = $this->Model->$method();
            else
                $this->Filter[$prop]['List'] = $this->Model->Get_Config_Prop()[$prop]['Value'];
        }
        return true;
    }

    /**
     * Dobavlenie fil`tra daty` i vremeni
     *
     * @param string $prop Svoi`stvo daty` i vremeni dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @return bool
     */
    public function Add_Filter_DateTime($prop, $is_visible = 0)
    {
        $prop = $this->Set_Alias($prop);
        if ( isset($this->Filter[$prop]) )
            return true;
        $this->Filter[$prop] = [];
        //        $this->Filter[$prop]['Comment'] = $this->Model->Get_Config_Filter()[$prop]['Comment'];
        $this->Filter[$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Filter[$prop]['Filter'] = 'DateTime';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = ['', ''];
        return true;
    }

    /**
     * Ustanovka znachenii` fil`tra
     *
     * @param string $prop Svoi`stvo
     * @param mixed $value Znachenie fil`tra
     * @return bool
     */
    public function Set_Filter($prop, $value)
    {
        $this->Filter[$prop]['Value'] = $value;
        return true;
    }

    /**
     * Getter. Poluchenie fil`trov.
     *
     * @return array
     */
    public function Get_Filter()
    {
        return $this->Filter;
    }

    /**
     * Dobavlenie poiska po chislam
     *
     * @param string $prop Svoi`stvo po kotoromu vozmozhen poisk
     * @return bool
     */
    public function Add_Search_Number($prop)
    {
        $prop = $this->Set_Alias($prop);
        $this->Search['List'][$prop]['Form'] = 'Number';
        $this->Search['List'][$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Search['Value'][$prop] = '';
        return true;
    }

    /**
     * Dobavlenie poiska po strokam i tekstu
     *
     * @param string $prop Svoi`stvo po kotoromu vozmozhen poisk
     * @return bool
     */
    public function Add_Search_Text($prop)
    {
        $prop = $this->Set_Alias($prop);
        $this->Search['List'][$prop]['Form'] = 'Text';
        $this->Search['List'][$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Search['Value'][$prop] = '';
        return true;
    }

    /**
     * Ustanovka znachenii` poiskovogo Fil`tra
     *
     * @param string $prop Svoi`stvo ('ALL_PROPS' poisk po vsem dobavlenny`m poliam)
     * @param string $value Znachenie (poiskovy`i` zapros)
     */
    public function Set_Search($prop = '', $value = '')
    {
        if ( '' == $prop )
        {
            foreach ($this->Search['Value'] as $prop => $value)
            {
                $this->Search['Value'][$prop] = '';
            }
        }
        else
            $this->Search['Value'][$prop] = $value;
    }

    /**
     * Getter. Poluchenie poiska.
     *
     * @return array
     */
    public function Get_Search()
    {
        return $this->Search;
    }

    /**
     * Dobavlenie sortirovki
     *
     * @param string $prop Svoi`stvo po kotoromu vozmzhna sortirovka
     * @return bool
     */
    public function Add_Sort($prop)
    {
        $prop = $this->Set_Alias($prop);
        $this->Sort['List'][$prop]['Comment'] = Zero_I18n::T($this->Model->Get_Source(), 'model prop ' . $prop, $prop);
        $this->Sort['Value'][$prop] = '';
        return true;
    }

    /**
     * Ustanovka znacheniia sortirovki
     *
     * @param string $prop Svoi`stvo
     * @param string $value Napravlenie sortirovki
     */
    public function Set_Sort($prop = '', $value = 'ASC')
    {
        if ( '' == $prop )
        {
            foreach ($this->Sort['Value'] as $prop => $value)
            {
                $this->Sort['Value'][$prop] = '';
            }
        }
        else
            $this->Sort['Value'][$prop] = $value;
    }

    /**
     * Getter. Poluchenie sortirovki.
     *
     * @return array
     */
    public function Get_Sort()
    {
        return $this->Sort;
    }

    /**
     * Sbros fil`tra
     *
     * Esli svoi`stvo ne ukazano sbrasy`vaetsia ves` fil`tr
     *
     * @param string $prop Svoi`stvo
     */
    public function Reset($prop = '')
    {
        if ( '' == $prop )
        {
            //  fil`try`
            $this->Filter = [];
            //  poisk
            $this->Search = ['List' => [], 'Value' => []];
            $this->Add_Search_Text('ALL_PROPS');
            //  sortirovka
            $this->Sort = ['List' => [], 'Value' => []];
            //
            $this->Page = 1;
            $this->Page_Item = Zero_App::$Config->View_PageItem;
            $this->IsInit = false;
        }
        else
        {
            unset($this->Filter[$prop]);
        }
    }
}
