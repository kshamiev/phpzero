<?php

/**
 * Config. Class configuration, application site.
 *
 * @package Zero.Config
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Config_zero extends Zero_Config
{
    /**
     * Getting config
     *
     * @return array
     */
    public static function Get_Config()
    {
        return array_replace_recursive(require ZERO_PATH_SITE . '/config.php', [
            // Redefinition components
            'FactoryComponents' => [
                //  Route
                'Zero_Route' => 'Zero_Route',
            ],
            // Redefinition models
            'FactoryModel' => [
                //  Users
                'Zero_Users' => 'Zero_Users',
            ],
        ]);
    }
}