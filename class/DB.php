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
     * Массив коннектов к БД
     *
     * @var array mysqli
     */
    protected static $DB = [];

    /**
     * Массив конфигураций
     *
     * @var array
     */
    protected static $Config = [];

    /**
     * Добавление конфигурации доступа к БД
     *
     * @param $name string название конфигурации (используется при запросах для идентификации коннекта)
     * @param $config array конфигурация ('Host', 'Login', 'Password', 'Name')
     */
    public static function Add_Config($name, $config)
    {
        self::$Config[$name] = $config;
    }

    /**
     * Initcializatciia soedineniia s BD.
     *
     */
    protected static function Init($name)
    {
        //  Initcializatciia ob``ekta mysqli
        /* create a connection object which is not connected */
        try
        {
            self::$DB[$name] = mysqli_connect(self::$Config[$name]['Host'], self::$Config[$name]['Login'], self::$Config[$name]['Password'], self::$Config[$name]['Name']);
        } catch ( Exception $e )
        {
            throw new Exception(mysqli_connect_error(), 500);
        }
        /* check connection */
        if ( mysqli_connect_errno() )
            die("mysqli - Unable to connect to the server or choose a database.<br>\n Cause: " . mysqli_connect_error());
        self::$DB[$name]->set_charset('utf8');
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
     * Proverka tcely`kh chisel
     *
     * @return string
     */

    /**
     * Check and format query
     * %s - строка
     * %d - целое число
     * %f - число с плавающей точкой
     *
     * Format param: $sql[[[, $arg], $arg], $arg...]
     *
     * @return string пропарсенный запрос sql со вставленными параметрами с их проверкой
     * @throws Exception
     */
    public static function Escape()
    {
        $arr = func_get_args();
        $sql = array_shift($arr);
        preg_match_all("~(%[d|s|f]{1})~si", $sql, $match);
        if ( count($arr) != count($match[1]) )
            throw new Exception("Wrong number of parameters", 409);
        foreach ($match[1] as $k => $v)
        {
            $match[1][$k] = '~' . $v . '~i';
            switch ( $v )
            {
                case '%d':
                {
                    $arr[$k] = Zero_DB::Escape_I($arr[$k]);
                    break;
                }
                case '%f':
                {
                    $arr[$k] = Zero_DB::Escape_F($arr[$k]);
                    break;
                }
                case '%s':
                {
                    $arr[$k] = Zero_DB::Escape_T($arr[$k]);
                    break;
                }
            }
            //            echo $v . " = " . $arr[$k] . "<br>";
        }
        return preg_replace($match[1], $arr, $sql, 1);
    }

    /**
     * Proverka tcely`kh chisel
     *
     * @param int $int
     * @return int or NULL
     */
    public static function Escape_I($int)
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
    public static function Escape_F($float)
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
    public static function Escape_T($str)
    {
        if ( !isset(self::$DB['main']) )
            self::Init('main');
        $str = trim(strval($str));
        if ( $str )
            //            return "'" . self::$DB['main']->real_escape_string($str) . "'";
            return "'" . addslashes($str) . "'";
        else
            return 'NULL';
    }

    /**
     * Proverka perechislenii` (ENUM)
     *
     * @param string $enum
     * @return string or NULL
     */
    public static function Escape_E($enum)
    {
        if ( !isset(self::$DB['main']) )
            self::Init('main');
        $enum = trim(strval($enum));
        if ( $enum )
            //            return "'" . self::$DB['main']->real_escape_string($enum) . "'";
            return "'" . addslashes($enum) . "'";
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
    public static function Escape_S($array, $separator = ',')
    {
        if ( !isset(self::$DB['main']) )
            self::Init('main');
        $str = trim(implode($separator, $array));
        if ( $str )
            //            return "'" . self::$DB['main']->real_escape_string($str) . "'";
            return "'" . addslashes($str) . "'";
        else
            return 'NULL';
    }

    /**
     * Proverka daty` i vremeni
     *
     * @param string $datetime
     * @return string or NULL
     */
    public static function Escape_D($datetime)
    {
        return self::Escape_T($datetime);
    }

    /**
     * Perevod binarny`kh danny`kh v format binarnogo SQL (0xFFFF...)
     *
     * @param string $databinary
     * @return string or NULL
     */
    public static function Escape_B($databinary)
    {
        $rph = "0x";
        if ( 0 < strlen($databinary) )
        {
            for ($i = 0; $i < strlen($databinary); $i++)
            {
                $chr = dechex(ord($databinary[$i]));
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
    protected static function Query($sql, $nameConnect = 'main')
    {
        if ( !isset(self::$DB[$nameConnect]) )
            self::Init($nameConnect);
        Zero_Logs::Start('#{SQL} ' . $sql);
        $res = self::$DB[$nameConnect]->query($sql);
        Zero_Logs::Stop('#{SQL} ' . $sql);
        if ( !$res )
        {
            Zero_Logs::Set_Message_Error("#{SQL_ERROR} " . self::$DB[$nameConnect]->error);
            Zero_Logs::Set_Message_Error("#{SQL_QUERY} " . $sql);
            throw new Exception(self::$DB[$nameConnect]->error, 500);
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
    protected static function Query_Real($sql, $nameConnect = 'main')
    {
        if ( !isset(self::$DB[$nameConnect]) )
            self::Init($nameConnect);
        Zero_Logs::Start('#{SQL} ' . $sql);
        $res = self::$DB[$nameConnect]->real_query($sql);
        Zero_Logs::Stop('#{SQL} ' . $sql);
        if ( !$res )
        {
            Zero_Logs::Set_Message_Error("#{SQL_ERROR} " . self::$DB[$nameConnect]->error);
            Zero_Logs::Set_Message_Error("#{SQL_QUERY} " . $sql);
            throw new Exception(self::$DB[$nameConnect]->error, 500);
        }
        return $res;
    }

    /**
     * Priamoi` zapros k BD na poluchenie danny`kh v vide odnomernogo assotciativnogo massiva (odnoi` stroki)
     *
     * @param string $sql zapros
     * @return array
     */
    public static function Select_Row($sql, $nameConnect = 'main')
    {
        if ( !$res = self::Query($sql, $nameConnect) )
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
    public static function Select_List($sql, $nameConnect = 'main')
    {
        $result = [];
        if ( !$res = self::Query($sql, $nameConnect) )
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
    public static function Select_List_Index($sql, $nameConnect = 'main')
    {
        $result = [];
        if ( !$res = self::Query($sql, $nameConnect) )
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
    public static function Select_Array($sql, $nameConnect = 'main')
    {
        $result = [];
        if ( !$res = self::Query($sql, $nameConnect) )
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
    public static function Select_Array_Index($sql, $nameConnect = 'main')
    {
        $result = [];
        if ( !$res = self::Query($sql, $nameConnect) )
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
    public static function Select_Field($sql, $nameConnect = 'main')
    {
        if ( !$res = self::Query($sql, $nameConnect) )
            return false;
        /* @var $res mysqli_result */
        $row = $res->fetch_row();
        $res->close();
        return isset($row[0]) ? $row[0] : null;
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
    public static function Update($sql, $nameConnect = 'main')
    {
        if ( self::Query($sql, $nameConnect) )
            return self::$DB[$nameConnect]->affected_rows;
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
    public static function Insert($sql, $nameConnect = 'main')
    {
        if ( self::Query($sql, $nameConnect) )
            return self::$DB[$nameConnect]->insert_id;
        return false;
    }

    /**
     * Rabota s khranimy`mi protcedurami i funktciiami.
     *
     * Vy`polnenie khranimy`kh protcedur i funktcii` cherez metod peregruzki metodov
     *
     * @param string $store_procedure_name имя хранимой процедуры
     * @param array $params список параметров хранимой процедуры
     * @param string $nameConnect имя коннекта
     * @return array результат
     */
    public static function Call_Procedure($store_procedure_name, $params = [], $nameConnect = 'main')
    {
        $quotedparams = [];
        foreach ($params as $param)
        {
            array_push($quotedparams, $param === null ? 'NULL' : "'" . self::$DB[$nameConnect]->real_escape_string($param) . "'");
        }
        $sql = 'CALL ' . $store_procedure_name . '(' . implode(',', $quotedparams) . ');';
        /* execute multi query */
        if ( !self::Query_Real($sql, $nameConnect) )
            return false;
        $results = [];
        do
        {
            if ( false != $result = self::$DB[$nameConnect]->use_result() )
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
        while ( self::$DB[$nameConnect]->more_results() && self::$DB[$nameConnect]->next_result() );
        if ( 1 < count($results) )
            return $results;
        else if ( 1 < count($results[0]) )
            return $results[0];
        else if ( 1 < count($results[0][0]) )
            return $results[0][0];
        return array_shift($results[0][0]);
    }
}