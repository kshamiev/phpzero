<?php

/**
 * Прямая отправка письма
 *
 * @package Zero.Api.Mail
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.09.08
 */
class Helper_Mail_Api_Send extends Zero_Controller
{
    public function Action_POST()
    {
        $_REQUEST['Email'] = [
            'Reply' => ['Name' => 'Reply', 'Email' => 'reply@mail.ru'],
            'From' => ['Name' => 'From', 'Email' => 'from@mail.ru'],
            'Subject' => 'Тема сообщения',
            'Message' => 'Текст или тело сообщения',
            'To' => [
                'Recipient@mail.ru' => 'NameRecipient',
            ],
            'Attach' => [
                'pathFile' => 'nameFile',
            ],
        ];
        if ( empty($_REQUEST['Email']) )
            Zero_Response::JsonRest(null, -1, ["Данные не переданы"], 409);

        $res = Helper_Mail::SendMessage($_REQUEST['Email']);
        if ( 0 < $res )
            Zero_Response::JsonRest(null, -1, ["Ошибка отправки письма"], 409);
        else
            Zero_Response::JsonRest();
    }
}
