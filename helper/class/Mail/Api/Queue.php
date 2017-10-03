<?php

/**
 * Отправка письма через очередь
 *
 * @package Zero.Api.Mail
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.09.08
 */
class Helper_Mail_Api_Queue extends Zero_Controller
{
    public function Action_POST()
    {
        $_REQUEST['Queue'] = [
            'Name' => 'Name',
            'Description' => 'Description',
        ];
        $_REQUEST['Email'] = [
            'Reply' => ['Name' => 'Reply', 'Email' => 'reply@mail.ru'],
            'From' => ['Name' => 'From', 'Email' => 'from@mail.ru'],
            'To' => [
                'Recipient@mail.ru' => 'NameRecipient',
            ],
            'Subject' => 'Тема сообщения',
            'Message' => 'Текст или тело сообщения',
        ];
        if ( empty($_REQUEST['Queue']) || empty($_REQUEST['Email']) )
            Zero_Response::JsonRestful(null, -1, ["Данные не переданы"], 409);

        $res = Helper_Mail::Queuing($_REQUEST['Queue']['Name'], $_REQUEST['Queue']['Description'], $_REQUEST['Email'], Helper_Mail::SendMessage);
        if ( 0 < $res )
            Zero_Response::JsonRestful();
        else
            Zero_Response::JsonRestful($res, -1, ["Ошибка постановки письма в очередь"], 409);
    }
}
