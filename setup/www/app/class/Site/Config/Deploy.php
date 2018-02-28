<?php

/**
 * The configuration of systems and applications in general.
 *
 * @package Site.Config
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 *
 * @property string $Branch Выкладываемая ветка
 * @property array $Users Пользователи которым разрешен deploy
 * @property string $CommitMessage Ключевое сообщение
 * @property array $PathDeploy Выкладываемые репозитории
 */
class Site_Config_DeployTemplate
{
    /**
     * Хранилище конфигураций
     *
     * @var array
     */
    private $config = [];

    /**
     * Конструктор
     *
     * Инициализация хранилища конфигураций
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Получение абстрактных пользовательских конфигураций приложеня
     *
     * @param string $prop
     * @return mixed конфигурация
     */
    public function __get($prop)
    {
        return $this->config[$prop];
    }
}
