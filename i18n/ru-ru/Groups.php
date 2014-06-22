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
        'ID' => 'ID',
        'Name' => 'Название',
        'Description' => 'Описание',
        'Status' => 'Статус',
        'Status close' => 'закрыт',
        'Status open' => 'открыт',
    ],
    'view' => [

    ],
    'controller' => [
        'Action_Copy' => '',

    ],
];

return [
    'model' => 'Группы',
    'model prop Description' => 'Описание',
    'model prop ID' => 'Идентификатор',
    'model prop Name' => 'Название',
    'model prop Status' => 'Статус доступа',
    'model prop Status option close' => 'закрыто',
    'model prop Status option open' => 'открыто',
	'controller Zero_Groups_Access' => 'Управление правами доступа или ролями',
	'controller Zero_Groups_Access action Copy' => 'копировать права доступа',
	'controller Zero_Groups_Access action Save' => 'сохранить права доступа',
	'controller Zero_Groups_Access message RoleAccess' => 'Права сохранены',
	'controller Zero_Groups_Access message AccessCopy' => 'Права скопированы',
	'controller Zero_Groups_Edit' => 'Изменение групп',
    'controller Zero_Groups_Edit action Add' => 'добавить',
    'controller Zero_Groups_Edit action Save' => 'сохранить',
    'controller Zero_Groups_Edit action Default' => 'контроллер по умолчанию',
    'controller Zero_Groups_Edit message Error_Validate' => 'Ошибка валидации',
    'controller Zero_Groups_Edit message Error_Save' => 'Ошибка сохранения',
    'controller Zero_Groups_Edit message Save' => 'Сохранено',
	'controller Zero_Groups_Grid' => 'Список групп постранично',
	'controller Zero_Groups_Grid action Add' => 'добавить',
	'controller Zero_Groups_Grid action Edit' => 'изменить',
	'controller Zero_Groups_Grid action Default' => 'контроллер по умолчанию',
	'controller Zero_Groups_Grid action FilterSet' => 'установка фильтра',
	'controller Zero_Groups_Grid action FilterReset' => 'сброс фильтра',
	'controller Zero_Groups_Grid action Remove' => 'удалить',
    'controller Zero_Groups_Grid message Remove' => 'Удалено',
    'controller Zero_Groups_Grid message Error_Remove' => 'Ошибка удаления',
    'translation Key' => 'Translation Value',
];