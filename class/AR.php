<?php

/**
 * Component. Inkapsuliruet v sebe vsiu rabotu s BD.
 *
 * - Inkapsuliruet v sebe vsiu priamuiu rabotu s BD
 * - Realizovan sbor statistiki ob e`ffektivnosti raboty` s BD
 * - Zamery` vremeni vy`polneniia zaprosov
 * - Proverka vhodiashchikh parametrov zaprosov na bezopasnost`
 * - Realizuet rabotu s BD na urovne ORM
 * - Konstrutkor po postroeniiu uslovii` zaprosov
 *
 * Gruppy` metodov:
 * - Metod initcializatcii soedineniia s istochnikom.  Init()
 * - Metody` priamy`kh zaprosov k istochniku s formirovaniem rezul`tata v nuzhno vide. Imeiut prefiks Query_
 * - Metody` proverok vhodny`kh danny`kh dlia zaprosov. Odnobukvenny`e (I, F, T, E, S, D, B)
 * - Metody` formirovaniia usloviia dlia posleduiushchikh zaprosov (rabotaet ot modeli). Imeiut prefiks Sql_
 * - Metody` vy`borki danny`kh v nuzhnom vide po zadanny`m usloviiam (rabotaet ot modeli). Imeiut prefiks Select_
 * - Metody` zagruzki, obnovleniia, vstavki i udaleniia po zadanny`m usloviiam (rabotaet ot modeli). Imeiut prefiks Load_, Update_, Insert_, Delete_
 * - Metody` kotory`e keshiruiut rezul`taty` zaprosov k BD imeiut konechny`i` suffiks _Cache
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_AR
{
    /**
     * Массив параметров запроса к источнику.
     *
     * @var array
     */
    protected $Params = [];

    /**
     * Объект, с которым мы работаем
     *
     * @var Zero_Model
     */
    protected $Model = null;

    /**
     * Konstruktor classa
     *
     * @param Zero_Model $Model
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    /**
     * Kompiliatciia uslovii` zaprosa
     *
     * @return string
     */
    public function Sql_Where_Compilation()
    {
        if ( !isset($this->Params['Where']) )
            return '1';
        $sql = '';
        $sql_row = '';
        $flag_separator = false;
        $where_count = count($this->Params['Where']);
        foreach ($this->Params['Where'] as $key => $where)
        {
            if ( 'OR' == $where || 'AND' == $where || 'XOR' == $where )
            {
                $sql .= $sql_row;
                //
                if ( ($key + 1) < $where_count )
                {
                    if ( false == $flag_separator )
                    {
                        if ( '' == $sql )
                            $sql .= " 1 AND ( ";
                        else if ( '' != $sql_row )
                            $sql .= "\n\t\t\t" . $where . " ( ";
                    }
                    else
                    {
                        if ( '' == $sql_row )
                            $sql .= "1 )\n\t\t\t" . $where . " ( ";
                        else
                            $sql .= ")\n\t\t\t" . $where . " ( ";
                    }
                    $flag_separator = true;
                }
                else
                {
                    if ( '' == $sql_row )
                        $sql .= "1 )";
                    else
                        $sql .= ")";
                    $flag_separator = false;
                }
                $sql_row = '';
            }
            else
            {
                if ( '' == $sql_row )
                    $sql_row .= end($where) . ' ';
                else
                    $sql_row .= key($where) . ' ' . end($where) . ' ';
            }
        }
        if ( '' != $sql_row )
            $sql .= $sql_row;
        if ( true == $flag_separator )
            $sql .= " )";
        return $sql;
    }

   /**
     * Get list id linked
     *
     * @param string $source_target tcelevaia tablitca s kotoroi` postroena sviaz` (mnogie ko mnogim)
     * @param mixed $source_target_id identifikator(y` stroka cherez zapiatuiu) ob``ekta tcelevoi` tablitca s kotoroi` postroena sviaz` mnogie ko mnogim
     * @return array
     */
    public function Select_Cross_ID($source_target, $source_target_id)
    {
        $link = $this->Model->Get_Config_Link();
        if ( !$source_target || !$source_target_id || !isset($link[$source_target]) )
        {
            Zero_Logs::Set_Message_Error("nepravil`noe obrashchenie k kross tablitce: {$this->Model->Source} - {$source_target}, ID = {$source_target_id}");
            return [];
        }
        $link = $link[$source_target];
        $sql = "
        SELECT
          {$link['prop_this']}
        FROM {$link['table_link']}
        WHERE
          {$link['prop_target']} IN ({$source_target_id})
        ORDER BY
          1
        ";
        return Zero_DB::Select_List($sql);
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $sign Znak usloviia (= ,!=, <=, >=, <>)
     * @param string $value znachenie udovletvoriaiushchie usloviiu
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where($prop = '', $sign = '', $value = '', $separator = 'AND')
    {
        if ( $prop )
            return $this->Sql_Where_Expression($prop . ' ' . $sign . ' ' . Zero_DB::Escape_T($value), $separator);
        else
            unset($this->Params['Where']);
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $condition uslovie
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_Expression($condition, $separator = 'AND')
    {
        $this->Params['Where'][] = [$separator => $condition];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $value znachenie udovletvoriaiushchie usloviiu
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_Like($prop, $value, $separator = 'AND')
    {
        if ( is_array($value) && !is_scalar($value) )
            $value = implode("%", $value);
        $value = Zero_DB::Escape_T('%' . str_replace(' ', '%', $value) . '%'); //  ??? vozmozhno probely` ne stoit zameniat` (tonkaia nastroi`ka)
        $this->Params['Where'][] = [$separator => "{$prop} LIKE {$value}"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $value znachenie udovletvoriaiushchie usloviiu
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_NotLike($prop, $value, $separator = 'AND')
    {
        if ( is_array($value) && !is_scalar($value) )
            $value = implode("%", $value);
        $value = Zero_DB::Escape_T('%' . str_replace(' ', '%', $value) . '%'); //  ??? vozmozhno probely` ne stoit zameniat` (tonkaia nastroi`ka)
        $this->Params['Where'][] = [$separator => "{$prop} NOT LIKE {$value}"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $value znachenie udovletvoriaiushchie usloviiu
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_In($prop, $value, $separator = 'AND')
    {
        if ( is_array($value) && !is_scalar($value) )
        {
            foreach ($value as $k => $v)
            {
                $value[$k] = Zero_DB::Escape_T($v);
            }
            $value = implode(", ", $value);
        }
        else
            $value = Zero_DB::Escape_T($value);
        //        if ( 'NULL' == $value || !$value )
        //          return;
        $this->Params['Where'][] = [$separator => "{$prop} IN ({$value})"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $value znachenie udovletvoriaiushchie usloviiu
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_NotIn($prop, $value, $separator = 'AND')
    {
        if ( is_array($value) && !is_scalar($value) )
        {
            foreach ($value as $k => $v)
            {
                $value[$k] = Zero_DB::Escape_T($v);
            }
            $value = implode(", ", $value);
        }
        else
            $value = Zero_DB::Escape_T($value);
        //        if ( 'NULL' == $value || !$value )
        //            return false;
        $this->Params['Where'][] = [$separator => "{$prop} NOT IN ({$value})"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_IsNull($prop, $separator = 'AND')
    {
        $this->Params['Where'][] = [$separator => "{$prop} IS NULL"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_IsNotNull($prop, $separator = 'AND')
    {
        $this->Params['Where'][] = [$separator => "{$prop} IS NOT NULL"];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     *
     * @param string $prop svoi`stvo po kotoromu stavitsia uslovie
     * @param string $value_begin nachal`noe znachenie udovletvoriaiushchie usloviiu (>=)
     * @param string $value_end konechnoe znachenie udovletvoriaiushchie usloviiu (<)
     * @param string $separator Logicheskoe ob``edinenie uslovii` (AND, OR)
     */
    public function Sql_Where_Between($prop, $value_begin, $value_end, $separator = 'AND')
    {
        $this->Params['Where'][] = [$separator => "{$prop} BETWEEN " . Zero_DB::Escape_T($value_begin) . " AND " . Zero_DB::Escape_T($value_end)];
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     * Logicheskoe i mezhdu usloviiami (kak gruppy` uslovtii` ta k i odnogo usloviia)
     */
    public function Sql_Where_And()
    {
        $this->Params['Where'][] = 'AND';
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     * Logicheskoe ili mezhdu usloviiami (kak gruppy` uslovtii` ta k i odnogo usloviia)
     */
    public function Sql_Where_Or()
    {
        $this->Params['Where'][] = 'OR';
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     * Argument $Filter iavliaetsia komponentom fil`tra ob``ektov s kotory`mi on rabotaet.
     * Pozvoliaet rabotat` v rezhime korobki (gotovoi` sborki), na osnove uslovii` fil`tra:
     * - Ustanavlivaet tochny`e usloviia
     * - Ustanvalivaet uslovie poiska
     * - Ustanvalivaet sortirovku
     * - Ustanvalivaet postranichnost`
     *
     * @param Zero_Filter $Filter component filter object
     */
    public function Sql_Where_Filter(Zero_Filter $Filter)
    {
        $filter_list = $Filter->Get_Filter();
        foreach ($filter_list as $row)
        {
            // если фильтр не установлен
            if ( !$row['Value'] )
                continue;

            // если нулевое или не нулевое значение
            if ( 'NULL' == $row['Value'] || 'IS NULL' == $row['Value'] )
            {
                $this->Sql_Where_IsNull($row['AliasDB']);
                continue;
            }
            else if ( 'NOTNULL' == $row['Value'] || 'IS NOT NULL' == $row['Value'] )
            {
                $this->Sql_Where_IsNotNull($row['AliasDB']);
                continue;
            }

            // остальные значения
            //  data i vremia
            if ( 'DateTime' == $row['Filter'] )
            {
                if ( $row['Value'][0] )
                    $this->Sql_Where($row['AliasDB'], '>=', $row['Value'][0]);
                if ( $row['Value'][1] )
                    $this->Sql_Where($row['AliasDB'], '<', $row['Value'][1]);
            } //  mnozhestva
            else if ( 'Checkbox' == $row['Filter'] )
                $this->Sql_Where_Like($row['AliasDB'], $row['Value']);
            //  fil`try` perechisleniia i sviazei` - ssy`lki
            else if ( 'Radio' == $row['Filter'] || 'Select' == $row['Filter'] || 'Link' == $row['Filter'] || 'LinkMore' == $row['Filter'] )
            {
                $this->Sql_Where($row['AliasDB'], '=', $row['Value']);
            }
        }
        //  atomarny`i` poisk i poisk po vsem poliam
        $search = $Filter->Get_Search();
        foreach ($search['Value'] as $prop => $value)
        {
            if ( !$value )
                continue;
            if ( 'ALL_PROPS' == $prop )
            {
                $this->Sql_Where_And();
                unset($search['List']['ALL_PROPS']);
                foreach ($search['List'] as $row)
                {
                    if ( 'Number' == $row['Form'] )
                        $this->Sql_Where($row['AliasDB'], '=', $value, 'OR');
                    else
                        $this->Sql_Where_Like($row['AliasDB'], $value, 'OR');
                }
                $this->Sql_Where_And();
                break;
            }
            else
            {
                if ( 'Number' == $search['List'][$prop]['Form'] )
                {
                    $arr = explode('-', $value);
                    if ( 1 < count($arr) )
                    {
                        if ( 0 < $arr[0] )
                            $this->Sql_Where($search['List'][$prop]['AliasDB'], '>=', $arr[0] * 1);
                        if ( 0 < $arr[1] )
                            $this->Sql_Where($search['List'][$prop]['AliasDB'], '<=', $arr[1] * 1);
                    }
                    else
                        $this->Sql_Where($search['List'][$prop]['AliasDB'], '=', $value * 1);
                }
                else
                    $this->Sql_Where_Like($search['List'][$prop]['AliasDB'], $value);
            }
        }

        //    sortirovka
        $sort = $Filter->Get_Sort();
        foreach ($sort['Value'] as $prop => $value)
        {
            if ( $value )
                $this->Sql_Order($sort['List'][$prop]['AliasDB'], $value);
        }

        //    postranichnost`
        $this->Sql_Limit($Filter->Page, $Filter->Page_Item);
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Ukazy`vaetsia pole po kotoromu proishodit gruppirovka.
     *
     * @param string $prop pole (svoi`stvo ob``ekta)
     */
    public function Sql_Group($prop = '')
    {
        if ( $prop )
            $this->Params['Group'] = $prop;
        else
            unset($this->Params['Group']);
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Ukazy`vaetsia pole po kotoromu proishodit sortirovka i ee naprvlenie.
     *
     * @param string $prop pole (svoi`stvo ob``ekta)
     * @param string $value 'ASC' or 'DESC'
     */
    public function Sql_Order($prop, $value)
    {
        if ( $prop && $value )
            $this->Params['Order'][] = ['Prop' => $prop, 'Value' => $value];
        else
            unset($this->Params['Order']);
    }

    /**
     * Ustavnoka uslovii` dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * @param integer $page nomer stranitcy` (0 poluchit` vse)
     * @param integer $page_item kolichestvo e`lementov na stranitce (0 poluchit` vse)
     */
    public function Sql_Limit($page = 0, $page_item = 0)
    {
        if ( $page )
            $this->Params['Limit'] = [$page, $page_item];
        else
            unset($this->Params['Limit']);
    }

    /**
     * Sbros ustanovlenny`kh parametrov dlia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     */
    public function Sql_Reset()
    {
        $this->Params = [];
    }

    /**
     * Proverka sushchestvovanie sviazi (mnogie ko mnogim).
     *
     * @param string $source_target istochnik danny`kh s kotory`m postroena sviaz` (mnogie ko mnogim)
     * @param mixed $source_target_id identifikator(y` stroka cherez zapiatuiu) ob``ekta tcelevogo istochnika s kotory`m postroena sviaz` (mnogie ko mnogim)
     * @return int est` li sviaz`
     * TODO работу с cross таблицами упростить.
     */
    public function Select_Cross_IsExist($source_target, $source_target_id)
    {
        $link = $this->Model->Get_Config_Link();
        if ( !$source_target || !$source_target_id || !isset($link[$source_target]) )
        {
            Zero_Logs::Set_Message_Error("nepravil`noe obrashchenie k kross tablitce: {$this->Model->Source} - {$source_target}, ID = {$source_target_id}");
            return -1;
        }
        $link = $link[$source_target];
        $sql = "
        SELECT
          COUNT(*)
        FROM {$link['table_link']}
        WHERE
          {$link['prop_target']} IN ({$source_target_id})
          AND {$link['prop_this']} = {$this->Model->ID}
        ";
        unset($link);
        return Zero_DB::Select_Field($sql);
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu (odin ko mnogim i mnogie ko mnogim).
     *
     * Poriadok formirovanie zaprosa:
     * - Ustanovka fil`trov (mozhet ne by`t`)
     * - Ustanovka sviazanny`kh ob``ektov (mnogie ko mnogim, uslovie I) (mozhet ne by`t`) (zadaetsia v Sql_Cross)
     * - Gruppirovka (mozhet ne by`t`).
     * - Sortirovka (mozhet ne by`t`).
     * - Postranichnost` (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom
     * @param bool $flag_param_reset flag sbrosa parametrov posle zaprosa
     * @return array Nai`denny`e danny`e v vide assotciativnogo massiva
     */
    public function Select_Array($props, $flag_param_reset = true)
    {
        return $this->_Select($props, 'array', $flag_param_reset);
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu (odin ko mnogim i mnogie ko mnogim).
     *
     * Poriadok formirovanie zaprosa:
     * - Ustanovka fil`trov (mozhet ne by`t`)
     * - Ustanovka sviazanny`kh ob``ektov (mnogie ko mnogim, uslovie I) (mozhet ne by`t`) (zadaetsia v Sql_Cross)
     * - Gruppirovka (mozhet ne by`t`).
     * - Sortirovka (mozhet ne by`t`).
     * - Postranichnost` (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom
     * @param bool $flag_param_reset flag sbrosa parametrov posle zaprosa
     * @return array Nai`denny`e danny`e v vide indeksirovannogo assotciativnogo massiva
     */
    public function Select_Array_Index($props, $flag_param_reset = true)
    {
        return $this->_Select($props, 'index', $flag_param_reset);
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu (odin ko mnogim i mnogie ko mnogim).
     *
     * Poriadok formirovanie zaprosa:
     * - Ustanovka fil`trov (mozhet ne by`t`)
     * - Ustanovka sviazanny`kh ob``ektov (mnogie ko mnogim, uslovie I|NE) (mozhet ne by`t`) (zadaetsia v Sql_Cross)
     * - Gruppirovka (mozhet ne by`t`).
     * - Sortirovka (mozhet ne by`t`).
     * - Postranichnost` (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param mixed $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom, libo odnomerny`i` massiv so svoi`stvami
     * @param string $mode v kakom vide otdavat` rezul`tat (array po umolchaniiu, list, index, model, row)
     * @param bool $flag_param_reset flag sbrosa parametrov posle zaprosa
     * @return array nai`denny`e danny`e
     */
    private function _Select($props, $mode = 'array', $flag_param_reset = true)
    {
        //  initcializatciia
        if ( is_array($props) )
            $sql_prop = "SELECT " . implode(', ', $props);
        else if ( '*' == $props )
            $sql_prop = "SELECT " . implode(', ', array_keys($this->Model->Get_Config_Prop()));
        else
            $sql_prop = "SELECT " . $props;
        /**
         * Usloviia Where
         */
        if ( isset($this->Params['Where']) )
            $sql_where = 'WHERE ' . $this->Sql_Where_Compilation();
        else
            $sql_where = 'WHERE 1';
        /**
         * Gruppirovka
         */
        $sql_group = '';
        if ( isset($this->Params['Group']) )
        {
            $sql_group = 'GROUP BY ' . $this->Params['Group'];
        }
        /**
         * Sortirovka
         */
        $sql_sort = '';
        if ( isset($this->Params['Order']) )
        {
            $sql_sort = 'ORDER BY';
            foreach ($this->Params['Order'] as $row)
            {
                $sql_sort .= ' ' . $row['Prop'] . ' ' . $row['Value'] . ',';
            }
            $sql_sort = substr($sql_sort, 0, -1);
        }
        /**
         * Postranichnost`
         */
        $sql_limit = '';
        if ( isset($this->Params['Limit']) )
        {
            if ( 0 < $this->Params['Limit'][1] )
                $sql_limit = 'LIMIT ' . (($this->Params['Limit'][0] - 1) * $this->Params['Limit'][1]) . ', ' . $this->Params['Limit'][1];
            else
                $sql_limit = 'LIMIT ' . $this->Params['Limit'][0];
        }
        /**
         * From
         */
        $source = $this->Model->Get_Source();
        if ( isset($this->Params['From']) )
            $sql_from = $this->Params['From'];
        else
            $sql_from = "FROM {$source} AS z";
        /**
         * OB``EKTY
         */
        $sql = "
        {$sql_prop}
        {$sql_from}
        {$sql_where}
        {$sql_group}
        {$sql_sort}
        {$sql_limit}
        ";
        $result = [];
        if ( 'array' == $mode )
            $result = Zero_DB::Select_Array($sql);
        else if ( 'index' == $mode )
            $result = Zero_DB::Select_Array_Index($sql);
        else if ( 'list' == $mode )
            $result = Zero_DB::Select_List_Index($sql);
        else if ( 'row' == $mode )
            $result = Zero_DB::Select_Row($sql);
        if ( $flag_param_reset )
            $this->Sql_Reset();
        return $result;
    }

    /**
     * Poluchenie kolichestva danny`kh udovletriaiushchikh zaprosu
     *
     * @return integer
     * @param bool $flag_param_reset flag sbrosa parametrov posle zaprosa
     * @see Select
     */
    public function Select_Count($flag_param_reset = true)
    {
        //  initcializatciia
        $sql_prop = "SELECT COUNT(DISTINCT z.ID)";
        /**
         * Usloviia Where
         */
        if ( isset($this->Params['Where']) )
            $sql_where = 'WHERE ' . $this->Sql_Where_Compilation();
        else
            $sql_where = 'WHERE 1';
        /**
         * From
         */
        $source = $this->Model->Get_Source();
        if ( isset($this->Params['From']) )
            $sql_from = $this->Params['From'];
        else
            $sql_from = "FROM {$source} AS z";
        /**
         * KOLICHESVTO OB``EKTOV
         */
        $sql = "
        {$sql_prop}
        {$sql_from}
        {$sql_where}
        ";
        if ( $flag_param_reset )
            $this->Sql_Reset();
        return Zero_DB::Select_Field($sql);
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu rekursivno po derevu.
     *
     * Poriadok formirovanie zaprosa:
     * - Sortirovka (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param mixed $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom, libo odnomerny`i` massiv so svoi`stvami
     * @return array nai`denny`e danny`e
     */
    public function Select_Line($props)
    {
        if ( 0 == $this->Model->ID )
            return [];
        //  initcializatciia
        $source = $this->Model->Get_Source();
        $prop_list = $this->Model->Get_Config_Prop();
        if ( is_array($props) )
            $sql_prop = implode(', ', $props);
        else if ( '*' == $props )
            $sql_prop = implode(', ', array_keys($prop_list));
        else
            $sql_prop = $props;
        $sql_prop .= ", {$source}_ID";

        /**
         * Usloviia Where
         */
        if ( isset($this->Params['Where']) )
            $sql_where = $this->Sql_Where_Compilation();
        else
            $sql_where = '1';

        //  OB``EKTY
        $this->Params['__CATALOG__'] = [];
        $sql_tpl = "SELECT {$sql_prop} FROM {$source} WHERE ID = <ID> AND {$sql_where}";
        $this->_Select_Line($sql_tpl, $this->Model->ID);
        $this->Params['__CATALOG__'] = array_reverse($this->Params['__CATALOG__'], true);
        return $this->Params['__CATALOG__'];
    }

    /**
     * Rekusrivny`i` obhod kataloga.
     *
     * Poluchenie vsego dereva kataloga, libo ego otdel`noi` vetki
     *
     * @param string $sql_tpl shablon sql zaprosa
     * @param int $id identifikator
     */
    private function _Select_Line($sql_tpl, $id)
    {
        if ( 0 < $id )
        {
            $sql = str_replace('<ID>', $id, $sql_tpl);
            foreach (Zero_DB::Select_Array_Index($sql) as $id => $row)
            {
                $this->Params['__CATALOG__'][$id] = $row;
                $this->_Select_Line($sql_tpl, $row[$this->Model->Get_Source() . '_ID']);
            }
        }
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu rekursivno po derevu.
     *
     * Poriadok formirovanie zaprosa:
     * - Sortirovka (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param mixed $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom, libo odnomerny`i` massiv so svoi`stvami
     * @return array nai`denny`e danny`e
     */
    public function Select_Tree($props)
    {
        //  initcializatciia
        $source = $this->Model->Get_Source();
        $prop_list = $this->Model->Get_Config_Prop();
        if ( is_array($props) )
            $sql_prop = implode(', ', $props);
        else if ( '*' == $props )
            $sql_prop = implode(', ', array_keys($prop_list));
        else
            $sql_prop = $props;
        $sql_prop .= ", {$source}_ID";

        /**
         * Usloviia Where
         */
        if ( isset($this->Params['Where']) )
            $sql_where = $this->Sql_Where_Compilation();
        else
            $sql_where = '1';

        //  Sortirovka
        $sql_sort = '';
        if ( isset($prop_list['Sort']) )
            $sql_sort = 'ORDER BY Sort ASC';
        else if ( isset($prop_list['Direction']) )
            $sql_sort = 'ORDER BY Direction ASC';
        else
            $sql_sort = 'ORDER BY Name ASC';

        //  OB``EKTY
        $this->Params['__CATALOG__'] = [];
        $sql_tpl = "SELECT {$sql_prop} FROM {$source} WHERE {$source}_ID = <ID> AND {$sql_where} {$sql_sort}";
        if ( 0 == $this->Model->ID )
        {
            $sql = "SELECT {$sql_prop} FROM {$source} WHERE {$source}_ID IS NULL AND {$sql_where} {$sql_sort}";
            foreach (Zero_DB::Select_Array_Index($sql) as $id => $row)
            {
                $this->Params['__CATALOG__'][$id] = $row;
                $this->Params['__CATALOG__'][$id]['Level'] = 1;
                $this->_Select_Tree($sql_tpl, $id);
            }
        }
        else
            $this->_Select_Tree($sql_tpl, $this->Model->ID);
        return $this->Params['__CATALOG__'];
    }

    /**
     * Rekusrivny`i` obhod kataloga.
     *
     * Poluchenie vsego dereva kataloga, libo ego otdel`noi` vetki
     *
     * @param string $sql_tpl shablon sql zaprosa
     * @param int $id identifikator
     * @param int $level uroven` vlozhennosti
     */
    private function _Select_Tree($sql_tpl, $id, $level = 1)
    {
        $sql = str_replace('<ID>', $id, $sql_tpl);
        foreach (Zero_DB::Select_Array_Index($sql) as $id => $row)
        {
            $this->Params['__CATALOG__'][$id] = $row;
            $this->Params['__CATALOG__'][$id]['Level'] = $level;
            $this->_Select_Tree($sql_tpl, $id, $level + 1);
        }
    }

    /**
     * Zagruzka vy`borochny`kh svoi`stv ob``ekta, libo tcelikom iz BD.
     *
     * Esli ob``ekt v BD ne by`l nai`den $this->ID stanovitsia ravny`m 0
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zaiapiatuiu ('Name, Price, Description')
     * @return bool|array
     */
    public function Select($props)
    {
        if ( 0 < $this->Model->ID )
            $this->Sql_Where('ID', '=', $this->Model->ID);
        else if ( empty($this->Params['Where']) )
        {
            Zero_Logs::Set_Message_Error("Error Load: {$this->Model->Source} SqlWhere is empty");
            return false;
        }
        $row = $this->_Select($props, 'row');
        if ( 0 < count($row) )
            $this->Model->Set_Props($row);
        return $row;
    }

    /**
     * Zagruzka vy`borochny`kh svoi`stv ob``ekta, libo tcelikom iz BD.
     *
     * Esli ob``ekt v BD ne by`l nai`den $this->ID stanovitsia ravny`m 0
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zaiapiatuiu ('Name, Price, Description')
     * @return bool|array
     */
    public function Select_Language($props)
    {
        if ( 0 == $this->Model->ID )
            return [];
        $source = $this->Model->Get_Source();
        $sql = "SELECT {$props} FROM {$source}Language WHERE {$source}_ID = {$this->Model->ID} AND Lang = '" . ZERO_LANG . "'";
        $row = Zero_DB::Select_Row($sql);
        unset($row['Lang']);
        unset($row[$source . '_ID']);
        unset($row['ID']);
        $this->Model->Set_Props($row);
        return $row;
    }

    /**
     * Save danny`kh v BD.
     *
     * @param string $source
     * @return bool
     */
    public function Insert($source = '')
    {
        $prop_list = $this->Model->Get_Config_Prop();
        unset($prop_list['ID']);

        //  sborka svoi`stv dlia sokhraneniia v BD
        $sql_update = [];
        $props = $this->Model->Get_Props(-1);
        foreach ($props as $prop => $value)
        {
            if ( !isset($prop_list[$prop]) )
                continue;
            $method = "Escape_" . $prop_list[$prop]['DB'];
            $sql_update[] = '`' . $prop . '` = ' . Zero_DB::$method($value);
        }

        //  Save
        if ( 0 == count($sql_update) )
            $sql_update[] = 'ID = NULL';
        $this->Model->ID = Zero_DB::Insert("INSERT " . $this->Model->Source . " SET " . implode(', ', $sql_update));
        if ( !$this->Model->ID )
            return false;

        //  Binarny`e danny`e
        $sql_update = [];
        foreach ($props as $prop => $value)
        {
            if ( isset($_FILES[$prop]) )
            {
                if ( 'File Upload Ok' == $value )
                {
                    // V fai`lovoi` sisteme
                    $file = strtolower($this->Model->Source) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                    $path = ZERO_PATH_DATA . '/' . $file;
                    if ( file_exists($path) )
                    {
                        $pos = strrpos($_FILES[$prop]['name'], ".", -1);
                        $_FILES[$prop]['name'] = substr($_FILES[$prop]['name'], 0, $pos) . '_' . $prop . substr($_FILES[$prop]['name'], $pos);
                        $file = strtolower($this->Model->Source) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                        $path = ZERO_PATH_DATA . '/' . $file;
                    }
                    if ( !is_dir(dirname($path)) )
                        mkdir(dirname($path), 0777, true);
                    if ( !rename($_FILES[$prop]['tmp_name'], $path) )
                    {
                        Zero_Logs::Set_Message_Error('Error Copy File');
                        continue;
                    }
                    $sql_update[] = "`" . $prop . "` = '{$file}'";
                    $this->Model->$prop = $file;
                    //  V BD
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = '" . Zero_DB::Escape_B(file_get_contents($path)) . "'";
                }
                else if ( !$value )
                {
                    $sql_update[] = "`" . $prop . "` = NULL";
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = NULL";
                }
            }
        }
        if ( '' == $source )
            $source = $this->Model->Source;
        if ( 0 < count($sql_update) )
            Zero_DB::Update("UPDATE {$source} SET " . implode(', ', $sql_update) . " WHERE ID = " . $this->Model->ID);

        //  Ustanovka statusov
        $this->Model->Set_Props();

        return $this->Model->ID;
    }

    /**
     * Sozdanie sviazei` mezhdu ob``ektami.
     *
     * @param Zero_Model $ObjectParent Roditel`skii` ob``ekt s kotory`m sozdaem sviaz`
     * @return boolean
     */
    public function Insert_Cross($ObjectParent)
    {
        if ( !is_object($ObjectParent) || 0 == $ObjectParent->ID )
            return false;
        $link = $this->Model->Get_Config_Link()[$ObjectParent->Source];
        $sql = "
        INSERT INTO {$link['table_link']}
          ({$link['prop_this']}, {$link['prop_target']})
        VALUES
          ({$this->Model->ID}, {$ObjectParent->ID})
        ";
        Zero_DB::Update($sql);
        return true;
    }

    /**
     * Izmenenie danny`kh v BD.
     *
     * @param string $source
     * @return bool
     */
    public function Update($source = '')
    {
        $prop_list = $this->Model->Get_Config_Prop();
        unset($prop_list['ID']);
        //  sborka svoi`stv dlia sokhraneniia v BD
        $sql_update = [];
        foreach ($this->Model->Get_Props(-1) as $prop => $value)
        {
            if ( !isset($prop_list[$prop]) )
                continue;
            $method = "Escape_" . $prop_list[$prop]['DB'];
            if ( isset($_FILES[$prop]) )
            {
                if ( 'File Upload Ok' == $value )
                {
                    // V fai`lovoi` sisteme
                    $file = strtolower($this->Model->Source) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                    $path = ZERO_PATH_DATA . '/' . $file;
                    if ( file_exists($path) )
                    {
                        $pos = strrpos($_FILES[$prop]['name'], ".", -1);
                        $_FILES[$prop]['name'] = substr($_FILES[$prop]['name'], 0, $pos) . '_' . $prop . substr($_FILES[$prop]['name'], $pos);
                        $file = strtolower($this->Model->Source) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                        $path = ZERO_PATH_DATA . '/' . $file;
                    }
                    if ( !is_dir(dirname($path)) )
                        mkdir(dirname($path), 0777, true);
                    if ( !rename($_FILES[$prop]['tmp_name'], $path) )
                    {
                        Zero_Logs::Set_Message_Error('Error Copy File');
                        continue;
                    }
                    $sql_update[] = "`" . $prop . "` = '{$file}'";
                    $this->Model->$prop = $file;
                    //  V BD
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = '" . Zero_DB::Escape_B(file_get_contents($path)) . "'";
                }
                else if ( !$value )
                {
                    $sql_update[] = "`" . $prop . "` = NULL";
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = NULL";
                }
            }
            else
                $sql_update[] = '`' . $prop . '` = ' . Zero_DB::$method($value);
        }

        $flag = 0;
        if ( 0 < count($sql_update) )
        {
            //  Usloviia Where
            if ( isset($this->Params['Where']) )
                $sql_where = 'WHERE ' . $this->Sql_Where_Compilation();
            else
                $sql_where = 'WHERE 1';

            //  Identifikator
            if ( 0 < $this->Model->ID )
                $sql_where .= ' AND `ID` = ' . $this->Model->ID;

            if ( '' == $source )
                $source = $this->Model->Source;
            $sql = "UPDATE {$source} SET " . implode(', ', $sql_update) . " " . $sql_where;
            $flag = Zero_DB::Update($sql);
        }

        //  Ustanovka statusov
        $this->Model->Set_Props();

        return false !== $flag;
    }

    /**
     * Sortirovka ob``ekta.
     *
     * Sortiruet ob``ekt za ukazanny`m v $object_id
     * Metod prednaznachen dlia drag and drop
     * Ne prednaznachen dlia sortirovki katalogov verkhnego urovnia
     *
     * @param integer $object_id idetifikator ob``ekta za kotory`m dolzhen stat` sortiruemy`i` (mozhet by`t` 0 esli sortiruem v samoe nachalo)
     * @param integer $parent_id idetifikator roditel`skogo kataloga (dlia sortirovki katalogov)
     * @return boolean
     */
    public function Update_SortingObject($object_id = 0, $parent_id = 0)
    {
        $source = $this->Model->Source;
        $sql_where = '';
        if ( 0 < $parent_id )
            $sql_where = "{$source}_ID = {$parent_id} AND";
        //  poluchaem tekushchiiu sortirovku ob``ekta
        $sort = Zero_DB::Select_Field("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$this->Model->ID}");
        //  sdvigaem sortirovku (skhlopy`vanie)
        $sql = "UPDATE {$source} SET Direction = Direction - 1 WHERE {$sql_where} {$sort} < Direction";
        Zero_DB::Update($sql);
        //  vy`chislenie novoi` sortirovki
        if ( 0 < $object_id )
        {
            $sort = Zero_DB::Select_Field("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$object_id}");
        }
        else
        {
            $sort = Zero_DB::Select_Field("SELECT Direction FROM {$source} WHERE {$sql_where} MIN(Direction)");
        }
        //  razdvigaem sortirovku (vstavka)
        $sql = "UPDATE {$source} SET Direction = Direction + 1 WHERE {$sql_where} {$sort} < Direction";
        Zero_DB::Update($sql);
        $sort++;
        //  obnovliaem sortirovku ob``ekta
        $sql = "UPDATE {$source} SET Direction = {$sort} WHERE {$sql_where} ID = {$this->Model->ID}";
        Zero_DB::Update($sql);
        //
        $this->Model->Direction = $sort;
        return true;
    }

    /**
     * Sortirovka ob``ekta.
     *
     * Sortirovka na odnu pozitciiu v nachalo ili konetc otnositel`no sosednego ob``ekta
     * Ne prednaznachen dlia sortirovki katalogov verkhnego urovnia
     *
     * @param boolean $direction naprvlenie sortirovki
     * @param integer $parent_id idetifikator roditel`skogo kataloga (dlia sortirovki katalogov)
     * @return boolean
     */
    public function Update_SortingStep($direction, $parent_id = 0)
    {
        $source = $this->Model->Source;
        $sql_where = '';
        if ( 0 < $parent_id )
            $sql_where = "{$source}_ID = {$parent_id} AND";
        //  poluchaem tekushchiiu sortirovku ob``ekta
        $this->Model->Direction = Zero_DB::Select_Field("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$this->Model->ID}");
        // nachalo i konetc
        if ( $direction )
        {
            $sql = "SELECT ID FROM {$source} WHERE {$sql_where} Direction = " . ($this->Model->Direction + 1);
            $sort = $this->Model->Direction + 1;
        }
        else
        {
            $sql = "SELECT ID FROM {$source} WHERE {$sql_where} Direction = " . ($this->Model->Direction - 1);
            $sort = $this->Model->Direction - 1;
        }
        if ( !$id = Zero_DB::Select_Field($sql) )
        {
            return true;
        }
        //  obnovliaem sortirovki
        $sql = "UPDATE {$source} SET Direction = {$sort} WHERE {$sql_where} ID = {$this->Model->ID}";
        Zero_DB::Update($sql);
        $sql = "UPDATE {$source} SET Direction = {$this->Model->Direction} WHERE {$sql_where} ID = {$id}";
        Zero_DB::Update($sql);
        //
        $this->Model->Direction = $sort;
        return true;
    }

    /**
     * Udalenie ob``ekta iz BD.
     *
     * @param string $source
     * @return bool
     */
    public function Delete($source = '')
    {
        if ( 0 == $this->Model->ID )
            return true;

        //  Usloviia Where
        if ( isset($this->Params['Where']) )
            $sql_where = 'WHERE ' . $this->Sql_Where_Compilation();
        else
            $sql_where = 'WHERE 1';

        //  Identifikator
        if ( 0 < $this->Model->ID )
            $sql_where .= ' AND `ID` = ' . $this->Model->ID;

        if ( '' == $source )
            $source = $this->Model->Source;
        Zero_DB::Update("DELETE FROM {$source} " . $sql_where);
        return true;
    }

    /**
     * Udalenie sviazei` mezhdu ob``ektami.
     *
     * @param Zero_Model $ObjectParent Roditel`skii` ob``ekt s kotory`m udaliaem sviaz`
     * @return boolean
     */
    public function Delete_Cross($ObjectParent)
    {
        if ( !is_object($ObjectParent) || 0 == $ObjectParent->ID )
            return false;
        $link = $this->Model->Get_Config_Link()[$ObjectParent->Source];
        $sql = "
        DELETE FROM {$link['table_link']}
        WHERE
          {$link['prop_this']} = {$this->Model->ID}
          AND {$link['prop_target']} = {$ObjectParent->ID}
        ";
        Zero_DB::Update($sql);
        return true;
    }

    /**
     * Ustavnoka FROM dlia vy`polneniia osnovny`kh zaprosov (Insert, Update, Delete, Select, Load).
     *
     * Sluzhebny`i` metod dlia osnovnogo zaprosa.
     * Pozvoliaet tochno identifitcirvoat` obrabaty`vaemy`e ob``ekty`.
     * E`ti danny`e berutsia iz metoda modeli (DB_)
     *
     * @param string $sql_from
     */
    public function Sql_From($sql_from)
    {
        $this->Params['From'] = $sql_from;
    }
}
