<?php

/**
 * Раздел - страница (uri) (Модель).
 *
 * Разделы или страницы сайта идентифицируемые по запрошенному uri.
 * Каждый раздел содержит глобальные настройки идентифицируемой страницы.
 * Видимость, индексация, авторизация...
 *
 * @namespace Www.Section
 */
class Www_Section extends Zero_Section
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
