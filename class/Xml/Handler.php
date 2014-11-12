<?php
/* vim: set expandtab tabstop=2 shiftwidth=2 softtabstop=2 foldmethod=marker: */
/**
 * @package Service
 */

/**
 * Класс реализующий обрабчики нод XML объекта.
 *
 * Для обработки нужной ноды создется метод-обработчик с соответсвующим ей именем.
 * К примеру: &lt;item ...&gt; ... &lt;/item&gt; = function Item()
 * ! Регистр и там и там не имеет значения !
 * Сулжит в первую очередь для поточной обработки нод.
 * Во время парсинга файла очень большего размера.
 * После обработки он не добавляется в общее дерево объекта XML
 *
 * @package Service
 * @subpackage XML
 * @author Konstantin Shamiev aka marko-polo <konstanta75@mail.ru>
 * @version 16.03.2010
 */
class Zero_Xml_Handler
{
    /**
     * Конструткор
     */
    public function __construct()
    {
    }

    /**
     * Обработчик ноды одноименной методу (без учета регистра)
     *
     * @param Zero_Xml_Object $Xml
     * @return bool
     */
    public function Example(Zero_Xml_Object $Xml)
    {
        //  print '$Xml<pre>'; print_r($Xml); print '</pre>';
        return true;
    }

    /**
     * Деструктор
     */
    public function __destruct()
    {
    }
}
