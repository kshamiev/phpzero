<?php
/**
 * PHP email transport class
 *
 * @package Zero.Lib
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */

require ZERO_PATH_LIBRARY . '/PHPMailer/PHPMailerAutoload.php';

class Zero_Lib_Mail
{
    /**
     * Отправка сообщения
     *
     * @param array $from from
     * @param array $to to
     * @param array $reply to
     * @param string $subject subject
     * @param string $message message
     * @param array $attach attachments
     * @return int количесвто ошибок отправления
     */
    public static function Send($from, $to, $reply, $subject, $message, $attach = [])
    {
        $cntFail = 0;
        foreach ($to as $row)
        {
            $mail = new PHPMailer;
            //  Header mail
            $mail->CharSet = 'utf-8';
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
                $mail->AddAttachment($path, $name);
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
     * @param array $from from
     * @param array $to to
     * @param array $reply to
     * @param string $subject subject
     * @param string $message message
     * @param array $attach attachments
     * @return int количесвто ошибок отправления
     */
    public static function SendAuth($from, $to, $reply, $subject, $message, $attach = [])
    {
        $cntFail = 0;
        foreach ($to as $row)
        {
            $mail = new PHPMailer;
            //  Header mail
            $mail->isSMTP();
            $mail->Debugoutput = 'html';
            $mail->Host = 'host';
            $mail->Port = 0;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "login";
            $mail->Password = "password";
            $mail->CharSet = 'utf-8';
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
                $mail->AddAttachment($path, $name);
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
}
