<?php

/**
 * Запросы к внешним источникам (службам, сервисам)
 *
 * Компонент
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017-09-14
 *
 * @method Test
 */
class Zero_Request
{
    /**
     * API запрос к биллингу
     *
     * @param array $postData
     * @return array
     */
    private function request($method ='', $content = '', $access = '')
    {
        pre(__FUNCTION__, __METHOD__);
        /*
         * Доступы получаются через ключевое слово = имя метода или через последний параметр
         * Поиск реквизитов ведтся либо по БД либо через конфигурационный блок AccessApi
         *
         */





        // $content = json_encode($content, JSON_PRESERVE_ZERO_FRACTION);
        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        $ch = curl_init('https://dev.hostkey.ru/api/test.php');
        //	время работы
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);          //	полное время сеанса
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);    //	время ожидания соединения в секундах
        //	Передаем и возвращаем Заголовки и тело страницы
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        //	Заголовки
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json; charset=utf-8",
            "Content-Length: " . strlen($content),
            "AuthUser: " . md5('funtik'),
        ]);
        //	АВТОРИЗАЦИЯ МЕТОДОМ APACHE
        if ( true )
        {
            curl_setopt($ch, CURLOPT_USERPWD, "dev:dev");
        }
        // Метод запроса и тело запроса
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        //	возвращаем результат в переменную
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if ( true )
        {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, fopen(ZERO_PATH_LOG . '/curl.log', 'a'));
        }
        // Запрос
        $body = curl_exec($ch);
        $head = curl_getinfo($ch);
        $error_code = curl_errno($ch);
        $error_subj = curl_error($ch);
        if ( 0 < $error_code )
        {
            Zero_Logs::Set_Message_ErrorTrace('Curl error: ' . $error_code . ' - ' . $error_subj);
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
     * Метод перегрузки
     *
     * @param string $method имя вызываемого метода
     * @param array $params массив передаваемых параметров
     * @return array ответ
     */
    public function __call($method, $params)
    {
        if ( empty($params[0]) )
        {
            $params[0] = [];
        }
        $params[0]['action'] = str_replace('_', '', $method);
        return $this->request($params[0]);
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