<?php

/**
 * Component. Bazovaia abstraktnaia model`.
 *
 * - Realizuet spetcializirovannuiu i abstratknuiu obrabotku svoi`stv modeli
 * - Agregator razlichny`kh komponentov po rabote s nasleduemy`m ob``ektom
 * - Ispol`zuet pattern Kompozitciiu s delegirovaniem
 * - Rabota so svoi`stvami proishodit cherez metody` peregruzki
 *
 * Inkapusliruet v sebe sleduiushchie komponenty` sistemy`:
 * - Validator svoi`stv nasleduemogo ob``ekta pri ego izmenenii
 * - Sistema ob``ektnogo (tcelevogo) keshirovaniia
 * - Component on interaction and working with the database in the philosophy of ORM
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 *
 * @property integer $ID Identifikator ob``ekta
 * @property string $Source Istochnik ili khraniashchaia danny`kh ob``ektov
 * @property Zero_DB $DB Ob``ekt dlia raboty` s BD
 * @property Zero_Validator $VL Ob``ekt dlia raboty` po validatcii (proverki) vhodny`kh danny`kh
 * @property Zero_Cache $Cache Ob``ekt dlia dlia raboty` s keshem
 */
abstract class Zero_Model
{
    /**
     * Identifikator ob``ekta
     *
     * @var integer
     */
    protected $ID = 0;

    /**
     * Imia istochnika ob``ektov nasleduemogo classa
     *
     * @var string
     */
    protected $Source = '';

    /**
     * Massiv soderzhashchii` svoi`stva ob``ekta i ikh znacheniia
     * Esli svoi`stva v massive net znachit nikakaia rabota s nim ne provodilas`
     * Esli svoi`stvo v massive est` to ego znachenie opredeliaet ego tekushchee sostoianie
     *
     * @var array
     */
    private $_Props = [];

    /**
     * Massiv sostoianimi` svoi`stv
     *
     * - -1 izmnenennoe
     * - 1 zagruzhennoe iz istochnika
     *
     * @var array
     */
    private $_Props_Change = [];

    /**
     * Ob``ekt dlia raboty` s BD
     *
     * @var Zero_DB
     */
    private $_DB = null;

    /**
     * Ob``ekt dlia raboty` po validatcii (proverki) vhodny`kh danny`kh
     *
     * @var Zero_Validator
     */
    private $_VL = null;

    /**
     * Ob``ekt dlia dlia raboty` s keshem
     *
     * @var Zero_Cache
     */
    private $_Cache = null;

    /**
     * Configuration model i ee svoi`stv
     *
     * @var array
     */
    private static $_Config = [];

    /**
     * Spisok modelei` sozdanny`kh v peredelakh odnogo zaprosa
     *
     * @var array
     */
    private static $_Instance = [];

    /**
     * Konstrutkor classa
     * Initcializatciia identifikatora ob``ekta
     * 0 - novy`i` ob``ekt, ne sokhraneny`i` v BD
     * Esli $flag_load ustanovlen v true proishodit zagruzka svoi`stv ob``ekta iz BD
     *
     * @param integer $id identifikator ob``ekta
     * @param boolean $flag_load flag zagruzki ob``ekta
     * @param string $source istochnik
     */
    public function __construct($id = 0, $flag_load = false, $source = '')
    {
        $this->ID = intval($id);
        if ( $source )
            $this->Source = $source;
        else if ( '' == $this->Source )
            $this->Source = get_class($this);
        if ( 0 < $this->ID && $flag_load )
            $this->DB->Load('*');
    }

    /**
     * Fabrika po sozdaniiu ob``ektov.
     *
     * @param string $source imia istochnika model` kotoroi` sozdaetsia
     * @param int $id identifikator ob``ekta
     * @param bool $flag_load flag polnoi` zagruzki ob``ekta
     * @return Zero_Model
     * @throws Exception
     */
    public static function Make($source, $id = 0, $flag_load = false)
    {
        $model_name = $source;
        if ( isset(Zero_App::$Config->FactoryModel[$source]) )
            $model_name = Zero_App::$Config->FactoryModel[$source];
        return new $model_name($id, $flag_load, $source);
    }

