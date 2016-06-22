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

/**
 * Check function on the the date and time run in the format of crontab
 *
 * @param string $date_this
 * @param string $date_cron
 * @return boolean
 */
function zero_crontab_check_datetime($date_this, $date_cron)
{
    //  any valid value or exact match
    if ( '*' == $date_cron || $date_this == $date_cron )
    {
        return true;
    }
    //  range
    if ( false !== strpos($date_cron, '-') )
    {
        $arr = explode('-', $date_cron);
        if ( $arr[0] <= $date_this && $date_this <= $arr[1] )
        {
            return true;
        }
        return false;
    }
    //  fold
    else if ( false !== strpos($date_cron, '/') )
    {
        $arr = explode('/', $date_cron);
        if ( $date_this % $arr[1] )
        {
            return false;
        }
        return true;
    }
    //  list
    else if ( false !== strpos($date_cron, ',') )
    {
        $arr = explode(',', $date_cron);
        if ( in_array($date_this, $arr) )
        {
            return true;
        }
        return false;
    }
    else
    {
        return false;
    }
}

/**
 * Генерация случайной строки
 *
 * @param int $length длина строки
 * @param string $chartypes ('lower,upper,numbers,special')
 * @return string
 */
function zero_random_string($length, $chartypes = 'all')
{
    $chartypes_array = explode(",", $chartypes);
    // задаем строки символов.
    //Здесь вы можете редактировать наборы символов при необходимости
    $lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
    $numbers = '1234567890'; // numbers
    $special = '^@*+-+%()!?'; //special characters
    $chars = "";
    // определяем на основе полученных параметров,
    //из чего будет сгенерирована наша строка.
    if ( in_array('all', $chartypes_array) )
    {
        $chars = $lower . $upper . $numbers . $special;
    }
    else
    {
        if ( in_array('lower', $chartypes_array) )
            $chars = $lower;
        if ( in_array('upper', $chartypes_array) )
            $chars .= $upper;
        if ( in_array('numbers', $chartypes_array) )
            $chars .= $numbers;
        if ( in_array('special', $chartypes_array) )
            $chars .= $special;
    }
    // длина строки с символами
    $chars_length = strlen($chars) - 1;
    // создаем нашу строку,
    //извлекаем из строки $chars символ со случайным
    //номером от 0 до длины самой строки
    $string = $chars{rand(0, $chars_length)};
    // генерируем нашу строку
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // выбираем случайный элемент из строки с допустимыми символами
        $random = $chars{rand(0, $chars_length)};
        // убеждаемся в том, что два символа не будут идти подряд
        if ( $random != $string{$i - 1} )
            $string .= $random;
    }
    // возвращаем результат
    return $string;
}
