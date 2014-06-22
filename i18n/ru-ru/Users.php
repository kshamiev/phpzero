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
        'IsAccess close' => 'закрыт',
        'IsAccess open' => 'открыт',
        'IsCondition no' => 'нет',
        'IsCondition yes' => 'да',
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
return [
    'model' => 'Пользователи',
    'model prop Date' => 'Дата регистрации',
    'model prop DateOnline' => 'Дата посещения',
    'model prop Email' => 'Email',
    'model prop Email validate Error_NotValid' => 'Поле заполнено не корректно',
    'model prop Email validate Error_NotRegistration' => 'Адрес не зарегистрирован',
    'model prop Email validate Error_Registration' => 'Адрес уже занят',
    'model prop ID' => 'Идентификатор',
    'model prop ImgAvatar' => 'Фото - Аватар',
    'model prop ImgAvatar validate Error Upload File' => 'Ошибка загрузки файла',
    'model prop ImgAvatar validate Error Image Resize' => 'Ошибка обработки картинки',
    'model prop IsAccess' => 'Статус доступа',
    'model prop IsAccess option close' => 'закрыто',
    'model prop IsAccess option open' => 'открыто',
    'model prop IsCondition' => 'Условие пользователя',
    'model prop IsCondition option no' => 'нет',
    'model prop IsCondition option yes' => 'да',
    'model prop IsOnline' => 'Статус присутствия',
    'model prop IsOnline option no' => 'нет',
    'model prop IsOnline option yes' => 'да',
    'model prop Keystring' => 'Контрольная строка',
    'model prop Keystring validate Error_Keystring' => 'Контрольная строка не совпадает',
    'model prop Login' => 'Логин',
    'model prop Login validate Error_Exists' => 'Логин уже занят',
    'model prop Name' => 'ФИО',
    'model prop Password' => 'Пароль',
    'model prop PasswordR' => 'Пароль еще раз',
    'model prop Password validate Error_PasswordValid' => 'Пароли не совпадают',
    'model prop PasswordR validate Error_PasswordValid' => 'Пароли не совпадают',
    'model prop Phone' => 'Телефон',
    'model prop Skype' => 'Скайп',
    'model prop Zero_Groups_ID' => 'Группа',
    'model prop Zero_Users_ID' => 'Пользователь',
	'controller Zero_Users_Edit' => 'Изменение пользователей',
	'controller Zero_Users_Edit action Add' => 'добавить',
	'controller Zero_Users_Edit action Save' => 'сохранить',
	'controller Zero_Users_Edit action Default' => 'контроллер по умолчанию',
	'controller Zero_Users_Edit message Error_Validate' => 'Ошибка валидации',
	'controller Zero_Users_Edit message Error_Save' => 'Ошибка сохранения',
	'controller Zero_Users_Edit message Save' => 'Сохранено',
	'controller Zero_Users_Grid' => 'Список пользоватиелей постранично',
	'controller Zero_Users_Grid action Add' => 'добавить',
	'controller Zero_Users_Grid action Edit' => 'изменить',
    'controller Zero_Users_Grid action CatalogMove' => 'переместить',
	'controller Zero_Users_Grid action Remove' => 'удалить',
	'controller action Profile' => 'профиль',
	'controller Zero_Users_Grid action Default' => 'контроллер по умолчанию',
	'controller Zero_Users_Grid action FilterSet' => 'установка фильтра',
	'controller Zero_Users_Grid action FilterReset' => 'сброс фильтра',
	'controller Zero_Users_Grid message Move' => 'Перемещено',
    'controller Zero_Users_Grid message Error_NotParam' => 'Нечего перемещать',
	'controller Zero_Users_Grid message Remove' => 'Удалено',
	'controller Zero_Users_Grid message Error_Remove' => 'Ошибка удаления',
	'controller Zero_Users_Login' => 'Авторизация пользователя',
	'controller action Login' => 'авторизация',
	'controller action Logout' => 'выход',
	'controller Zero_Users_Login message Error_Registration' => 'Незарегистрирован',
	'controller Zero_Users_Login message Error_Password' => 'Пароль не верен',
	'controller Zero_Users_Login message Error_Groups' => 'Акаунт ни входит ни в одну группу',
    'controller Zero_Users_Login action Default' => 'Контроллер по умолчанию',
    'controller Zero_Users_Login action Reminder' => 'Восстановление пароля',
    'controller Zero_Users_Login action Offline' => 'Оффлайн статус пользователей',
	'controller Zero_Users_Offline' => 'Инициализация онлайн статуса не активных пользователей.',
	'controller Zero_Users_Profile' => 'Профиль пользовтаеля.',
	'controller Zero_Users_Profile message Error_Validate' => 'Ошибка валидации',
	'controller Zero_Users_Profile message Profile' => 'Профиль изменен',
    'controller Zero_Users_Profile action Default' => 'Контроллер по умолчанию',
	'controller Zero_Users_Registration' => 'Регистрация нового пользовтаеля',
	'controller Zero_Users_Registration action Registration' => 'регистрация нового пользователя.',
	'controller Zero_Users_Registration message Error_Validate' => 'Ошибка валидации',
	'controller Zero_Users_Registration message Registration' => 'Новый пользователь зарегистрирован',
	'controller Zero_Users_Reminder' => 'Восстановление реквизитов пользователя',
	'controller Zero_Users_Reminder action Reminder' => 'восстановление реквизитов пользователя.',
	'controller Zero_Users_Reminder message Error_Validate' => 'Ошибка валидации',
	'controller Zero_Users_Reminder message Reminder' => 'Новые реквизиты сохранены и отправлены на почту',
    'translation Img View' => 'Просмотр картинки',
    'translation File View' => 'Просмотр файла',
    'translation Remove' => 'Удалить',
    'translation button profile' => 'Изменить профиль',
    'translation profile' => 'Профиль',
    'translation exit' => 'выход',
    'translation Reminder' => 'Напомнить',
    'translation Registration' => 'Регистрация',
    'translation Login' => 'Логин',
    'translation Enter' => 'Вход',
    'translation Password' => 'Пароль',
    'translation KeystringComment' => 'Введите строку с картинки:',
    'translation KeystringHelp' => 'Клините по картинке, если ен читается:',
    'translation enter' => 'Translation Value',
    'translation Key' => 'Translation Value',
];