<?php

/**
 * Работа с почтой (почтовыми сообщениями).
 *
 * Поставнока в очередь на отправку.
 * Получение очереди для отправления по почте.
 *
 * @package Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 * @link https://github.com/PHPMailer/PHPMailer
 */
class Helper_Mail
{
    /**
     * Возможные методы отправки почтового сообщения
     */
    const SendMessage = 'SendMessage';
    const SendMessageAuth = 'SendMessageAuth';
    const SendMessageAuthSsl = 'SendMessageAuthSsl';

    /**
     *
     * @var string $smtp_username - логин
     * @var string $smtp_password - пароль
     * @var string $smtp_host - хост
     * @var string $smtp_from - от кого
     * @var integer $smtp_port - порт
     * @var string $smtp_charset - кодировка
     *
     */
    private $smtp_username;

    private $smtp_password;

    private $smtp_host;

    private $smtp_port;

    private $smtp_charset;

    public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_port = 25, $smtp_charset = "UTF-8")
    {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;
    }

    /**
     * Отправка письма
     *
     * @param array $data = [
     * 'From' => ['Name' => 'From', 'Email' => 'from@mail.ru'],
     * 'To' => 'Recipient@mail.ru',
     * 'Subject' => 'Тема сообщения',
     * 'Message' => 'Текст или тело сообщения',
     * ];
     * @param string $headers - заголовки письма
     * @return bool|string В случаи отправки вернет true, иначе текст ошибки
     */
    public function Sending($data, $headers = '')
    {
        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?' . base64_encode($data['Subject']) . "=?=\r\n";
        $contentMail .= "MIME-Version: 1.0\r\n";
        $contentMail .= "Content-type: text/html; charset={$this->smtp_charset}\r\n";
        $contentMail .= "Content-Transfer-Encoding: 8bit\r\n";
        $contentMail .= "From: {$data['From']['Name']} <{$data['From']['Email']}>\r\n";
        $contentMail .= $headers . "\r\n";
        $contentMail .= $data['Message'] . "\r\n";

        try
        {
            if ( !$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30) )
            {
                throw new Exception($errorNumber . "." . $errorDescription, 409);
            }
            if ( !$this->_parseServer($socket, "220") )
            {
                throw new Exception('Connection error', 409);
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fputs($socket, "HELO $server_name\r\n");
            if ( !$this->_parseServer($socket, "250") )
            {
                fclose($socket);
                throw new Exception('Error of command sending: HELO', 409);
            }

            fputs($socket, "AUTH LOGIN\r\n");
            if ( !$this->_parseServer($socket, "334") )
            {
                fclose($socket);
                throw new Exception('Autorization error', 409);
            }

            fputs($socket, base64_encode($this->smtp_username) . "\r\n");
            if ( !$this->_parseServer($socket, "334") )
            {
                fclose($socket);
                throw new Exception('Autorization error', 409);
            }

            fputs($socket, base64_encode($this->smtp_password) . "\r\n");
            if ( !$this->_parseServer($socket, "235") )
            {
                fclose($socket);
                throw new Exception('Autorization error', 409);
            }

            fputs($socket, "MAIL FROM: <" . $this->smtp_username . ">\r\n");
            if ( !$this->_parseServer($socket, "250") )
            {
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM', 409);
            }

            $data['To'] = ltrim($data['To'], '<');
            $data['To'] = rtrim($data['To'], '>');
            fputs($socket, "RCPT TO: <" . $data['To'] . ">\r\n");
            if ( !$this->_parseServer($socket, "250") )
            {
                fclose($socket);
                throw new Exception('Error of command sending: RCPT TO', 409);
            }

            fputs($socket, "DATA\r\n");
            if ( !$this->_parseServer($socket, "354") )
            {
                fclose($socket);
                throw new Exception('Error of command sending: DATA', 409);
            }

            fputs($socket, $contentMail . "\r\n.\r\n");
            if ( !$this->_parseServer($socket, "250") )
            {
                fclose($socket);
                throw new Exception("E-mail didn't sent", 409);
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } catch ( Exception $e )
        {
            return $e->getMessage();
        }
        return true;
    }

    private function _parseServer($socket, $response)
    {
        $responseServer = '';
        while ( @substr($responseServer, 3, 1) != ' ' )
        {
            if ( !($responseServer = fgets($socket, 256)) )
            {
                return false;
            }
        }
        if ( !(substr($responseServer, 0, 3) == $response) )
        {
            return false;
        }
        return true;
    }

    /**
     * Постановка почтового сообщения в очередь
     *
     * @param string $name краткое название сообщения для идетификации
     * @param string $description подробное описание сообщения для идетификации
     * @param mixed $content сообщение:
     * @sample: $content = [
     * 'Reply' => ['Name' => '', 'Email' => 'reply@reply.ru'],
     * 'From' => ['Name' => '', 'Email' => 'from@from.ru'],
     * 'To' => [['Name' => '', 'Email' => 'to@to.ru']],
     * 'Subject' => 'Тема сообщения',
     * 'Message' => 'Текст или тело сообщения',
     * 'Attach' => [],
     * ];
     * @param string $method метод реализующий отправку почты
     * @return int
     */
    public static function Queuing($name, $description, $content, $method = self::SendMessage)
    {
        $sql = "INSERT MailQueue SET
            Name = " . Zero_DB::EscT($name) . ",
            Description = " . Zero_DB::EscT($description) . ",
            Content = '" . json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "',
            Date = NOW(),
            RetryCnt = 0,
            Method = " . Zero_DB::EscT($method) . "
        ";
        return Zero_DB::Insert($sql);
    }

    /**
     * Отправка очереди почтовых сообщений адресатам.
     */
    public static function Send()
    {
        $sql = "
        SELECT
          *
        FROM MailQueue
        WHERE
          DateSend IS NULL
          AND RetryCnt < " . Zero_App::$Config->Mail_RetryCnt . "
        ORDER BY
          Date ASC
        LIMIT 0, 100
        ";
        $rows = Zero_DB::Select_Array_Index($sql);
        foreach ($rows as $id => $row)
        {
            $data = json_decode($row["Content"], true);
            if ( !method_exists(__CLASS__, $method = $row['Method']) )
                $method = self::SendMessage;
            $cntFail = self::$method($data);
            if ( 0 < $cntFail )
                $sql = "UPDATE MailQueue SET RetryCnt = RetryCnt + 1 WHERE Id = {$id}";
            else
                $sql = "UPDATE MailQueue SET RetryCnt = RetryCnt + 1, DateSend = NOW() WHERE Id = {$id}";
            Zero_DB::Update($sql);
        }
    }

    /**
     * Отправка сообщения
     *
     * @param array $data [
     *      'Reply' => ['Name' => 'ReplyName', 'Email' => 'reply@mail.ru'],
     *      'From' => ['Name' => 'FromName', 'Email' => 'from@mail.ru'],
     *      'To' => [
     *          'Recipient@mail.ru' => 'NameRecipient',
     *      ],
     *      'Subject' => 'Тема сообщения',
     *      'Message' => 'Текст или тело сообщения',
     *      'Attach' => [
     *          'pathFile' => 'nameFile',
     *      ],
     * ]
     * @return int количесвто ошибок отправления
     */
    public static function SendMessage($data)
    {
        $errorCnt = 0;
        $dataT = [
            'From' => ['Name' => $data['From']['Name'], 'Email' => $data['From']['Email']],
            'To' => '',
            'Subject' => $data['Subject'],
            'Message' => nl2br($data['Message']),
        ];
        $mailSMTP = new Helper_Mail(Zero_App::$Config->Mail_Username, Zero_App::$Config->Mail_Password, Zero_App::$Config->Mail_Host, Zero_App::$Config->Mail_Port);
        foreach($data['To'] as $key=>$val)
        {
            $dataT['To'] = $key;
            $result = $mailSMTP->Sending($dataT);
            if ( $result !== true )
            {
                $errorCnt++;
                Zero_Logs::Set_Message_Error($result);
            }
        }
        return $errorCnt;
    }

    /**
     * Отправка сообщения
     *
     * @param array $data [
     *      'Reply' => ['Name' => 'ReplyName', 'Email' => 'reply@mail.ru'],
     *      'From' => ['Name' => 'FromName', 'Email' => 'from@mail.ru'],
     *      'To' => [
     *          'Recipient@mail.ru' => 'NameRecipient',
     *      ],
     *      'Subject' => 'Тема сообщения',
     *      'Message' => 'Текст или тело сообщения',
     *      'Attach' => [
     *          'pathFile' => 'nameFile',
     *      ],
     * ]
     * @return int количесвто ошибок отправления
     */
    public static function SendMessageAuth($data)
    {
        $errorCnt = 0;
        $dataT = [
            'From' => ['Name' => $data['From']['Name'], 'Email' => $data['From']['Email']],
            'To' => '',
            'Subject' => $data['Subject'],
            'Message' => nl2br($data['Message']),
        ];
        $mailSMTP = new Helper_Mail(Zero_App::$Config->Mail_Username, Zero_App::$Config->Mail_Password, Zero_App::$Config->Mail_Host, Zero_App::$Config->Mail_Port);
        foreach($data['To'] as $key=>$val)
        {
            $dataT['To'] = $key;
            $result = $mailSMTP->Sending($dataT);
            if ( $result !== true )
            {
                $errorCnt++;
                Zero_Logs::Set_Message_Error($result);
            }
        }
        return $errorCnt;
    }

    /**
     * Отправка сообщения
     *
     * @param array $data [
     *      'Reply' => ['Name' => 'ReplyName', 'Email' => 'reply@mail.ru'],
     *      'From' => ['Name' => 'FromName', 'Email' => 'from@mail.ru'],
     *      'To' => [
     *          'Recipient@mail.ru' => 'NameRecipient',
     *      ],
     *      'Subject' => 'Тема сообщения',
     *      'Message' => 'Текст или тело сообщения',
     *      'Attach' => [
     *          'pathFile' => 'nameFile',
     *      ],
     * ]
     * @return int количесвто ошибок отправления
     */
    public static function SendMessageAuthSsl($data)
    {
        $errorCnt = 0;
        $dataT = [
            'From' => ['Name' => $data['From']['Name'], 'Email' => $data['From']['Email']],
            'To' => '',
            'Subject' => $data['Subject'],
            'Message' => nl2br($data['Message']),
        ];
        $mailSMTP = new Helper_Mail(Zero_App::$Config->Mail_Username, Zero_App::$Config->Mail_Password, Zero_App::$Config->Mail_Host, Zero_App::$Config->Mail_Port);
        foreach($data['To'] as $key=>$val)
        {
            $dataT['To'] = $key;
            $result = $mailSMTP->Sending($dataT);
            if ( $result !== true )
            {
                $errorCnt++;
                Zero_Logs::Set_Message_Error($result);
            }
        }
        return $errorCnt;
    }
}