<?php
/**
 * Системные функции общего назначения
 *
 * @package General.Function
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

/**
 * Check and format query
 * %s - строка
 * %d - целое число
 * %f - число с плавающей точкой
 *
 * Format param: $sql[[[, $arg], $arg], $arg...]
 *
 * @return string пропарсенный запрос sql со вставленными параметрами с их проверкой
 * @throws Exception
 */
function zero_sprintf()
{
    //
    $arr = func_get_args();
    if ( 0 == count($arr) )
        return '';
    if ( is_array($arr[0]) )
        $arr = $arr[0];
    //
    $str = array_shift($arr);
    if ( 0 < count($arr) )
        return vsprintf($str, $arr);
    return $str;
}
