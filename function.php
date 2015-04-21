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
/*
    switch ( count($arr) )
    {
        default :
            return $sql;
        case 1:
            return sprintf($sql, $arr[0]);
        case 2:
            return sprintf($sql, $arr[0], $arr[1]);
        case 3:
            return sprintf($sql, $arr[0], $arr[1], $arr[2]);
        case 4:
            return sprintf($sql, $arr[0], $arr[1], $arr[2], $arr[3]);
        case 5:
            return sprintf($sql, $arr[0], $arr[1], $arr[2], $arr[3], $arr[4]);
        case 6:
            return sprintf($sql, $arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5]);
        case 7:
            return sprintf($sql, $arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6]);
    }
*/
    /*
    preg_match_all("~(%[d|s|f]{1})~si", $sql, $match);
    if ( count($arr) != count($match[1]) )
        throw new Exception("Wrong number of parameters", 409);
    foreach ($match[1] as $k => $v)
    {
        $match[1][$k] = '~' . $v . '~i';
        switch ( $v )
        {
            case '%d':
            {
                $arr[$k] = Zero_DB::EscI($arr[$k]);
                break;
            }
            case '%f':
            {
                $arr[$k] = Zero_DB::EscF($arr[$k]);
                break;
            }
            case '%s':
            {
                $arr[$k] = Zero_DB::EscT($arr[$k]);
                break;
            }
        }
        //            echo $v . " = " . $arr[$k] . "<br>";
    }
    return preg_replace($match[1], $arr, $sql, 1);
    */
}
