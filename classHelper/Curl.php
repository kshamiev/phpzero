<?php

/**
 * Вспомогательный класс для обмена информацией и проведения операций с внешними ресурсами
 *
 * @package Zero.Helper
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.15
 */
class Zero_Helper_Curl
{
    /**
     * Заголовки ответа
     *
     * @var string
     */
    public $Head = [];

    /**
     * Тело ответа
     *
     * @var string
     */
    public $Body = '';

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
     * Конструктор
     *
     * @param string $url (http://www.odnoklassniki.ru/)
     * @param string $host (www.odnoklassniki.ru)
     * @param string $proxy Прокси сервер (ip адресс)
     */
    public function __construct($url, $host, $proxy = '')
    {
        $this->url = $url;
        $this->host = $host;
        $this->proxy = $proxy;
        $this->Set_Cookie_file();
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
     * Иницилизация файл-лога хранящего куку сессии
     *
     */
    protected function Set_Cookie_file()
    {
        if ( $this->cookie_file )
            unlink($this->cookie_file);
        do
        {
            sleep(1);
            $this->cookie_file = ZERO_PATH_EXCHANGE . '/cookie_' . $this->host . '_' . zero_random_string('8', 'lower,upper,numbers') . '_' . date('d.m.Y_H.i.s') . '.txt';
        }
        while ( file_exists($this->cookie_file) );
        fclose(fopen($this->cookie_file, 'w'));
    }

    /**
     * Получение страницы ( text/html )
     *
     * @param string $url запрашиваемый url
     * @param array $postData key => value
     * @return bool
     */
    public function Get_Page($url, $postData = [])
    {
        $ch = curl_init($url);
        //	идем через proxy
        if ( $this->proxy )
        {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            // curl_setopt($ch,CURLOPT_PROXYUSERPWD,'user:password');
        }
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);    //	время ожидания соединения в секундах
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
        //	ошибки
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);
        if ( 0 < $error_code )
        {
            Zero_Logs::Set_Message_Error('CURL: ' . $error_subj);
            curl_close($ch);
            $this->Head = [];
            $this->Body = '';
            return false;
        }
        else
        {
            curl_close($ch);
            $arr = explode("\n", $page);
            $this->Head = [];
            while ( $h = trim(array_shift($arr)) )
            {
                $this->Head[] = $h;
            }
            $this->Body = implode("\n", $arr);
            return true;
        }
        //	print curl_getinfo($ch,CURLINFO_HTTP_CODE).'<br>';
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);    //	время ожидания соединения в секундах
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