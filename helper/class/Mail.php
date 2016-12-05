<?php
require_once ZERO_PATH_LIBRARY . '/PHPMailer/PHPMailerAutoload.php';

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
 *
 * @property string $Name
 * @property string $Description
 * @property string $Content
 * @property string $Date
 * @property string $DateSend
 * @property int $RetryCnt
 */
class Helper_Mail
{
    /**
     * The table stores the objects this model
     *
     * @var string
     */
    protected $Source = 'MailQueue';

    /**
     * Возможные методы отправки почтового сообщения
     */
    const SendMessage = 'SendMessage';
    const SendMessageAuth = 'SendMessageAuth';
    const SendMessageAuthSsl = 'SendMessageAuthSsl';

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
            $cntFail = self::$method($data['Reply'], $data['From'], $data['To'], $data['Subject'], $data['Message'], $data['Attach']);
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
        $data = func_get_args();
        if ( 1 < count($data) )
        {
            $reply = $data[0];
            $from = $data[1];
            $to = $data[2];
            $subject = $data[3];
            $message = $data[4];
            $attach = isset($data[5]) ? $data[5] : [];
        }
        else
        {
            $reply = $data[0]['Reply'];
            $from = $data[0]['From'];
            $to = $data[0]['To'];
            $subject = $data[0]['Subject'];
            $message = $data[0]['Message'];
            $attach = isset($data[0]['Attach']) ? $data[0]['Attach'] : [];
        }
        $cntFail = 0;
        foreach ($to as $key => $row)
        {
            if ( !is_array($row) )
            {
                $row = [
                    'Email' => $key,
                    'Name' => $row,
                ];
            }
            $mail = new PHPMailer;
            //  Header mail
            $mail->CharSet = Zero_App::$Config->Mail_CharSet;
            //
            $mail->setFrom($from['Email'], $from['Name']);
            $mail->addReplyTo($reply['Email'], $reply['Name']);
            $mail->addAddress($row['Email'], $row['Name']);
            //  The message body
            $mail->Subject = $subject;
            $mail->msgHTML($message);
            $mail->AltBody = $message;
            //  Attachments
            foreach ($attach as $path => $name)
            {
                $mail->addAttachment($path, $name);
            }
            //  Send
            if ( !$mail->send() )
            {
                $cntFail++;
                Zero_Logs::Set_Message_Error("From: {$from['Email']}; To: {$row['Email']}; Subject: {$subject}");
            }
            //
            $mail->ClearAddresses();
            $mail->ClearAttachments();
        }
        return $cntFail;
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
        $data = func_get_args();
        if ( 1 < count($data) )
        {
            $reply = $data[0];
            $from = $data[1];
            $to = $data[2];
            $subject = $data[3];
            $message = $data[4];
            $attach = isset($data[5]) ? $data[5] : [];
        }
        else
        {
            $reply = $data[0]['Reply'];
            $from = $data[0]['From'];
            $to = $data[0]['To'];
            $subject = $data[0]['Subject'];
            $message = $data[0]['Message'];
            $attach = isset($data[0]['Attach']) ? $data[0]['Attach'] : [];
        }
        $cntFail = 0;
        foreach ($to as $key => $row)
        {
            if ( !is_array($row) )
            {
                $row = [
                    'Email' => $key,
                    'Name' => $row,
                ];
            }
            $mail = new PHPMailer;
            //  Header mail
            $mail->isSMTP();
            $mail->Debugoutput = 'html';
            $mail->Host = Zero_App::$Config->Mail_Host;
            $mail->Port = Zero_App::$Config->Mail_Port;
            $mail->SMTPSecure = '';
            $mail->SMTPAuth = true;
            $mail->Username = Zero_App::$Config->Mail_Username;
            $mail->Password = Zero_App::$Config->Mail_Password;
            $mail->CharSet = Zero_App::$Config->Mail_CharSet;

            //
            $mail->setFrom($from['Email'], $from['Name']);
            $mail->addReplyTo($reply['Email'], $reply['Name']);
            $mail->addAddress($row['Email'], $row['Name']);
            //  The message body
            $mail->Subject = $subject;
            $mail->msgHTML($message);
            $mail->AltBody = $message;
            //  Attachments
            foreach ($attach as $path => $name)
            {
                $mail->addAttachment($path, $name);
            }
            //  Send
            if ( !$mail->send() )
            {
                $cntFail++;
                Zero_Logs::Set_Message_Error("From: {$from['Email']}; To: {$row['Email']}; Subject: {$subject}");
            }
            //
            $mail->ClearAddresses();
            $mail->ClearAttachments();
        }
        return $cntFail;
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
        $data = func_get_args();
        if ( 1 < count($data) )
        {
            $reply = $data[0];
            $from = $data[1];
            $to = $data[2];
            $subject = $data[3];
            $message = $data[4];
            $attach = isset($data[5]) ? $data[5] : [];
        }
        else
        {
            $reply = $data[0]['Reply'];
            $from = $data[0]['From'];
            $to = $data[0]['To'];
            $subject = $data[0]['Subject'];
            $message = $data[0]['Message'];
            $attach = isset($data[0]['Attach']) ? $data[0]['Attach'] : [];
        }
        $cntFail = 0;
        foreach ($to as $key => $row)
        {
            if ( !is_array($row) )
            {
                $row = [
                    'Email' => $key,
                    'Name' => $row,
                ];
            }
            $mail = new PHPMailer;
            //  Header mail
            $mail->isSMTP();
            $mail->Debugoutput = 'html';
            $mail->Host = Zero_App::$Config->Mail_Host;
            $mail->Port = Zero_App::$Config->Mail_Port;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = Zero_App::$Config->Mail_Username;
            $mail->Password = Zero_App::$Config->Mail_Password;
            $mail->CharSet = Zero_App::$Config->Mail_CharSet;
            //
            $mail->setFrom($from['Email'], $from['Name']);
            $mail->addReplyTo($reply['Email'], $reply['Name']);
            $mail->addAddress($row['Email'], $row['Name']);
            //  The message body
            $mail->Subject = $subject;
            $mail->msgHTML($message);
            $mail->AltBody = $message;
            //  Attachments
            foreach ($attach as $path => $name)
            {
                $mail->addAttachment($path, $name);
            }
            //  Send
            if ( !$mail->send() )
            {
                $cntFail++;
            }
            //
            $mail->ClearAddresses();
            $mail->ClearAttachments();
        }
        return $cntFail;
    }
}