<?php
/**
 * File Language
 *
 * model =
 * model prop Status =
 * model prop Status validate key1 =
 * model prop Status validate key2 =
 * model prop Status option cold =
 * model prop Status option hot =
 *
 * controller Zero_Users_Grid =
 * controller Zero_Users_Grid action name1 =
 * controller Zero_Users_Grid action name2 =
 * controller Zero_Users_Grid message name1 =
 * controller Zero_Users_Grid message name2 =
 *
 * 'translation Key' => 'Translation Value'
 */
return [
    'model' => [
        'Property all' => 'Все свойства',
        'Error_Exists' => 'уже занято',
        'Error_ValidEmail' => 'Электронный адрес неправильный',
        'Error_NotRegistration' => 'не зарегистрирован',
        'Error_Keystring' => 'контрольная строка неправильна',
        'IsAccess options' => ['close' => 'закрыт', 'open' => 'открыт'],
        'IsCondition options' => ['no' => 'нет', 'yes' => 'да'],
        'IsOnline options' => ['no' => 'нет', 'yes' => 'да'],
    ],
    'view' => [
        'Login' => 'Логин',
        'Password' => 'Пароль',

    ],
    'controller' => [
        'Action_Login' => 'Авторизация',
        'Action_Reminder' => 'Восстановление пароля',
        'Action_Logout' => 'Выход',
        'Error_Registration' => 'Не зарегистрирован',
        'Error_Password' => 'Пароль не верен',
        'Action_CatalogMove' => 'Перемещение пользователя',
    ],
];
