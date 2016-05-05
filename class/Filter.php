<?php

/**
 * Фильтр.
 *
 * Fil`try`, poisk, sortirovka, nomer tekushchei` stranitcy`.
 * Fil`tr vedetsia po poliam sviazei`, perechisleniiam, mnozhestvam i vremenny`m (data i vremia).
 * Poik i sortirovka po vsem ostal`ny`m chislovy`m i strokovy`m poliam.
 * Reazlizuet fil`tratciiu pri vy`vode ob``ektov v gride.
 * V forme vy`stupaet kak spravochnik variantov znachenii` dlia svoi`stv.
 *
 * @package Zero.Component Фильтр
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
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
     * Конфигурация свойств длиа фильтров.
     *
     * - [$prop]['Comment'] Nazvanie fil`tra
     * - [$prop]['Form'] Tip fil`tra
     * - [$prop]['Visible'] Vidimost` fil`tra
     * - [$prop]['Value'] Ustanovlennoi` znachenie
     * - [$prop]['List'] Spisok vozmozhny`kh znachenii`
     *
     * @var array
     */
    protected $Filter = [];

    /**
     * Свойства по которым ищем
     *
     * - Список свойств
     * - Их типы
     * - Их Значения
     *
     * @var array
     */
    protected $Search = [];

    /**
     * Ссылки для запроса в БД
     *
     * Формируютсиа на основе переданных свойтсв
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
    public $Page = 0;

    /**
     * Kolichetsvo elementov na stranitce
     *
     * @var integer
     */
    public $Page_Item;

    /**
     * Статус установленного фильтра
     *
     * @var boolean
     */
    public $IsSet = false;

    /**
     * Sozdanie i initcializatciia fil`tra.
     *
     * @param Zero_Model $Model Delegirovannaia model` dlia kotoroi` sozdaiutsia fil`try`
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
        $this->Reset();
    }

    /**
     * Создание и инициализация фильтра.
     *
     * Работает через сессию (Zero_Session)
     *
     * @param Zero_Model $Model Делегированная модель для которой создается фильтр
     * @return Zero_Filter
     */
    public static function Factory($Model)
    {
        $index = 'Filter' . '_' . get_class($Model);
        if ( !$result = Zero_Session::Get($index) )
        {
            $result = new self($Model);
            Zero_Session::Set($index, $result);
        }
        return $result;
    }

    /**
     * Добавление фильтра свиази (с небольшим числом объектов)
     *
     * @param string $prop
     * @param array $row Конфигурация фильтра для указанного свойства
     * @param int $is_visible Видимость в представлении
     * @param mixed $load Загрузка фильтра (нет, да, массив вариантов)
     * @return bool
     */
    public function Add_Filter_Link($prop, $row, $is_visible = 0, $load = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Link';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = 0;
        $this->Filter[$prop]['List'] = [];
        if ( 'Lang' == $prop )
        {
            $this->Filter[$prop]['List'] = $this->FL_Lang();
        }
        else if ( method_exists($this->Model, $method = 'FL_' . $prop) )
        {
            $this->Filter[$prop]['List'] = $this->Model->$method();
        }
        else if ( $load )
        {
            if ( isset($row['List']) )
                $this->Filter[$prop]['List'] = $row['List'];
            else
            {
                $this->Filter[$prop]['List'] = Zero_DB::Select_List_Index("SELECT ID, Name FROM `" . zero_relation($prop) . "` ORDER BY `Name`");
            }
        }
        return true;
    }

    /**
     * Dobavlenie fil`tra perchisleniia
     *
     * @param string $prop Svoi`stvo perechisleniia dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - Avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Select($prop, $row, $is_visible = 0, $load = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Select';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( 'Lang' == $prop )
        {
            $this->Filter[$prop]['List'] = $this->FL_Lang();
        }
        else if ( method_exists($this->Model, $method = 'FL_' . $prop) )
        {
            $this->Filter[$prop]['List'] = $this->Model->$method();
        }
        else if ( $load )
        {
            if ( isset($row['List']) )
                $this->Filter[$prop]['List'] = $row['List'];
            else
            {
                $data = Zero_I18n::Model(get_class($this->Model), $this->Model->Source . ' ' . $prop . ' options');
                if ( !is_array($data) )
                    $data = Zero_I18n::Model(get_class($this->Model), $prop . ' options');
                //
                if ( is_array($data) )
                    $this->Filter[$prop]['List'] = $data;
            }
        }
        return true;
    }

    /**
     * Zagruzka fil`tra iazy`kov
     *
     * @return array
     */
    public static function FL_Lang()
    {
        $result = [];
        foreach (Zero_App::$Config->Language as $key => $name)
        {
            $result[$key] = $name;
        }
        return $result;
    }

    /**
     * Dobavlenie fil`tra perchisleniia
     *
     * @param string $prop Svoi`stvo perechisleniia dlia kotorogo budet sozdan fil`tr.
     * @param integer $is_visible Vidimost` fil`tra (1 - otobrazhaetsia, 0 - ne otobrazhaetsia po umolchaniiu)
     * @param mixed $load 1 - avtonomnaia zagruzka fil`tra, 0 - bez zagruzki po umolchaniiu, array - peredanny`e varianty` (spisok ID => Name)
     * @return bool
     */
    public function Add_Filter_Radio($prop, $row, $is_visible = 0, $load = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Radio';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = [];
        if ( 'Lang' == $prop )
        {
            $this->Filter[$prop]['List'] = $this->FL_Lang();
        }
        else if ( method_exists($this->Model, $method = 'FL_' . $prop) )
        {
            $this->Filter[$prop]['List'] = $this->Model->$method();
        }
        else if ( $load )
        {
            if ( isset($row['List']) )
                $this->Filter[$prop]['List'] = $row['List'];
            else
            {
                $data = Zero_I18n::Model(get_class($this->Model), $this->Model->Source . ' ' . $prop . ' options');
                if ( !is_array($data) )
                    $data = Zero_I18n::Model(get_class($this->Model), $prop . ' options');
                //
                if ( is_array($data) )
                    $this->Filter[$prop]['List'] = $data;
            }
        }
        return true;
    }

    public function Add_Filter_Null($prop, $row, $is_visible = 0, $load = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Null';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = Zero_I18n::Model('Zero_General', 'FlagOptions');
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
    public function Add_Filter_Check($prop, $row, $is_visible = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Check';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = '';
        $this->Filter[$prop]['List'] = Zero_I18n::Model('Zero_General', 'FlagOptions');
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
    public function Add_Filter_Checkbox($prop, $row, $is_visible = 0, $load = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Checkbox';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = [];
        $this->Filter[$prop]['List'] = [];
        if ( 'Lang' == $prop )
        {
            $this->Filter[$prop]['List'] = $this->FL_Lang();
        }
        else if ( method_exists($this->Model, $method = 'FL_' . $prop) )
        {
            $this->Filter[$prop]['List'] = $this->Model->$method();
        }
        else if ( $load )
        {
            if ( isset($row['List']) )
                $this->Filter[$prop]['List'] = $row['List'];
            else
            {
                $data = Zero_I18n::Model(get_class($this->Model), $this->Model->Source . ' ' . $prop . ' options');
                if ( !is_array($data) )
                    $data = Zero_I18n::Model(get_class($this->Model), $prop . ' options');
                //
                if ( is_array($data) )
                    $this->Filter[$prop]['List'] = $data;
            }
        }
        return true;
    }

    /**
     * @param string $prop Свойство даты и времени для которого будет создан фильтр.
     * @param array $row Массив конфигурации свойства
     * @param int $is_visible Видимость фильтра (1 - отображаетсиа, 0 - не отображаетсиа по умолчанию)
     * @return bool
     */
    public function Add_Filter_Datetime($prop, $row, $is_visible = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Datetime';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = ['', ''];
        return true;
    }

    /**
     * @param string $prop Свойство даты и времени для которого будет создан фильтр.
     * @param array $row Массив конфигурации свойства
     * @param int $is_visible Видимость фильтра (1 - отображаетсиа, 0 - не отображаетсиа по умолчанию)
     * @return bool
     */
    public function Add_Filter_Date($prop, $row, $is_visible = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Date';
        $this->Filter[$prop]['Visible'] = $is_visible;
        $this->Filter[$prop]['Value'] = ['', ''];
        return true;
    }

    /**
     * @param string $prop Свойство даты и времени для которого будет создан фильтр.
     * @param array $row Массив конфигурации свойства
     * @param int $is_visible Видимость фильтра (1 - отображаетсиа, 0 - не отображаетсиа по умолчанию)
     * @return bool
     */
    public function Add_Filter_Time($prop, $row, $is_visible = 0)
    {
        $this->Filter[$prop] = $row;
        $this->Filter[$prop]['Form'] = 'Time';
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
     * @param string $propNaqme получить фильтр по конкретному свойству
     * @return array
     */
    public function Get_Filter($propNaqme = '')
    {
        if ( $propNaqme )
            return $this->Filter[$propNaqme];
        else
            return $this->Filter;
    }

    /**
     * Dobavlenie poiska po chislam
     *
     * @param string $prop Svoi`stvo po kotoromu vozmozhen poisk
     * @return bool
     */
    public function Add_Search_Number($prop, $row)
    {
        $this->Search['List'][$prop] = $row;
        $this->Search['List'][$prop]['Form'] = 'Number';
        $this->Search['Value'][$prop] = '';
        return true;
    }

    /**
     * Dobavlenie poiska po strokam i tekstu
     *
     * @param string $prop Svoi`stvo po kotoromu vozmozhen poisk
     * @return bool
     */
    public function Add_Search_Text($prop, $row)
    {
        $this->Search['List'][$prop] = $row;
        $this->Search['List'][$prop]['Form'] = 'Text';
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
    public function Add_Sort($prop, $row)
    {
        $this->Sort['List'][$prop] = $row;
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
     * Сброс фильтра
     *
     * Если свойство не указано сбрасываетсиа весь фильтр
     *
     * @return Zero_Filter
     */
    public function Reset()
    {
        //  фильтры
        $this->Filter = [];
        //  поиск
        $this->Search = ['List' => [], 'Value' => []];
        $this->Add_Search_Text('ALL_PROPS', ['Comment' => Zero_I18n::Model('Zero_Model', 'Property all')]);
        //  сортировка
        $this->Sort = ['List' => [], 'Value' => []];
        //
        $this->Page = 0;
        $this->Page_Item = Zero_App::$Config->View_PageItem;
        $this->IsSet = false;

        // Инициализация фильтра
        $properties = $this->Model->Get_Config_Filter(get_class($this));
        foreach ($properties as $prop => $row)
        {
            $method = 'Add_Filter_' . $row['Form'];
            if ( method_exists($this, $method) )
            {
                if ( isset($row['Visible']) && true == $row['Visible'] )
                    $row['Visible'] = 1;
                else
                    $row['Visible'] = 0;
                $this->$method($prop, $row, $row['Visible'], 1);
                //
                if ( isset($row['DB']) && $row['DB'] == 'D' )
                    $this->Add_Sort($prop, $row);
            }
            else if ( isset($row['DB']) )
            {
                $method = '';
                if ( $row['DB'] == 'I' || $row['DB'] == 'F' )
                    $method = 'Add_Search_Number';
                else if ( $row['DB'] == 'T' )
                    $method = 'Add_Search_Text';

                if ( method_exists($this, $method) )
                    $this->$method($prop, $row);

                if ( $method != '' )
                {
                    $this->Add_Sort($prop, $row);
                }
            }
        }
        if ( isset($properties['Date']) )
            $this->Set_Sort('Date', 'DESC');
        if ( isset($properties['Sort']) )
            $this->Set_Sort('Sort', 'ASC');
        return $this;
    }
}
