<?php

/**
 * Пользователь (Модель).
 *
 * Пользователи сайта как зарегистрированные так и гости.
 * Содержит данные пользователя и реализует непосредвенную работу с ними в рамках БД.
 *
 * @namespace Www.Users
 */
class Www_Users extends Zero_Users
{
    /**
     * The configuration properties
     *
     * - 'DB'=> 'T, I, F, E, S, D, B'
     * - 'IsNull'=> 'YES, NO'
     * - 'Default'=> 'mixed'
     * - 'Value'=> [] 'Values ​​for Enum, Set'
     * - 'Comment' = 'PropComment'
     *
     * @param Zero_Model $Model The exact working model
     * @return array
     */
    protected static function Config_Prop($Model)
    {
        return array_replace_recursive(parent::Config_Prop($Model),
            [
            ]
        );
    }
}
