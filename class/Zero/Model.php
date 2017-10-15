<?php

/**
 * Базовая абстрактная модель (Компонент).
 *
 * - Реализует специализированную и абстраткную обработку свойств модели
 * - Агрегатор различных компонентов по работе с наследуемым объектом
 * - Использует паттерн Композицию с делегированием
 * - Работа со свойствами проишодит через методы перегрузки
 *
 * Инкапуслирует в себе следующие компоненты системы:
 * - Валидатор свойств наследуемого объекта при его изменении
 * - Система объектного (целевого) кеширования
 * - Component on interaction and working with the database in the philosophy of ORM
 *
 * @package Zero.Component Базовая абстрактная модель
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 *
 * @property int $ID
 * @property string $Source
 * @property Zero_AR $AR
 * @property Zero_Cache $CH
 * @property Zero_Validator $VL
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
     * Объект длиа работы с БД в контексте модели
     *
     * @var Zero_AR
     */
    private $_AR = null;

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
    private $_CH = null;

    /**
     * Configuration model i ee svoi`stv
     *
     * @var array
     */
    private static $_Config = [];

    /**
     * Konstrutkor classa
     * Initcializatciia identifikatora ob``ekta
     * 0 - novy`i` ob``ekt, ne sokhraneny`i` v BD
     * Esli $flag_load ustanovlen v true proishodit zagruzka svoi`stv ob``ekta iz BD
     *
     * @param integer $id identifikator ob``ekta
     * @param boolean $flag_load flag zagruzki ob``ekta
     */
    public function __construct($id = 0, $flag_load = false)
    {
        $this->ID = intval($id);
        if ( '' == $this->Source )
            $this->Source = get_class($this);
        if ( 0 < $this->ID && $flag_load )
            $this->Load();
    }

    /**
     * Фабрика по созданию объектов.
     *
     * @param string $class_name имиа источника модель которой создаетсиа
     * @param int $id идентификатор объекта
     * @param bool $flag_load flag полной загрузки объекта
     * @return Zero_Model
     * @throws Exception
     */
    public static function Makes($class_name, $id = 0, $flag_load = false)
    {
        if ( '' == $class_name )
            throw new Exception("Модель не указана", 409);
        if ( false == Zero_App::Autoload($class_name) )
            throw new Exception("Модель '{$class_name}' отсутсвует в приложении", 409);
        return new $class_name($id, $flag_load);
    }

    /**
     * Save ob``ekta v reestr.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @param bool $flag opredeleniia indeksa ob``ekta (1 - uchity`vaia id, 0 - ne uchity`vaia id)
     */
    public function Factory_Set($flag = false)
    {
        $index = get_class($this);
        if ( $flag )
            $index .= '_' . $this->ID;
        Zero_Session::Set($index, $this);
    }

    /**
     * Udalenie ob``ekta iz reestra.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @param bool $flag opredeleniia indeksa ob``ekta (1 - uchity`vaia id, 0 - ne uchity`vaia id)
     */
    public function Factory_Unset($flag = false)
    {
        $index = get_class($this);
        if ( $flag )
            $index .= '_' . $this->ID;
        Zero_Session::Rem($index);
    }

    /**
     * Poluchenie identifikatora ob``ekta
     *
     * @return integer identifikator ob``ekta
     */
    protected function Get_ID()
    {
        return $this->ID;
    }

    /**
     * Pereopredelenie identifikatora ob``ekta
     *
     * @param integer $id identifikator ob``ekta
     */
    protected function Set_ID($id)
    {
        $this->ID = intval($id);
    }

    /**
     * Poluchenie imeni istochnika
     *
     * @return string imia istochnika
     */
    protected function Get_Source()
    {
        return $this->Source;
    }

    /**
     * Получение свойств модели и их значений
     *
     * $flag
     * - -1 получить только измененные свойства (не сохраненные)
     * - 0 получить все свойства (по умолчанию)
     * - 1 получить только не измененные свойства (сохраненные)
     *
     * @param int $flag
     * @return array свойства
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
    protected function Set_Props($row = [])
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
            //  Персональный или алгоритмичный сеттер
            if ( method_exists($this, $method = 'Set_' . $prop) )
            {
                $this->$method($value);
                continue;
            }
            //  Свойства модели
            if ( isset($config[$prop]) && 'S' == $config[$prop]['DB'] && !is_array($value) )
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
     * The configuration properties
     *
     * @param Zero_Model $Model The exact working model
     * @param string $scenario scenario
     * @return array
     */
    protected static function Config_Prop($Model, $scenario = '')
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
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Prop($scenario = '')
    {
        $index = get_class($this);
        if ( !isset(self::$_Config[$index]['props']) )
        {
            foreach (static::Config_Prop($this, $scenario) as $prop => $row)
            {
                $row['Comment'] = Zero_I18n::Model($index, $prop);
                self::$_Config[$index]['props'][$prop] = $row;
            }
        }
        return self::$_Config[$index]['props'];
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
     * - 'Filter'=> 'Select, Radio, Checkbox, Datetime, Link, Linkmore, Number, Text, Textarea, Content
     * - 'Search'=> 'Number, Text'
     * - 'Sort'=> 'false, true'
     *
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Filter($scenario = '')
    {
        $props = static::Config_Filter($this, $scenario);
        $propsBase = $this->Get_Config_Prop();
        foreach ($props as $prop => $row)
        {
            if ( isset($propsBase[$prop]) )
                $props[$prop] = array_replace($propsBase[$prop], $row);
            else
            {
                $props[$prop]['Comment'] = Zero_I18n::Model(get_class($this), $prop);
            }
        }
        return $props;
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
        $propsBase = $this->Get_Config_Prop();
        foreach ($props as $prop => $row)
        {
            if ( isset($propsBase[$prop]) )
                $props[$prop] = array_replace($propsBase[$prop], $row);
            else
            {
                $props[$prop]['Comment'] = Zero_I18n::Model(get_class($this), $prop);
            }
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
     *      Number, Text, Select, Radio, Checkbox, Textarea, Date, Time, Datetime, Link,
     *      Hidden, Readonly, Password, File, Filedata, Img, ImgData, Content', Linkmore
     *      ]
     *
     * @param string $scenario scenario
     * @return array
     */
    public function Get_Config_Form($scenario = '')
    {
        $props = static::Config_Form($this, $scenario);
        $propsBase = $this->Get_Config_Prop();
        foreach ($props as $prop => $row)
        {
            if ( isset($propsBase[$prop]) )
                $props[$prop] = array_replace($propsBase[$prop], $row);
            else
            {
                $props[$prop]['Comment'] = Zero_I18n::Model(get_class($this), $prop);
            }
        }
        return $props;
    }

    /**
     * Получение ссылки на обьект Zero_DB
     *
     * @return Zero_AR
     */
    protected function Get_AR()
    {
        if ( !is_object($this->_AR) )
            $this->_AR = new Zero_AR($this);
        return $this->_AR;
    }

    /**
     * Poluchenie ssy`lki na ob``ekt validatcii
     *
     * @return Zero_Validator
     */
    protected function Get_VL()
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
    protected function Get_CH()
    {
        if ( !is_object($this->_CH) )
            $this->_CH = new Zero_Cache($this);
        return $this->_CH;
    }

    /**
     * Загрузка модели
     *
     * @param string $properties Загружаемые свойства (по умолчанию все).
     * @return array загруженные свойства и их значения
     */
    public function Load($properties = '*')
    {
        if ( 0 >= $this->ID )
        {
            Zero_Logs::Set_Message_Error("Error Load: {$this->Source} SqlWhere is empty");
            return [];
        }
        if ( is_array($properties) )
            $sql_select = implode(', ', $properties);
        else
            $sql_select = $properties;
        $sql = "SELECT {$sql_select} FROM {$this->Source} WHERE ID = {$this->ID}";
        $row = Zero_DB::Select_Row($sql);
        if ( 0 < count($row) )
            $this->Set_Props($row);
        return $row;
    }

    /**
     * Zagruzka vy`borochny`kh svoi`stv ob``ekta, libo tcelikom iz BD.
     *
     * Esli ob``ekt v BD ne by`l nai`den $this->ID stanovitsia ravny`m 0
     *
     * @param string $properties stroka zagruzhaemy`kh svoi`stv cherez zaiapiatuiu ('Name, Price, Description')
     * @return bool|array
     */
    public function Load_Language($properties = '*')
    {
        if ( 0 == $this->ID )
            return [];
        $sql = "SELECT {$properties} FROM {$this->Source}Language WHERE {$this->Source}_ID = {$this->ID} AND Lang = '" . ZERO_LANG . "'";
        $row = Zero_DB::Select_Row($sql);
        unset($row['Lang']);
        unset($row[$this->Source . '_ID']);
        unset($row['ID']);
        unset($row['Id']);
        unset($row['id']);
        $this->Set_Props($row);
        return $row;
    }

    /**
     * Сохранение модели
     *
     * @return bool
     */
    public function Save()
    {
        //  Подготовка не бинарных данных  для сохранения в БД
        $sql_update = [];
        $prop_list = $this->Get_Config_Prop();
        unset($prop_list['ID']);
        unset($prop_list['Id']);
        unset($prop_list['id']);
        foreach ($this->Get_Props(-1) as $prop => $value)
        {
            if ( !isset($prop_list[$prop]) || (isset($prop_list['AR']) && false == $prop_list['AR']) )
                continue;
            if ( $prop_list[$prop]['Form'] == 'Img' || $prop_list[$prop]['Form'] == 'File' )
                continue;
            $method = "Esc" . $prop_list[$prop]['DB'];
            $sql_update[] = '`' . $prop . '` = ' . Zero_DB::$method($value);
        }
        // INSERT
        if ( 0 == $this->ID )
        {
            if ( 0 == count($sql_update) )
                $sql_update[] = 'ID = NULL';
            $sql = "INSERT " . $this->Source . " SET " . implode(', ', $sql_update);
            $this->ID = Zero_DB::Insert($sql);
            if ( !$this->ID )
                return false;
        } // UPDATE
        else if ( 0 < count($sql_update) )
        {
            $sql = "UPDATE " . $this->Source . " SET " . implode(', ', $sql_update) . " WHERE ID = {$this->ID}";
            if ( false === Zero_DB::Update($sql) )
                return false;
        }

        // BINARY DATA
        $sql_update = [];
        foreach ($this->Get_Props(-1) as $prop => $value)
        {
            if ( !isset($prop_list[$prop]) || (isset($prop_list['AR']) && false == $prop_list['AR']) )
                continue;
            if ( $prop_list[$prop]['Form'] != 'Img' && $prop_list[$prop]['Form'] != 'File' )
                continue;
            if ( !$this->$prop )
            {
                $sql_update[$prop] = "`" . $prop . "` = NULL";
                if ( isset($prop_list[$prop . 'B']) )
                    $sql_update[$prop] = "`" . $prop . "B` = NULL";
            }
            if ( 0 === $_FILES[$prop]['error'] )
            {
                // V fai`lovoi` sisteme
                $file = strtolower($this->Source) . '/' . Helper_File::Get_Path_Cache($this->ID) . '/' . $this->ID . '/' . $_FILES[$prop]['name'];
                $path = ZERO_PATH_DATA . '/' . $file;
                if ( file_exists($path) )
                {
                    $pos = strrpos($_FILES[$prop]['name'], ".", -1);
                    $_FILES[$prop]['name'] = substr($_FILES[$prop]['name'], 0, $pos) . '_' . $prop . substr($_FILES[$prop]['name'], $pos);
                    $file = strtolower($this->Source) . '/' . Helper_File::Get_Path_Cache($this->ID) . '/' . $this->ID . '/' . $_FILES[$prop]['name'];
                    $path = ZERO_PATH_DATA . '/' . $file;
                }
                if ( !is_dir(dirname($path)) )
                    mkdir(dirname($path), 0777, true);
                if ( !rename($_FILES[$prop]['tmp_name'], $path) )
                {
                    Zero_Logs::Set_Message_Error('Error Copy File');
                    continue;
                }
                $sql_update[$prop] = "`" . $prop . "` = '{$file}'";
                if ( isset($prop_list[$prop . 'B']) )
                    $sql_update[$prop] = "`" . $prop . "B` = '" . Zero_DB::EscB(file_get_contents($path)) . "'";
                $this->$prop = $file;
            }
        }
        $flag = true;
        if ( 0 < count($sql_update) )
        {
            $sql = "UPDATE " . $this->Source . " SET " . implode(', ', $sql_update) . " WHERE ID = {$this->ID}";
            $flag = Zero_DB::Update($sql);
        }
        //
        $this->Set_Props();
        return false !== $flag;
    }

    /**
     * Удаление модели
     *
     * @return bool
     */
    public function Remove()
    {
        if ( 0 == $this->ID )
            return true;
        //  Удаляем кеш
        $this->Get_CH()->Reset();
        // Удаляем бинарные данные
        $path = ZERO_PATH_DATA . '/' . strtolower($this->Source) . '/' . Helper_File::Get_Path_Cache($this->ID) . '/' . $this->ID;
        if ( is_dir($path) )
        {
            Helper_File::Folder_Remove($path);
        }
        // Удаляем из сессии
        $this->Factory_Unset(1);
        // Удаляем из БД
        Zero_DB::Update("DELETE FROM {$this->Source} WHERE ID = {$this->ID}");
        return true;
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
        //        Zero_Logs::Set_Message_Notice('#{LOAD PROP} load prop "' . $prop . '" for model "' . get_class($this) . '"');
        $arr = Zero_DB::Select_Row("SELECT `{$prop}` FROM {$this->Source} WHERE ID = {$this->ID}");
        $this->_Props[$prop] = isset($arr[$prop]) ? $arr[$prop] : null;
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
        if ( !isset($this->_Props[$prop]) && $value )
        {
            $this->_Props[$prop] = $value;
            $this->_Props_Change[$prop] = -1;
        }
        else if ( isset($this->_Props[$prop]) && $this->_Props[$prop] != $value )
        {
            if ( $this->_Props[$prop] || $value )
            {
                $this->_Props[$prop] = $value;
                $this->_Props_Change[$prop] = -1;
            }
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
        {
            if ( !$this->$method )
                return null;
            return Zero_DB::Select_Field("SELECT {$params[0]} FROM " . zero_relation($method) . " WHERE ID = " . intval($this->$method));
            //            return self::Make(zero_relation($method), $this->$method, !empty($params[0]));
        }
        throw new Exception('metod not found: ' . get_class($this) . ' -> ' . $method, 409);
    }
}
