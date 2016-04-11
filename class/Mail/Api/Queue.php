<?php

/**
 * Отправка письма через очередь
 *
 * @package Zero.Api.Mail
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.09.08
 */
class Zero_Mail_Api_Queue extends Zero_Controller
{
    public function Action_POST()
    {
        $sample = [
            'Queue' => [
                'Name' => 'Name',
                'Description' => 'Description',
            ],
            'Email' => [
                'Reply' => ['Name' => 'Reply', 'Email' => 'reply@mail.ru'],
                'From' => ['Name' => 'From', 'Email' => 'from@mail.ru'],
                'To' => [
                    'Recipient@mail.ru' => 'NameRecipient',
                ],
                'Subject' => 'Тема сообщения',
                'Message' => 'Текст или тело сообщения',
                'Attach' => [
                    'pathFile' => 'nameFile',
                ],
            ],
        ];
        if ( empty($_REQUEST['Queue']) || empty($_REQUEST['Email']) )
            Zero_App::ResponseJson500(-1, ["Данные не переданы"]);

        $res = Zero_Mail::Queuing($_REQUEST['Queue']['Name'], $_REQUEST['Queue']['Description'], $_REQUEST['Email'], Zero_Mail::SendMessage);
        if ( 0 < $res )
            Zero_App::ResponseJson200();
        else
            Zero_App::ResponseJson200($res, -1, ["Ошибка постановки письма в очередь"]);
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
     * @return Zero_Mail_Api_Queue
     */
    public static function Make($properties = [])
    {
        $Controller = new self();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }
}
