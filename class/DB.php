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
class Zero_DB
{
    /**
     * Ob``ekt mysqli raboty` s BD
     *
     * @var mysqli
     */
    protected static $DB;

    /**
     * Massiv uslovii` dlia formirovaniia zaprosov k istochniku (Sql_...).
     *
     * @var array
     */
    protected $Params = [];

    /**
     * Ob``ekt, s kotory`m my` rabotaem
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
     * Initcializatciia soedineniia s BD.
     *
     */
    public static function Init()
    {
        //  Initcializatciia ob``ekta mysqli
        /* create a connection object which is not connected */
        self::$DB = mysqli_connect(Zero_App::$Config->Db['Host'], Zero_App::$Config->Db['Login'], Zero_App::$Config->Db['Password'], Zero_App::$Config->Db['Name']);
        /* check connection */
        if ( mysqli_connect_errno() )
            die("mysqli - Unable to connect to the server or choose a database.<br>\n Cause: " . mysqli_connect_error());
        self::$DB->set_charset('utf8');
        //  self::$DB = mysqli_init();
        /* set connection options */
        //  self::$DB->options(MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
        //  self::$DB->options(MYSQLI_INIT_COMMAND, "SET CacheDataACTER SET UTF8");
        //  self::$DB->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        /* connect to server */
        //  self::$DB->real_connect(DB_HOST, DB_LOGIN, DB_PASSW, DB_NAME);
        //  self::$DB->select_db(DB_NAME);
        //  Initcializatciia interfesa dlia raboty` s khranimy`mi protcedurami
        //    self::$Procedure = new DB_Procedure();
        //  mysql_query('SET CacheDataACTER SET UTF8');
        //  mysql_query('SET CacheDataACTER SET cp1251_koi8');
        //  mysql_query('set names cp1251');
        //  mysql_query("SET CacheDataACTER SET DEFAULT", self::$DB_Link);
    }

