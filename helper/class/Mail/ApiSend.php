<?php

/**
 * Прямая отправка письма
 *
 * @package Zero.Api.Mail
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.09.08
 */
class Helper_Mail_ApiSend extends Zero_Controller
{
    public function Action_POST()
    {
        $Email = [
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
        ];
        if ( empty($_REQUEST['Email']) )
            Zero_App::ResponseJson500(-1, ["Данные не переданы"]);

        $res = Helper_Mail::SendMessage($_REQUEST['Email']);
        if ( 0 < $res )
            Zero_App::ResponseJson500(-1, ["Ошибка отправки письма"]);
        else
            Zero_App::ResponseJson200();
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
     * @return Helper_Mail_ApiSend
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
