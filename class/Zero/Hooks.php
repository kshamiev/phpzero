<?php

/**
 * Функции хуки.
 *
 * @package Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2018-06-20
 */
class Zero_Hooks
{
    /**
     * Хуки выполняющиеся до выполнения всех контроллеров
     *
     * @var array
     */
    private static $before = [];

    /**
     * Хуки выполняющиеся после выполнения всех контроллеров
     *
     * @var array
     */
    private static $after = [];

    /**
     * Добавление хука
     *
     * @param string $nameFunc
     * @param int $sort
     */
    public static function Add_Before($nameFunc, $sort)
    {
        self::$before[$sort] = $nameFunc;
    }

    /**
     * Выполнение хуков
     */
    public static function Run_Before()
    {
        foreach (self::$before as $func)
        {
            $func();
        }
    }

    /**
     * Добавление хука
     *
     * @param string $nameFunc
     * @param int $sort
     */
    public static function Add_After($nameFunc, $sort)
    {
        self::$after[$sort] = $nameFunc;
    }

    /**
     * Выполнение хуков
     */
    public static function Run_After()
    {
        foreach (self::$after as $func)
        {
            $func();
        }
    }
}