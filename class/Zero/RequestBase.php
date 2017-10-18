<?php

/**
 * Запросы к внешним источникам (службам, сервисам)
 *
 * Расширяемый компонент
 * Путем добавления конфигураций и указания методов ниже в комментарии
 * Либо чере методы геттеры
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017-09-14
 *
 * @method Zero_Request_Type Simple($method, $urn, $content = null, $headers = []) Прямые запросы без конфигурации
 */
class Zero_RequestBase
{
    /**
     * Конструктор. Инициализация реквизитов доуступа к внешним ресурсам
     *
     * @param bool $IsDB загружать ли опции из БД
     */
    public function __construct($IsDB = false)
    {
        if ( $IsDB )
        {
            $sql = "SELECT AccessMethod, `Name`, Url, Login, Password, AuthUserToken, IsDebug FROM AccessOutside";
            foreach (Zero_DB::Select_Array_Index($sql) as $key => $row)
            {
                Zero_App::$Config->AccessOutside[$key] = $row;
            }
        }
    }

    /**
     * API запрос к внешнему ресурсу
     *
     * @param string $access
     * @param string $method
     * @param string $uri
     * @param mixed $content
     * @param array $headers список заголовков
     * @return Zero_Request_Type
     */
    private function request($access, $method, $uri, $content = null, $headers = [])
    {
        if ( empty(Zero_App::$Config->AccessOutside[$access]) )
        {
            Zero_Logs::Set_Message_Error("Реквизиты метода {$access} для запросов не определены");
            return new Zero_Request_Type;
        }
        $access = Zero_App::$Config->AccessOutside[$access];

        // $content = json_encode($content, JSON_PRESERVE_ZERO_FRACTION);
        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        $ch = curl_init($access['Url'] . $uri);
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);    //	время ожидания соединения в секундах
        //	Передаем и возвращаем Заголовки и тело страницы
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        //	Заголовки
        $headers[] = "Content-Type: application/json; charset=utf-8";
        $headers[] = "Content-Length: " . strlen($content);
        if ( isset($access['AuthUserToken']) && $access['AuthUserToken'] )
            $headers[] = "AuthUserToken: " . $access['AuthUserToken'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //	АВТОРИЗАЦИЯ МЕТОДОМ APACHE
        if ( isset($access['Login']) && $access['Login'] && isset($access['Password']) && $access['Password'] )
        {
            curl_setopt($ch, CURLOPT_USERPWD, $access['Login'] . ":" . $access['Password']);
        }
        // Метод запроса и тело запроса
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        //	возвращаем результат в переменную
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if ( isset($access['IsDebug']) && $access['IsDebug'] )
        {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, fopen(ZERO_PATH_LOG . "/curl_{$access['AccessMethod']}.log", 'a'));
        }
        // Запрос
        $body = curl_exec($ch);
        $head = curl_getinfo($ch);
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);
        if ( 0 < $error_code )
        {
            Zero_Logs::Set_Message_Error('Curl error: ' . $error_code . ' - ' . $error_subj);
            return new Zero_Request_Type;
        }
        curl_close($ch);
        // Заголовки
        switch ( $head['http_code'] )
        {
            case '201':
                break;
            case '400':
                break;
            case '401':
                break;
            case '409':
                break;
            case '503':
                break;
            default:
                break;
        }
        // Данные
        $typ = explode(' ', $head['content_type']);
        if ( $typ[0] = 'application/json;' )
        {
            $body = json_decode($body, true);
        }
        //
        $response = new Zero_Request_Type;
        $response->Head = $head;
        $response->Body = $body;
        return $response;
    }

    /**
     * Геттер для реализации пользовательских классов - запросов
     *
     * @param string $prop
     * @return mixed
     */
    public function __get($prop)
    {
        if ( method_exists($this, $method = 'Get_' . $prop) )
            return $this->$method();
        else
            Zero_Logs::Set_Message_Error("обращение к нереализованному функционалу запросов '{$prop}'");
        return null;
    }

    /**
     * Метод перегрузки
     *
     * @param string $method имя вызываемого метода
     * @param array $params массив передаваемых параметров
     * @return array ответ
     */
    public function __call($method, $params)
    {
        if ( empty($params[2]) )
            $params[2] = null; // $content
        if ( empty($params[3]) )
            $params[3] = []; // $headers
        return $this->request($method, $params[0], $params[1], $params[2], $params[3]);
    }
}

/**
 * Тип ответа запроса
 */
class Zero_Request_Type
{
    public $Head = [];

    public $Body = [];
}