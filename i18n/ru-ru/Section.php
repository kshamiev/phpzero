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
	'model' => 'Разделы',
	'model prop ControllerConfig' => 'Контроллер в конфигурации',
	'model prop Layout' => 'Шаблон раздела (Макет)',
	'model prop Controller' => 'Пользователький контроллер',
	'model prop Controller validate Error_Path_Class' => 'Источник контроллера отсутсвует',
	'model prop Controller validate Error_Class_Exists' => 'Источник не содержит контроллера',
	'model prop ID' => 'Идентификатор',
	'model prop IsAuthorized' => 'Раздел авторизованный',
	'model prop IsAuthorized option no' => 'нет',
	'model prop IsAuthorized option yes' => 'да',
	'model prop IsVisible' => 'Видимость в навигации',
	'model prop IsVisible option no' => 'нет',
	'model prop IsVisible option yes' => 'да',
	'model prop IsEnable' => 'Раздел включен',
	'model prop IsEnable option no' => 'нет',
	'model prop IsEnable option yes' => 'да',
	'model prop ContentType' => 'Тип отдаваемого контента',
	'model prop ContentType option file' => 'файл',
	'model prop ContentType option img' => 'картинка',
	'model prop ContentType option json' => 'json',
	'model prop ContentType option xml' => 'xml',
    'model prop ContentType option html' => 'html',
	'model prop Name' => 'Название',
	'model prop Sort' => 'Сортировка',
	'model prop Url' => 'Абсолютная ссылка',
	'model prop UrlRedirect' => 'Редирект',
	'model prop UrlThis' => 'Относительная ссылка',
	'model prop Zero_Section_ID' => 'Родительский раздел',
    'model prop Description' => 'Описание',
    'model prop Keywords' => 'Ключи',
    'model prop Title' => 'Титул',
    'controller Zero_Section_Edit' => 'Изменение разделов',
    'controller Zero_Section_Edit action Add' => 'добавить',
    'controller Zero_Section_Edit action Save' => 'сохранить',
    'controller Zero_Section_Edit action Modules' => 'модуль',
    'controller Zero_Section_Edit message Error_Validate' => 'Ошибка валидации',
    'controller Zero_Section_Edit message Error_Save' => 'Ошибка сохранения',
    'controller Zero_Section_Edit message Save' => 'Сохранено',
    'controller Zero_Section_Grid' => 'Список разделов сайты постранично',
    'controller Zero_Section_Grid action Add' => 'добавить',
    'controller Zero_Section_Grid action Edit' => 'изменить',
    'controller Zero_Section_Grid action CatalogMove' => 'переместить',
    'controller Zero_Section_Grid action Remove' => 'удалить',
    'controller Zero_Section_Grid action UpdateUrl' => 'обновить роутинг',
    'controller Zero_Section_Grid action Default' => 'контроллер по умолчанию',
    'controller Zero_Section_Grid action FilterSet' => 'утановка фильтра',
    'controller Zero_Section_Grid action FilterReset' => 'сброс фильтра',
    'controller Zero_Section_Grid message Update_Url' => 'Роутинг обновлен',
    'controller Zero_Section_Grid message Error_Update_Url' => 'Ошибка обновления роутинга',
    'controller Zero_Section_Grid message Move' => 'Перемещено',
    'controller Zero_Section_Grid message Error_NotParam' => 'Нечего перемещать',
    'controller Zero_Section_Grid message Remove' => 'Удалено',
    'controller Zero_Section_Grid message Error_Remove' => 'Ошибка удаления',
    'translation Key' => 'Translation Value',
];