    /**
     * Fabrika po sozdaniiu ob``ektov.
     *
     * Sokhraniaetsia v {$this->_Instance}
     *
     * @param string $source imia istochnika model` kotoroi` sozdaetsia
     * @param integer $id identifikator ob``ekta
     * @param bool $flag_load flag polnoi` zagruzki ob``ekta
     * @return Zero_Model
     */
    public static function Instance($source, $id = 0, $flag_load = false)
    {
        $index = $source . (0 < $id ? '_' . $id : '');
        if ( !isset(self::$_Instance[$index]) )
        {
            self::$_Instance[$index] = self::Make($source, $id, $flag_load);
            self::$_Instance[$index]->Init();
        }
        return self::$_Instance[$index];
    }

    /**
     * Fabrika po sozdaniiu ob``ektov.
     *
     * Rabotaet cherez sessiiu (Zero_Session).
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @param string $source imia istochnika model` kotoroi` sozdaetsia
     * @param integer $id identifikator ob``ekta
     * @param boolean|bool $flag_load flag polnoi` zagruzki ob``ekta
     * @return Zero_Model
     */
    public static function Factory($source, $id = 0, $flag_load = false)
    {
        $index = $source . (0 < $id ? '_' . $id : '');
        if ( !$result = Zero_Session::Get($index) )
        {
            $result = self::Make($source, $id, $flag_load);
            $result->Init();
            Zero_Session::Set($index, $result);
        }
        return $result;
    }

    /**
     * Save ob``ekta v reestr.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @param integer $flag opredeleniia indeksa ob``ekta (1 - uchity`vaia id, 0 - ne uchity`vaia id)
     */
    public function Factory_Set($flag = 0)
    {
        $index = $this->Source;
        if ( 0 < $flag )
            $index .= '_' . $this->ID;
        Zero_Session::Set($index, $this);
    }

    /**
     * Udalenie ob``ekta iz reestra.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @param integer $flag opredeleniia indeksa ob``ekta (1 - uchity`vaia id, 0 - ne uchity`vaia id)
     */
    public function Factory_Unset($flag = 0)
    {
        $index = $this->Source;
        if ( 0 < $flag )
            $index .= '_' . $this->ID;
        Zero_Session::Rem($index);
    }

    /**
     * Dinahmicheskii` fabrichny`i` metod dlia sozdanii ob``ekta cherez fabriku.
     */
    protected function Init()
    {
    }

    /**
     * Poluchenie identifikatora ob``ekta
     *
     * @return integer identifikator ob``ekta
     */
    public function Get_ID()
    {
        return $this->ID;
    }

    /**
     * Pereopredelenie identifikatora ob``ekta
     *
     * @param integer $id identifikator ob``ekta
     */
    public function Set_ID($id)
    {
        $this->ID = intval($id);
    }

    /**
     * Poluchenie imeni istochnika
     *
     * @return string imia istochnika
     */
    public function Get_Source()
    {
        return $this->Source;
    }

    /**
     * Poluchenie svoi`stv modeli i ikh znachenii`
     *
     * Znachenie $flag
     * - -1 poluchit` tol`ko izmenenny`e svoi`stva (ne sokhranenny`e)
     * - 0 poluchit` vse svoi`stva (po umolchaniiu)
     * - 1 poluchit` tol`ko ne izmenenny`e svoi`stva (sokhranenny`e)
     *
     * @param int $flag kakie svoi`stva poluchat`
     * @return array svoi`stva modeli i ikh znachenii`
     */
    public function Get_Props($flag = 0)
    {
        if ( 0 === $flag )
            return $this->_Props;
        $result = [];
        foreach ($this->_Props_Change as $prop => $flag_prop)
        {
            if ( $flag_prop == $flag )
                $result[$prop] = $this->_Props[$prop];
        }
        return $result;
    }

    /**
     * Ustanovka svoi`stv cherez massiv strogo so storony` istochnitka
     *
     * Esli $row ne peredan to proishodit ustavnoka statusa v 1 dlia vsekh svoi`stv
     * Inache tol`ko dlia tekh kotory`e peredany`
     *
     * @param array $row massiv svoi`stv i ikh znachenii`
     * @return bool
     */
    public function Set_Props($row = [])
    {
        if ( 0 == count($row) )
        {
            foreach (array_keys($this->_Props_Change) as $prop)
            {
                $this->_Props_Change[$prop] = 1;
            }
            return true;
        }

        $config = $this->Get_Config_Prop();
        foreach ($row as $prop => $value)
        {
            //  Personal`ny`i` ili algoritmichny`i` setter
            if ( method_exists($this, $method = 'Set_' . $prop) )
            {
                $this->$method($value);
                continue;
            }
            //  Svoi`stva modeli
            if ( 'S' == $config[$prop]['DB'] && !is_array($value) )
            {
                if ( $value )
                    $this->_Props[$prop] = explode(',', $value);
                else
                    $this->_Props[$prop] = [];
            }
            else
                $this->_Props[$prop] = $value;
            $this->_Props_Change[$prop] = 1;
        }
        return true;
    }

