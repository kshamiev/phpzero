<?php

/**
 * Вывод результата работы приложения.
 *
 * Компонент
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017.08.10
 */
class Zero_Response
{
    /**
     * @param string $content
     * @param int $status
     */
    public static function Page($content = '', $status = 200)
    {
    }

    /**
     * @param string $content
     * @param int $status
     */
    public static function Html($content = '', $status = 200)
    {
    }

    /**
     * @param array $content
     * @param int $status
     */
    public static function Json($content = [], $status = 200)
    {
    }
}