    /**
     * Poluchenie uslovii` zaprosa
     *
     * @return array
     */
    public function Get_Params()
    {
        return $this->Params;
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
     * Proverka tcely`kh chisel
     *
     * @param int $int
     * @return int or NULL
     */
    public static function I($int)
    {
        if ( 0 == strlen($int) )
            return 'NULL';
        return intval($int);
    }

    /**
     * Proverka chisel s plavaiushchei` tochkoi`
     *
     * @param float $float
     * @return float or NULL
     */
    public static function F($float)
    {
        if ( 0 == strlen($float) )
            return 'NULL';
        return floatval(str_replace(',', '.', $float));
    }

    /**
     * Proverka tekstovy`kh strok
     *
     * @param string $str
     * @return string or NULL
     */
    public static function T($str)
    {
        $str = trim(strval($str));
        if ( $str )
            return "'" . self::$DB->real_escape_string($str) . "'";
        else
            return 'NULL';
    }

    /**
     * Proverka perechislenii` (ENUM)
     *
     * @param string $str
     * @return string or NULL
     */
    public static function E($str)
    {
        $str = trim(strval($str));
        if ( $str )
            return "'" . self::$DB->real_escape_string($str) . "'";
        else
            return 'NULL';
    }

    /**
     * Proverka mnozhestv (SET)
     *
     * @param array $array
     * @param string $separator razdelitel` e`lementov mnozhestva
     * @return string or NULL
     */
    public static function S($array, $separator = ',')
    {
        $str = trim(implode($separator, $array));
        if ( $str )
            return "'" . self::$DB->real_escape_string($str) . "'";
        else
            return 'NULL';
    }

    /**
     * Proverka daty` i vremeni
     *
     * @param string $datetime
     * @return string or NULL
     */
    public static function D($datetime)
    {
        $datetime = trim(strval($datetime));
        if ( $datetime )
            return "'" . self::$DB->real_escape_string($datetime) . "'";
        else
            return 'NULL';
    }

    /**
     * Perevod binarny`kh danny`kh v format binarnogo SQL (0xFFFF...)
     *
     * @param string $data
     * @return string or NULL
     */
    public static function B($data)
    {
        $rph = "0x";
        if ( 0 < strlen($data) )
        {
            for ($i = 0; $i < strlen($data); $i++)
            {
                $chr = dechex(ord($data[$i]));
                if ( strlen($chr) < 2 )
                    $chr = "0" . $chr;
                $rph .= $chr;
            }
        }
        return $rph;
    }

    /**
     * Vy`polnenie zaprosa i vozvrashchenie deskriptora na rezul`tat
     *
     * - Zamer vremeni vy`polneniia zaprosa
     * - V sluchae oshibki vozvrashchaet false
     * - Zapis` zaprosa i vremeni ego vy`polneniia v fai`l-loge
     *
     * @param string $sql zapros k BD
     * @return bool|mysqli_result
     * @throws Exception
     */
    protected static function Query($sql)
    {
        Zero_Logs::Start('#{SQL} ' . $sql);
        $res = self::$DB->query($sql);
        Zero_Logs::Stop('#{SQL} ' . $sql);
        if ( !$res )
        {
            Zero_Logs::Set_Message("#{SQL_ERROR} " . self::$DB->error);
            Zero_Logs::Set_Message("#{SQL_QUERY} " . $sql);
            throw new Exception(self::$DB->error, 500);
        }
        return $res;
    }

    /**
     * Vy`polnenie zaprosa i vozvrashchenie deskriptora na rezul`tat
     *
     * Dlia khranimy`kh protcedur
     * - Zamer vremeni vy`polneniia zaprosa
     * - V sluchae oshibki vozvrashchaet false
     * - Zapis` zaprosa i vremeni ego vy`polneniia v fai`l-loge
     *
     * @param string $sql zapros k BD
     * @return bool|mysqli_result
     * @throws Exception
     */
    protected static function Query_Real($sql)
    {
        Zero_Logs::Start('#{SQL} ' . $sql);
        $res = self::$DB->real_query($sql);
        Zero_Logs::Stop('#{SQL} ' . $sql);
        if ( !$res )
        {
            Zero_Logs::Set_Message("#{SQL_ERROR} " . self::$DB->error);
            Zero_Logs::Set_Message("#{SQL_QUERY} " . $sql);
            throw new Exception(self::$DB->error, 500);
        }
        return $res;
    }

    /**
     * Vy`borka odnogo polia zapisi odnoi` zapisi (svoi`stva ob``ekta).
     *
     * @param int $id identifikator zapisi
     * @param string $source istochnik zapisi (tablitca)
     * @param string $filed pole zapisi
     * @return mixed
     */
    public static function Query_Get_Filed($id, $source, $filed)
    {
        return self::Query_Get_Cnt("SELECT `{$filed}` FROM {$source} WHERE ID = {$id}");
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide odnomernogo assotciativnogo massiva (odnoi` stroki)
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Query_Get_Row($sql)
    {
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        $row = $res->fetch_assoc();
        $res->close();
        if ( is_array($row) )
            return $row;
        return [];
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide odnomernogo massiva (spisok)
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Query_Get_One($sql)
    {
        $result = [];
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        while ( false != $row = $res->fetch_row() )
        {
            $result[] = $row[0];
        }
        $res->close();
        return $result;
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide odnomernogo massiva (spisok)
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Query_Get_Two($sql)
    {
        $result = [];
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        while ( false != $row = $res->fetch_row() )
        {
            $result[$row[0]] = $row[1];
        }
        $res->close();
        return $result;
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide assotciativnogo dvukhmernogo massiva
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Query_Get_Array($sql)
    {
        $result = [];
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        while ( false != $row = $res->fetch_assoc() )
        {
            $result[] = $row;
        }
        $res->close();
        return $result;
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide indeksirovannogo dvukhmernogo assotciativnogo massiva
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Query_Get_Array_Index($sql)
    {
        $result = [];
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        while ( false != $row = $res->fetch_assoc() )
        {
            $result[reset($row)] = $row;
        }
        $res->close();
        return $result;
    }

    /**
     * Priamoi` zapros k BD na poluchenie rezul`tata raboty` dlia agregiruiushchikh funktcii`
     *
     * @param string $sql zapros
     * @return string|integer
     */
    public static function Query_Get_Cnt($sql)
    {
        if ( !$res = self::Query($sql) )
            return false;
        /* @var $res mysqli_result */
        $row = $res->fetch_row();
        $res->close();
        return isset($row[0]) ? $row[0] : null;
    }

    /**
     * Poluchenie variantov znachenii` dlia polei` SET ili ENUM v vide assotciativnogo massiva
     *
     * @param Zero_Model $Model ob``ekt modeli
     * @param string $column svoi`stvo (stolbetc v bd)
     * @return array
     */
    public static function Query_Get_EnumSet($Model, $column)
    {
        $index = $Model->Get_Source() . '/EnumSet/' . $column . '/' . Zero_App::$Route->lang;
        if ( false === $result = Zero_Cache::Get_Data($index) )
        {
            $result = [];
            $row = self::Query_Get_Row("DESCRIBE " . $Model->Source . " " . $column);
            $row = explode("','", substr($row['Type'], strpos($row['Type'], "'") + 1, -2));
            sort($row);
            foreach ($row as $v)
            {
                $result[$v] = Zero_I18n::T($Model->Source, 'model prop ' . $column . ' option ' . $v, $v);
            }
            Zero_Cache::Set_Data($index, $result);
        }
        return $result;
    }

    /**
     * Priamoi` zapros k BD na obnovlenie ili udalenie danny`kh
     *
     * Izmeneniia v bd (delete, update)
     * Vozvrashchaet kolichestvo strok izmenenny`kh ili udalenny`kh libo false
     *
     * @param string $sql zapros k BD
     * @return boolean or integer
     */
    public static function Query_Set($sql)
    {
        if ( self::Query($sql) )
            return self::$DB->affected_rows;
        return false;
    }

    /**
     * Priamoi` zapros k BD na dobavlenie danny`kh
     *
     * Dobavlenie v bd (insert)
     * vozvrashchaet ID dobavlennoi` zapisi v tekushchei` tranzaktcii
     *
     * @param string $sql zapros k BD
     * @return int
     */
    public static function Query_Ins($sql)
    {
        if ( self::Query($sql) )
            return self::$DB->insert_id;
        return false;
    }

    /**
     * Rabota s khranimy`mi protcedurami i funktciiami.
     *
     * Vy`polnenie khranimy`kh protcedur i funktcii` cherez metod peregruzki metodov
     *
     * @param string $store_procedure_name imia khranimoi` protcedury`
     * @param array $params spisok parametrov khranimoi` protcedury`
     * @return array rezul`tat
     */
    public static function Query_Procedure($store_procedure_name, $params)
    {
        $quotedparams = [];
        foreach ($params as $param)
        {
            array_push($quotedparams, $param === null ? 'NULL' : "'" . self::$DB->real_escape_string($param) . "'");
        }
        $sql = 'CALL ' . $store_procedure_name . '(' . implode(',', $quotedparams) . ');';
        /* execute multi query */
        if ( !self::Query_Real($sql) )
            return false;
        $results = [];
        do
        {
            if ( false != $result = self::$DB->use_result() )
            {
                $rows = [];
                while ( false != $row = $result->fetch_assoc() )
                {
                    $rows[] = $row;
                }
                $result->close();
                $results[] = $rows;
            }
        }
        while ( self::$DB->next_result() );
        //  while ( self::$DB->more_results() &&  self::$DB->next_result() );
        if ( 1 < count($results) )
            return $results;
        else if ( 1 < count($results[0]) )
            return $results[0];
        else if ( 1 < count($results[0][0]) )
            return $results[0][0];
        return array_shift($results[0][0]);
    }

    /**
     * Get list id linked
     *
     * @param string $source_target tcelevaia tablitca s kotoroi` postroena sviaz` (mnogie ko mnogim)
     * @param mixed $source_target_id identifikator(y` stroka cherez zapiatuiu) ob``ekta tcelevoi` tablitca s kotoroi` postroena sviaz` mnogie ko mnogim
     * @return array
     */
    public function Cross_GetID($source_target, $source_target_id)
    {
        $link = $this->Model->Get_Config_Link();
        if ( !$source_target || !$source_target_id || !isset($link[$source_target]) )
        {
            Zero_Logs::Set_Message("nepravil`noe obrashchenie k kross tablitce: {$this->Model->Source} - {$source_target}, ID = {$source_target_id}");
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
        return self::Query_Get_One($sql);
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
            return $this->Sql_Where_Expression($prop . ' ' . $sign . ' ' . self::T($value), $separator);
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
        $value = self::T('%' . str_replace(' ', '%', $value) . '%'); //  ??? vozmozhno probely` ne stoit zameniat` (tonkaia nastroi`ka)
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
        $value = self::T('%' . str_replace(' ', '%', $value) . '%'); //  ??? vozmozhno probely` ne stoit zameniat` (tonkaia nastroi`ka)
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
                $value[$k] = self::T($v);
            }
            $value = implode(", ", $value);
        }
        else
            $value = self::T($value);
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
                $value[$k] = self::T($v);
            }
            $value = implode(", ", $value);
        }
        else
            $value = self::T($value);
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
        $this->Params['Where'][] = [$separator => "{$prop} BETWEEN " . self::T($value_begin) . " AND " . self::T($value_end)];
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
        $alias_list = $Filter->Get_Alias();
        $filter_list = $Filter->Get_Filter();
        foreach ($filter_list as $prop => $row)
        {
            if ( !$row['Value'] )
                continue;
            //  data i vremia
            if ( 'DateTime' == $row['Filter'] )
            {
                if ( $row['Value'][0] )
                    $this->Sql_Where($alias_list[$prop] . $prop, '>=', $row['Value'][0]);
                if ( $row['Value'][1] )
                    $this->Sql_Where($alias_list[$prop] . $prop, '<', $row['Value'][1]);
            } //  mnozhestva
            else if ( 'Checkbox' == $row['Filter'] )
                $this->Sql_Where_Like($alias_list[$prop] . $prop, $row['Value']);
            //  fil`try` perechisleniia i sviazei` - ssy`lki
            else if ( 'Radio' == $row['Filter'] || 'Select' == $row['Filter'] || 'Link' == $row['Filter'] || 'LinkMore' == $row['Filter'] )
            {
                if ( 'NULL' == $row['Value'] )
                    $this->Sql_Where_IsNull($alias_list[$prop] . $prop);
                else if ( 'NOTNULL' == $row['Value'] )
                    $this->Sql_Where_IsNotNull($alias_list[$prop] . $prop);
                else
                    $this->Sql_Where($alias_list[$prop] . $prop, '=', $row['Value']);
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
                foreach ($search['List'] as $prop => $row)
                {
                    if ( 'Number' == $row['Form'] )
                        $this->Sql_Where($alias_list[$prop] . $prop, '=', $value, 'OR');
                    else
                        $this->Sql_Where_Like($alias_list[$prop] . $prop, $value, 'OR');
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
                            $this->Sql_Where($alias_list[$prop] . $prop, '>=', $arr[0] * 1);
                        if ( 0 < $arr[1] )
                            $this->Sql_Where($alias_list[$prop] . $prop, '<=', $arr[1] * 1);
                    }
                    else
                        $this->Sql_Where($alias_list[$prop] . $prop, '=', $value * 1);
                }
                else
                    $this->Sql_Where_Like($alias_list[$prop] . $prop, $value);
            }
        }