    /**
     * Configuration model
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Model($Model)
    {
        return [];
    }

    /**
     * Poluchenie konfiguratcii modeli
     *
     * @return array dvumerny`i` assotciativny`i` massiv konfiguratcii
     */
    public function Get_Config_Model()
    {
        if ( !isset(self::$_Config[$this->Source]['model']) )
        {
            self::$_Config[$this->Source]['model'] = static::Config_Model($this);
            self::$_Config[$this->Source]['model']['Comment'] = Zero_I18n::T($this->Source, 'model', $this->Source);
        }
        return self::$_Config[$this->Source]['model'];
    }

    /**
     * Configuration links many to many
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Link($Model)
    {
        return [];
    }

    /**
     * Poluchenie konfiguratcii sviazei` modeli
     *
     * @return array dvumerny`i` assotciativny`i` massiv konfiguratcii
     */
    public function Get_Config_Link()
    {
        return static::Config_Link($this);
    }

    /**
     * The configuration language properties
     *
     * @param Zero_Model $Model The exact working model
     * @return string
     */
    protected static function Config_Prop_Lang($Model)
    {
        return '*';
    }

    /**
     * Poluchenie bazovoi` konfiguratciia svoi`stv
     *
     * @return string
     */
    public function Get_Config_Prop_Lang()
    {
        return static::Config_Prop_Lang($this);
    }

    /**
     * The configuration properties
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Prop($Model)
    {
        return [];
    }

    /**
     * Poluchenie bazovoi` konfiguratciia svoi`stv
     *
     * - 'DB'=> 'I, F, T, E, S, D, B'
     * - 'IsNull'=> 'YES, NO'
     * - 'Default'=> 'mixed'
     * - 'Comment'=> 'Comment'
     * - 'Value'=> [] 'Values ​​for Enum, Set'
     *
     * @return array
     */
    public function Get_Config_Prop()
    {
        if ( !isset(self::$_Config[$this->Source]['props']) )
        {
            foreach (static::Config_Prop($this) as $prop => $row)
            {
                $index = "model prop {$prop}";
                $row['Comment'] = Zero_I18n::T($this->Source, $index, $prop);

                if ( 'S' == $row['DB'] || 'E' == $row['DB'] )
                    $row['Value'] = Zero_DB::Sel_EnumSet($this, $prop);
                self::$_Config[$this->Source]['props'][$prop] = $row;
            }
        }
        return self::$_Config[$this->Source]['props'];
    }

    /**
     * Dynamic configuration properties for the filter
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario
     * @return array
     */
    protected static function Config_Filter($Model, $scenario = '')
    {
        return [];
    }

    /**
     * Dynamic configuration properties for the filter
     *
     * - 'Filter'=> 'Select, Radio, Checkbox, DateTime, Link, LinkMore, Number, Text, Textarea, Content
     * - 'Search'=> 'Number, Text'
     * - 'Sort'=> 'false, true'
     *
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Filter($scenario = '')
    {
        return static::Config_Filter($this, $scenario);
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
        return [];
    }

    /**
     * Dynamic configuration properties for the Grid
     *
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Grid($scenario = '')
    {
        $props = static::Config_Grid($this, $scenario);
        foreach ($props as $prop => $row)
        {
            $props[$prop]['Comment'] = Zero_I18n::T($this->Source, 'model prop ' . $prop, $prop);
        }
        return $props;
    }

    /**
     * Dynamic configuration properties for the form
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario forms
     * @return array
     */
    protected static function Config_Form($Model, $scenario = '')
    {
        return [];
    }

