<?php

/**
 * Вспомогательный класс для обмена информацией и проведения операций с внешними ресурсами
 *
 * @package Helper.Curl
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.15
 */
class Helper_Curl
{
    /**
     * Путь до файла хранящего куку сессии
     *
     * @var string
     */
    protected $cookie_file = '';

    /**
     * Url главной страницы сайта к которому обращаемся
     *
     * @var string
     */
    protected $url = '';

    /**
     * Хост к которому обращаемся
     *
     * @var string
     */
    protected $host = '';

    /**
     * Прокси сервер (ip адресс)
     *
     * @var string
     */
    protected $proxy = '';

    /**
     * Для стандартной авторизации методом apache
     *
     * @var string
     */
    protected $apacheUser = '';

    /**
     * Для стандартной авторизации методом apache
     *
     * @var string
     */
    protected $apachePwd = '';

    /**
     * Для стандартной авторизации методом apache
     *
     * @var string
     */
    protected $proxyUser = '';

    /**
     * Для стандартной авторизации методом apache
     *
     * @var string
     */
    protected $proxyPwd = '';

    /**
     * Конструктор
     *
     * @param string $proxy Прокси сервер (ip адресс)
     */
    public function __construct($url, $proxy = '')
    {
        $arr = explode('/', $url);
        $this->url = $arr[0] . '//' . $arr[2] . '/';
        $this->host = $arr[2];
        $this->proxy = $proxy;
        $this->set_Cookie_file();
    }

    /**
     * Установка логина и пароля для авторизации методом apache
     *
     * @param $user
     * @param $pwd
     */
    public function Set_Auth_Apache($user, $pwd)
    {
        $this->apacheUser = $user;
        $this->apachePwd = $pwd;
    }

    /**
     * Установка логина и пароля для авторизации методом apache
     *
     * @param $user
     * @param $pwd
     */
    public function Set_Auth_Proxy($user, $pwd)
    {
        $this->proxyUser = $user;
        $this->proxyPwd = $pwd;
    }

    /**
     * Иницилизация файл-лога хранящего куку сессии
     *
     */
    private function set_Cookie_file()
    {
        if ( $this->cookie_file )
            unlink($this->cookie_file);
        do
        {
            sleep(1);
            $this->cookie_file = ZERO_PATH_EXCHANGE . '/cookie_' . zero_random_string('8', 'lower,upper,numbers') . '_' . date('d.m.Y_H.i.s') . '.txt';
        }
        while ( file_exists($this->cookie_file) );
        fclose(fopen($this->cookie_file, 'w'));
    }

    /**
     * Получение страницы ( text/html )
     *
     * @param string $url запрашиваемый url
     * @param array $postData key => value
     * @return Helper_Curl_Response
     */
    public function Get_ApiJson($url, $postData = [])
    {
        $response = $this->Get_Page($url, $postData);
        $response->Body = json_decode($response->Body, true);
        return $response;
    }

    /**
     * Получение страницы ( text/html )
     *
     * @param string $url запрашиваемый url
     * @param array $postData key => value
     * @return Helper_Curl_Response
     */
    public function Get_Page($url, $postData = [])
    {
        $ch = curl_init($url);
        //	идем через proxy
        if ( $this->proxy )
        {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            if ( $this->proxyUser && $this->proxyPwd )
            {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$this->proxyUser}:{$this->proxyPwd}");
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    //	время ожидания соединения в секундах
        //	Передаем и возвращаем Заголовки и тело страницы
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        //	Заголовки
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0");
        //	Referer (откуда пришли, с какой страницы)
        curl_setopt($ch, CURLOPT_REFERER, $url);
        //	Host
        $header_mas = array();
        $header_mas[] = "Host: " . $this->host;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_mas);
        //	Cookie
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);   //	посылка
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);    //	получение
        //	АВТОРИЗАЦИЯ МЕТОДОМ APACHE
        if ( $this->apacheUser && $this->apachePwd )
        {
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->apacheUser}:{$this->apachePwd}");
        }
        //	переадресация
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    //	переход по редиректу
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);         //	максимальное количество переадресаций
        //	запрос GET
        if ( 0 == count($postData) )
        {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        //	запрос POST
        else
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            //            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //	возвращаем результат в переменную
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);
        $response = new Helper_Curl_Response();
        //	ошибки
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);

        if ( 0 < $error_code )
        {
            Zero_Logs::Set_Message_Error('CURL: ' . $error_subj);
            curl_close($ch);
            $response->Head = [];
            $response->Body = '';
            $response->Flag = false;
        }
        else
        {
            curl_close($ch);
            $arr = explode("\n", $page);
            $response->Head = [];
            while ( $h = trim(array_shift($arr)) )
            {
                $response->Head[] = $h;
            }
            $response->Body = implode("\n", $arr);
            $response->Flag = true;
        }
        //	print curl_getinfo($ch,CURLINFO_HTTP_CODE).'<br>';
        return $response;
    }

    /**
     * Получение бинарных данных в файл
     *
     * @param string $url запрашиваемый url
     * @param string $file имя файла в который будет сохранен результат
     * @return bool
     */
    function Get_File($url, $file)
    {
        $fp = fopen($file, 'w');
        $ch = curl_init($url);
        //	идем через proxy
        if ( $this->proxy )
        {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        }
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    //	время ожидания соединения в секундах
        //	Cookie
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);   //	посылка
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);    //	получение
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

    /**
     * Завершение работы очистка
     */
    public function Finish()
    {
        if ( $this->cookie_file )
            unlink($this->cookie_file);
    }
}

/**
 * Возвращаемое значение
 *
 * @package Helper.Curl
 */
class Helper_Curl_Response
{
    /**
     * Правильный ответ
     *
     * @var boolean
     */
    public $Flag;

    /**
     * Заголовки ответа
     *
     * @var array
     */
    public $Head = [];

    /**
     * Тело ответа
     *
     * @var mixed
     */
    public $Body;
}