        //    sortirovka
        $sort = $Filter->Get_Sort();
        foreach ($sort['Value'] as $prop => $value)
        {
            if ( $value )
                $this->Sql_Order($alias_list[$prop] . $prop, $value);
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
            $this->Params['Group'][] = $prop;
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
     */
    public function Cross_IsExist($source_target, $source_target_id)
    {
        $link = $this->Model->Get_Config_Link();
        if ( !$source_target || !$source_target_id || !isset($link[$source_target]) )
        {
            Zero_Logs::Set_Message("nepravil`noe obrashchenie k kross tablitce: {$this->Model->Source} - {$source_target}, ID = {$source_target_id}");
            return 0;
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
        return self::Query_Get_Cnt($sql);
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
     * @return array Nai`denny`e danny`e v vide odnomernogo massiva
     */
    public function Select_Row($props, $flag_param_reset = true)
    {
        return $this->Select($props, 'row', $flag_param_reset);
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
     * @return array Nai`denny`e danny`e v vide spiska
     */
    public function Select_List($props, $flag_param_reset = true)
    {
        return $this->Select($props, 'list', $flag_param_reset);
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
        return $this->Select($props, 'array', $flag_param_reset);
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
        return $this->Select($props, 'index', $flag_param_reset);
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
     * @return Zero_Model[] Nai`denny`e odanny`e v vide indeksirovannogo spiska ob``ektov
     */
    public function Select_Model($props, $flag_param_reset = true)
    {
        return $this->Select($props, 'model', $flag_param_reset);
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
    protected function Select($props, $mode = 'array', $flag_param_reset = true)
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
            $sql_group = 'GROUP BY';
            foreach ($this->Params['Group'] as $prop)
            {
                $sql_group .= ' ' . $prop . ',';
            }
            $sql_group = substr($sql_group, 0, -1);
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
            $result = self::Query_Get_Array($sql);
        else if ( 'index' == $mode )
            $result = self::Query_Get_Array_Index($sql);
        else if ( 'list' == $mode )
            $result = self::Query_Get_Two($sql);
        else if ( 'row' == $mode )
            $result = self::Query_Get_Row($sql);
        else if ( 'model' == $mode )
        {
            $rows = self::Query_Get_Array_Index($sql);
            foreach ($rows as $id => $row)
            {
                $result[$id] = Zero_Model::Make($source, $id);
                $result[$id]->Set_Props($row);
            }
        }
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
        $sql_prop = "SELECT COUNT(z.ID)";
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
            $sql_group = 'GROUP BY';
            foreach ($this->Params['Group'] as $prop)
            {
                $sql_group .= ' ' . $prop . ',';
            }
            $sql_group = substr($sql_group, 0, -1);
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
         * KOLICHESVTO OB``EKTOV
         */
        $sql = "
        {$sql_prop}
        {$sql_from}
        {$sql_where}
        {$sql_group}
        ";
        if ( $flag_param_reset )
            $this->Sql_Reset();
        return self::Query_Get_Cnt($sql);
    }

    /**
     * Poisk ob``ektov s signaturoi` ravnoi` delegirovannomu ob``ektu rekursivno po derevu.
     *
     * Poriadok formirovanie zaprosa:
     * - Sortirovka (mozhet ne by`t`).
     * - Formirovanie zaprosa i poluchenie danny`kh v nuzhnoi formate
     *
     * @param mixed $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu s probelom, libo odnomerny`i` massiv so svoi`stvami
     * @param string $mode tip zaprosa k katalogu (tree, line, child)
     * @return array nai`denny`e danny`e
     */
    public function Select_Tree($props, $mode = 'tree')
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
        if ( 'tree' == $mode )
        {
            $sql_tpl = "SELECT {$sql_prop} FROM {$source} WHERE {$source}_ID = <ID> {$sql_sort}";
            if ( 0 == $this->Model->ID )
            {
                $sql = "SELECT {$sql_prop} FROM {$source} WHERE {$source}_ID IS NULL {$sql_sort}";
                foreach (self::Query_Get_Array_Index($sql) as $id => $row)
                {
                    $this->Params['__CATALOG__'][$id] = $row;
                    $this->Params['__CATALOG__'][$id]['Level'] = 1;
                    $this->_Select_Tree($sql_tpl, $id);
                }
            }
            else
                $this->_Select_Tree($sql_tpl, $this->Model->ID);
        }
        else if ( 'line' == $mode )
        {
            if ( 0 < $this->Model->ID )
            {
                $sql_tpl = "SELECT {$sql_prop} FROM {$source} WHERE ID = <ID>";
                $this->_Select_TreeLine($sql_tpl, $this->Model->ID);
                $this->Params['__CATALOG__'] = array_reverse($this->Params['__CATALOG__'], true);
            }
        }
        else if ( 'child' == $mode )
        {
            if ( 0 < $this->Model->ID )
            {
                $sql = "SELECT {$sql_prop} FROM {$source} WHERE {$source}_ID = {$this->Model->ID} {$sql_sort}";
                $this->Params['__CATALOG__'] = self::Query_Get_Array_Index($sql);
            }
        }
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
        foreach (self::Query_Get_Array_Index($sql) as $id => $row)
        {
            $this->Params['__CATALOG__'][$id] = $row;
            $this->Params['__CATALOG__'][$id]['Level'] = $level;
            $this->_Select_Tree($sql_tpl, $id, $level + 1);
        }
    }

    /**
     * Rekusrivny`i` obhod kataloga.
     *
     * Poluchenie vsego dereva kataloga, libo ego otdel`noi` vetki
     *
     * @param string $sql_tpl shablon sql zaprosa
     * @param int $id identifikator
     */
    private function _Select_TreeLine($sql_tpl, $id)
    {
        if ( 0 < $id )
        {
            $sql = str_replace('<ID>', $id, $sql_tpl);
            foreach (self::Query_Get_Array_Index($sql) as $id => $row)
            {
                $this->Params['__CATALOG__'][$id] = $row;
                $this->_Select_TreeLine($sql_tpl, $row[$this->Model->Get_Source() . '_ID']);
            }
        }
    }

    /**
     * Zagruzka vy`borochny`kh svoi`stv ob``ekta, libo tcelikom iz BD.
     *
     * Esli ob``ekt v BD ne by`l nai`den $this->ID stanovitsia ravny`m 0
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zaiapiatuiu ('Name, Price, Description')
     * @param bool $is_lang zagruzka s uchetom iazy`ka
     * @return bool|array
     */
    public function Load($props, $is_lang = false)
    {
        if ( 0 < $this->Model->ID )
            $this->Sql_Where('ID', '=', $this->Model->ID);
        else if ( empty($this->Params['Where']) )
            return false;
        $row = $this->Select($props, 'row');
        if ( 0 == count($row) )
        {
            $this->Model->ID = 0;
            return $row;
        }
        else
        {
            if ( isset($row['ID']) && $is_lang && Zero_App::$Route->lang != Zero_App::$Config->Site_Language )
            {
                $source = $this->Model->Get_Source();
                $sql = "SELECT {$this->Model->Get_Config_Prop_Lang()} FROM {$source}Language WHERE {$source}_ID = {$row['ID']} AND Zero_Language_ID = " . Zero_App::$Route->lang_id;
                $row = array_replace($row, self::Query_Get_Row($sql));
            }
            $this->Model->Set_Props($row);
            return $row;
        }
    }

    /**
     * Zagruzka vy`borochny`kh svoi`stv ob``ekta, s ispol`zovaniem keshirovaniia iz BD.
     *
     * Sleduet zagruzhat` tol`ko chasto ispol`zuemy`e svoi`stva.
     *
     * @param string $props stroka zagruzhaemy`kh svoi`stv cherez zapiatuiu ('Name, Price, Description')
     * @param integer $time 0 - postoianny`i` kesh, 0 < vremia zhizni kesha v sekundakh
     * @param bool $is_lang zagruzka s uchetom iazy`ka
     * @return array
     */
    public function Load_Cache($props, $time = 0, $is_lang = false)
    {
        if ( !$this->Model->ID )
            Zero_Logs::Set_Message('Error Cache: Object ID is 0');

        $index = str_replace(',', '', $props);
        $index = str_replace(' ', '', $index);
        $index = str_replace('*', '__ALL__', $index);
        if ( false === $row = $this->Model->Cache->Get($index, $time) )
        {
            $row = $this->Load($props, $is_lang);
            $this->Model->Get_Cache()->Set($index, $row, $time);
            return $row;
        }
        $this->Model->Set_Props($row);
        return $row;
    }

    /**
     * Save danny`kh v BD.
     *
     * @return bool
     */
    public function Insert()
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
            $method = $prop_list[$prop]['DB'];
            $sql_update[] = '`' . $prop . '` = ' . self::$method($value);
        }

        //  Save
        if ( 0 == count($sql_update) )
            $sql_update[] = 'ID = NULL';
        $this->Model->ID = self::Query_Ins("INSERT " . $this->Model->Source . " SET " . implode(', ', $sql_update));
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
                    $file = strtolower($this->Model->Source) . '/' . Zero_Helper_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                    if ( !is_dir(dirname($path = ZERO_PATH_DATA . '/' . $file)) )
                        mkdir(dirname($path), 0777, true);
                    if ( !rename($_FILES[$prop]['tmp_name'], $path) )
                    {
                        Zero_Logs::Set_Message('Error Copy File');
                        continue;
                    }
                    $sql_update[] = "`" . $prop . "` = '{$file}'";
                    $this->Model->$prop = $file;
                    //  V BD
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = '" . self::B(file_get_contents($path)) . "'";
                }
                else if ( !$value )
                {
                    $sql_update[] = "`" . $prop . "` = NULL";
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = NULL";
                }
            }
        }
        if ( 0 < count($sql_update) )
            self::Query_Set("UPDATE {$this->Model->Source} SET " . implode(', ', $sql_update) . " WHERE ID = " . $this->Model->ID);

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
        self::Query_Set($sql);
        return true;
    }

    /**
     * Izmenenie danny`kh v BD.
     *
     * @return bool
     */
    public function Update()
    {
        $prop_list = $this->Model->Get_Config_Prop();
        unset($prop_list['ID']);
        //  sborka svoi`stv dlia sokhraneniia v BD
        $sql_update = [];
        foreach ($this->Model->Get_Props(-1) as $prop => $value)
        {
            if ( !isset($prop_list[$prop]) )
                continue;
            $method = $prop_list[$prop]['DB'];
            if ( isset($_FILES[$prop]) )
            {
                if ( 'File Upload Ok' == $value )
                {
                    // V fai`lovoi` sisteme
                    $file = strtolower($this->Model->Source) . '/' . Zero_Helper_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/' . $_FILES[$prop]['name'];
                    if ( !is_dir(dirname($path = ZERO_PATH_DATA . '/' . $file)) )
                        mkdir(dirname($path), 0777, true);
                    if ( !rename($_FILES[$prop]['tmp_name'], $path) )
                    {
                        Zero_Logs::Set_Message('Error Copy File');
                        continue;
                    }
                    $sql_update[] = "`" . $prop . "` = '{$file}'";
                    $this->Model->$prop = $file;
                    //  V BD
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = '" . self::B(file_get_contents($path)) . "'";
                }
                else if ( !$value )
                {
                    $sql_update[] = "`" . $prop . "` = NULL";
                    if ( isset($prop_list[$prop . 'B']) )
                        $sql_update[] = "`" . $prop . "B` = NULL";
                }
            }
            else
                $sql_update[] = '`' . $prop . '` = ' . self::$method($value);
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

            $sql = "UPDATE {$this->Model->Source} SET " . implode(', ', $sql_update) . " " . $sql_where;
            $flag = self::Query_Set($sql);
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
        $sort = self::Query_Get_Cnt("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$this->Model->ID}");
        //  sdvigaem sortirovku (skhlopy`vanie)
        $sql = "UPDATE {$source} SET Direction = Direction - 1 WHERE {$sql_where} {$sort} < Direction";
        self::Query_Set($sql);
        //  vy`chislenie novoi` sortirovki
        if ( 0 < $object_id )
        {
            $sort = self::Query_Get_Cnt("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$object_id}");
        }
        else
        {
            $sort = self::Query_Get_Cnt("SELECT Direction FROM {$source} WHERE {$sql_where} MIN(Direction)");
        }
        //  razdvigaem sortirovku (vstavka)
        $sql = "UPDATE {$source} SET Direction = Direction + 1 WHERE {$sql_where} {$sort} < Direction";
        self::Query_Set($sql);
        $sort++;
        //  obnovliaem sortirovku ob``ekta
        $sql = "UPDATE {$source} SET Direction = {$sort} WHERE {$sql_where} ID = {$this->Model->ID}";
        self::Query_Set($sql);
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
        $this->Model->Direction = self::Query_Get_Cnt("SELECT Direction FROM {$source} WHERE {$sql_where} ID = {$this->Model->ID}");
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
        if ( !$id = self::Query_Get_Cnt($sql) )
        {
            return true;
        }
        //  obnovliaem sortirovki
        $sql = "UPDATE {$source} SET Direction = {$sort} WHERE {$sql_where} ID = {$this->Model->ID}";
        self::Query_Set($sql);
        $sql = "UPDATE {$source} SET Direction = {$this->Model->Direction} WHERE {$sql_where} ID = {$id}";
        self::Query_Set($sql);
        //
        $this->Model->Direction = $sort;
        return true;
    }

    /**
     * Udalenie ob``ekta iz BD.
     *
     * @return bool
     */
    public function Delete()
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

        self::Query_Set("DELETE FROM {$this->Model->Source} " . $sql_where);
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
        self::Query_Set($sql);
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

Zero_DB::Init();