    /**
     * Dynamic configuration properties for the form
     *
     * - 'Form'=> [
     *      Number, Text, Select, Radio, Checkbox, Textarea, Date, Time, DateTime, Link,
     *      Hidden, ReadOnly, Password, File, FileData, Img, ImgData, Content', LinkMore
     *      ]
     *
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Form($scenario = '')
    {
        $props = static::Config_Form($this, $scenario);
        foreach ($props as $prop => $com)
        {
            $props[$prop]['Comment'] = Zero_I18n::T($this->Source, 'model prop ' . $prop, $prop);
        }
        return $props;
    }

    /**
     * Poluchenie ssy`lki na ob`ekt Zero_DB
     *
     * @return Zero_DB
     */
    public function Get_DB()
    {
        if ( !is_object($this->_DB) )
            $this->_DB = new Zero_DB($this);
        return $this->_DB;
    }

    /**
     * Poluchenie ssy`lki na ob``ekt validatcii
     *
     * @return Zero_Validator
     */
    public function Get_VL()
    {
        if ( !is_object($this->_VL) )
            $this->_VL = new Zero_Validator($this);
        return $this->_VL;
    }

    /**
     * Poluchenie ssy`lki na ob``ekt keshirovaniia
     *
     * @return Zero_Cache
     */
    public function Get_Cache()
    {
        if ( !is_object($this->_Cache) )
            $this->_Cache = new Zero_Cache($this);
        return $this->_Cache;
    }

    /**
     * Poluchenie znacheniia abstraktnogo svoi`stva
     *
     * Universal`ny`i` getter pozvoliaiushchii` obernut` vse priamy`e obrashcheniia
     * k abstraktny`m svoi`stvam v ikh personal`ny`i` getter
     *
     * @param string $prop abstraktnoe svoi`stvo nasleduemogo classa
     * @return mixed
     */
    public function __get($prop)
    {
        //  Personal`ny`i` ili algoritmichny`i` getter
        if ( method_exists($this, $method = 'Get_' . $prop) )
            return $this->$method();
        //  chitaem svoi`stvo
        if ( array_key_exists($prop, $this->_Props) )
            return $this->_Props[$prop];
        //  novy`i` ob``ekt ne sushchestvuiushchii` v BD i potomu ne imeiushchii` nikakogo znacheniia
        if ( 0 == $this->ID || !isset($this->Get_Config_Prop()[$prop]) )
            return null;
        //  svoi`stvo pustoe, ne zagruzhennoe iz BD
        Zero_Logs::Set_Message('#{LOAD PROP} load prop "' . $prop . '" for model "' . get_class($this) . '"', 'warning');
        $this->_Props[$prop] = Zero_DB::Sel_Filed($this->ID, $this->Source, $prop);
        return $this->_Props[$prop];
    }

    /**
     * Ustavnoka svoi`stv so storony` vneshnego mira (pol`zovatelia)
     *
     * Universal`ny`i` setter pozvoliaiushchii` obernut` vse priamy`e obrashcheniia
     * k abstraktny`m svoi`stvam v ikh personal`ny`i` setter
     *
     * @param string $prop abstraktnoe svoi`stvo nasleduemogo classa
     * @param mixed $value znachenie e`togo svoi`stva
     * @return boolean
     */
    public function __set($prop, $value)
    {
        //  Personal`ny`i` ili algoritmichny`i` setter
        if ( method_exists($this, $method = 'Set_' . $prop) )
            return $this->$method($value);
        //  Svoi`stva modeli
        if ( !isset($this->_Props[$prop]) || $this->_Props[$prop] != $value )
        {
            $this->_Props[$prop] = $value;
            $this->_Props_Change[$prop] = -1;
        }
        return true;
    }

    /**
     * Perekhvat metodov.
     *
     * Otslezhivaet obrashchenie k nesushchestvuiushchim metodam.
     * Ishchet metod 'Call_' + MethodName<br>
     * Ishchet otnoshenie (sviaz`) s roditelem. Esli est` sozdaet ego i vozvrashchaet ob``ekt
     *
     * @param string $method abstraktny`i` metod
     * @param array $params parametry` metoda
     * @return mixed|Zero_Model
     * @throws Exception
     */
    public function __call($method, $params)
    {
        //  standartny`i` metod peregruzki
        if ( method_exists($this, $method1 = 'Call_' . $method) )
            return $this->$method1($params);
        //  rabota so sviazanny`m roditel`skim ob``etom cherez svoi`tsvo sviazi (odin ko mnogim)
        if ( isset($this->Get_Config_Prop()[$method]) )
            return self::Make(zero_relation($method), $this->$method, !empty($params[0]));
        throw new Exception('metod not found: ' . get_class($this) . ' -> ' . $method);
    }
}
