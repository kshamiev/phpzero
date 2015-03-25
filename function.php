<?php
/**
 * Системные функции общего назначения
 *
 * @package Function
 */

/**
 * Debug output to the browser
 */
function pre()
{
    foreach (func_get_args() as $var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * Getting source on the property due
 *
 * @param string $prop свойство связи
 * @return string source of related objects
 */
function zero_relation($prop)
{
    return preg_replace('~(_[A-Z]{1}|_[0-9]{1,3})?_ID$~', '', $prop);
}
