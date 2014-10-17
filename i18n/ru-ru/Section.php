<?php
/**
 * Created by PhpStorm.
 * User: Константин
 * Date: 12.05.14
 * Time: 0:34
 */
return [
    'model' => [
        'ID' => 'Идентификатор',
        'IsAuthorized' => 'Раздел авторизованный',
        'IsAuthorized options' => ['no' => 'нет', 'yes' => 'да'],
        'IsVisible' => 'Видимость в навигации',
        'IsVisible options' => ['no' => 'нет', 'yes' => 'да'],
        'IsEnable' => 'Раздел включен',
        'IsEnable options' => ['no' => 'нет', 'yes' => 'да'],
        'IsIndex' => 'Индексация раздела',
        'IsIndex options' => ['no' => 'нет', 'yes' => 'да'],
        'Name' => 'Название',
        'Sort' => 'Сортировка',
        'Url' => 'Абсолютная ссылка',
        'UrlRedirect' => 'Редирект',
        'UrlThis' => 'Относительная ссылка',
        'Zero_Section_ID' => 'Родительский раздел',
        'Description' => 'Описание',
        'Content' => 'Контент',
        'Keywords' => 'Ключи',
        'Title' => 'Титул',
        'Layout' => 'Шаблон',
        'Controller' => 'Контроллер',
    ],
    'view' => [

    ],
    'controller' => [
        'Zero_Section_Edit' => 'Изменение разделов',
        'Action_Add' => 'добавить',
        'Save' => 'сохранено',
        'Action_Save' => 'сохранить',
        'Zero_Section_Grid' => 'Список разделов сайты постранично',
        'Action_Edit' => 'изменить',
        'Action_CatalogMove' => 'переместить',
        'Action_Remove' => 'удалить',
        'Action_UpdateUrl' => 'обновить роутинг',
        'Action_Default' => 'контроллер по умолчанию',
        'Action_FilterSet' => 'утановка фильтра',
        'Action_FilterReset' => 'сброс фильтра',
    ],
];