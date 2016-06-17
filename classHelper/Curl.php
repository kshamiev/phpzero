<?

/**
 * Вспомогательный класс для обмена информацией и проведения операций с внешними ресурсами
 *
 * @package Zero.Helper
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.15
 */
final class Zero_Helper_Curl
{
    //	конструктор
    var $url = ' ';                    //	для проеврки правильной страницы

    public $host = '';

    var $cookie_file = '';

    var $log_file = '';

    var $proxy_id = 0;

    /**
     * Прокси сервер (ip адресс)
     *
     * @var string
     */
    public $proxy = '';

    public function __construct($proxy = '', $url, $host, $log_file)
    {
        $this->proxy = $proxy;

        my_db::__construct();
        $this->url = $url;
        $this->host = $host;
        $this->log_file = $log_file;
        $this->cookie_file();
        $this->proxy_id = 0;
    }

    function cookie_file()
    {
        if ( $this->cookie_file )
            unlink('logs/' . $this->cookie_file);
        $this->cookie_file = $this->log_file . '_cookie_' . date('d.m.Y_H.i.s') . '.txt';
        while ( file_exists('logs/' . $this->cookie_file) )
        {
            sleep(1);
            $this->cookie_file = $this->log_file . '_cookie_' . date('d.m.Y_H.i.s') . '.txt';
        }
        $fp = fopen('logs/' . $this->cookie_file, 'w');
        fclose($fp);
    }

    /**
     * Получение страницы ( text/html )
     *
     * @param string $url запрашиваемый url
     * @param string $postData
     * @return bool|mixed|string
     */
    public function Get_Page($url, $postData = '')
    {
        $ch = curl_init($url);
        //	идем через proxy
        if ( $this->proxy )
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        // curl_setopt($ch,CURLOPT_PROXYUSERPWD,'user:password');
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);                        //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);        //	время ожидания соединения в секундах
        //	Передаем и возвращаем Заголовки и тело страницы
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        //	User-Agent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
        //	Referer
        curl_setopt($ch, CURLOPT_REFERER, $url);
        //	Host
        $header_mas = array();
        $header_mas[] = "Host: " . $this->host;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_mas);
        //	Cookie
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'logs/' . $this->cookie_file);    //	посылка
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'logs/' . $this->cookie_file);        //	получение
        //	АВТОРИЗАЦИЯ МЕТОДОМ APACHE
        //	curl_setopt($ch,CURLOPT_USERPWD,"guest:mics6");
        //	переадресация
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    //	переход по редиректу
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);                //	максимальное количество переадресаций
        //	запрос GET
        if ( $postdata == '' )
        {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        //	запрос POST
        else
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        //	возвращаем результат в переменную
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);
        //	ошибки
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);
        if ( stripos($page, $this->url) === false )
        {
            curl_close($ch);
            return false;
        }
        else
        {
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            //			if ( preg_match("(utf-8)si",$content_type) ) $page=mb_convert_encoding($page,'CP1251');
            //			if ( preg_match("(utf-8)si",$content_type) ) $page=mb_convert_encoding($page,'UTF-8');
            if ( preg_match("(utf-8)si", $content_type) )
                $page = iconv("UTF-8", "CP1251", $page);
            if ( $this->get_query_cnt("SELECT COUNT(*) FROM KlassTraffic WHERE Date = ' " . date('Y-m-d') . "'") )
            {
                $sql = "UPDATE KlassTraffic SET Size = Size + " . strlen($page) . " WHERE Date = '" . date('Y-m-d') . "'";
            }
            else
            {
                $sql = "INSERT INTO KlassTraffic (Date, Size) VALUES (NOW(), " . strlen($page) . ")";
            }
            $this->set_query($sql);
            curl_close($ch);
            return $page;
        }
        //	print curl_getinfo($ch,CURLINFO_HTTP_CODE).'<br>';
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //	ПОЛУЧЕНИЕ БИНАРНЫХ ДАННЫХ ( images/gif ... )	ЧЕРЕЗ PROXY
    function get_file_proxy($url, $file)
    {
        global $user_access;
        $page = false;
        if ( $this->proxy )
        {
            $page = $this->get_file($url, $this->proxy, $file);
            if ( $page )
            {
                return $page;
            }
            else
            {
                $sql = "UPDATE ProxyList SET HttpCode = 404 WHERE ID = " . $this->proxy_id;
                $this->set_query($sql);
                $this->proxy_id = 0;
                $this->proxy = '';
            }
        }
        if ( !$this->proxy_id )
        {
            $proxy_mas = $this->get_query_two("SELECT ID, Host FROM ProxyList WHERE HttpCode = 200 AND FlagBlock = 0");
            foreach ($proxy_mas as $id => $proxy)
            {
                $page = $this->get_file($url, $proxy, $file);
                if ( $page )
                {
                    $sql = "UPDATE ProxyList SET FlagBlock = 1 WHERE ID = " . $id;
                    $this->set_query($sql);
                    $this->proxy_id = $id;
                    $this->proxy = $proxy;
                    return $page;
                }
                else
                {
                    $sql = "UPDATE ProxyList SET HttpCode = 404 WHERE ID = " . $id;
                    $this->set_query($sql);
                    $this->proxy_id = 0;
                    $this->proxy = '';
                }
            }
        }
        $this->log_file('! СТРАНИЦА НЕ ПОЛУЧЕНА !');
        unlink('logs/' . $this->cookie_file);
        $sql = 'UPDATE KlassLogin SET Flag = 0 WHERE ID = ' . $user_access['ID'];
        $this->set_query($sql);
        exit;
    }

    //	получение бинарных данны в файл
    function get_file($url, $proxy, $file)
    {
        $fp = fopen($file, 'w');
        $ch = curl_init($url);
        //	идем через proxy
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);                    //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);        //	время ожидания соединения в секундах
        //	получение только тела
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        fclose($fp);
        //	ошибки
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);
        curl_close($ch);
        if ( $error_code > 0 )
            return false;
        return true;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //	ЛОГИ
    function log_file($str)
    {
        $fp = fopen('logs/' . $this->log_file . '_log.log', 'a+');
        fputs($fp, date('[d.m.Y H:i:s] ') . $str . "\n");
        fclose($fp);
    }

    function __destruct()
    {
        unlink('logs/' . $this->cookie_file);
        $sql = "UPDATE ProxyList SET FlagBlock = 0 WHERE ID = " . $this->proxy_id;
        $this->set_query($sql);
        $this->proxy_id = 0;
        $this->proxy = '';
    }
}