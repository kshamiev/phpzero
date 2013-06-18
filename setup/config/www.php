<?php

/**
 * Config. Class configuration, application site.
 *
 * @package Www.Config
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Config_www extends Zero_Config
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
                'Zero_Route' => 'Www_Route',
            ],
            // Redefinition models
            'FactoryModel' => [
                //  Users
                'Zero_Users' => 'Www_Users',
            ],
        ]);
    }
}