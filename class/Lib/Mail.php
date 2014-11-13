<?php

require_once dirname(dirname(__DIR__)) . '/library/PHPMailer/class.phpmailer.php';
/**
 * Lib. PHP email transport class
 *
 * @package Zero.Lib
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Lib_Mail
{
    /**
     * Отправка сообщения
     *
     * @param string $from from
     * @param string $to to
     * @param string $subject subject
     * @param string $message message
     * @param array $attach attachments
     * @return bool
     */
    public static function Send($from, $to, $subject, $message, $attach = [])
    {
        foreach (explode(';', $to) as $email)
        {
            //  Header mail
            $email = trim($email);
            $Mailer = new PHPMailer($from);
            //
            $Mailer->From = $from;
            $Mailer->FromName = '';
            $Mailer->Sender = $from;
            //
            $Mailer->Priority = 3;
            $Mailer->AddReplyTo($from);
            $Mailer->AddAddress($email);
            $Mailer->CharSet = 'UTF-8';
            //  The message body
            $Mailer->Subject = $subject;
                $Mailer->MsgHTML($message);
            //  Attachments
            if ( is_array($attach) )
            {
                foreach ($attach as $path => $name)
                {
                    $Mailer->AddAttachment($path, $name);
                }
            }
            //  Send
            if ( !$Mailer->Send() )
                Zero_Logs::Set_Message_Error("From: {$from}; To: {$email}; Subject: {$subject}");

            $Mailer->ClearAddresses();
            $Mailer->ClearAttachments();
        }
        return true;
    }
}