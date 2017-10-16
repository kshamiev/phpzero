<?php

/**
 * Запросы к внешним источникам (службам, сервисам)
 *
 * Расширяемый компонент
 * Путем добавления конфигураций и указания методов ниже в комментарии
 * Либо чере методы геттеры
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017-09-14
 *
 * @property mixed SampleCustomRequest
 * @method Zero_Request_Type Sample($method, $uri, $content = null, $headers = []) Пример запроса с реквизитами доступа
 */
class Zero_RequestSample extends Zero_RequestBase
{
    /**
     * Обертка запросов к внешнему источнику
     *
     * @var mixed
     */
    private $sampleCustomRequest = null;

    /**
     * Обертка запросов к внешнему источнику
     *
     * @return mixed
     */
    protected function Get_SampleCustomRequest()
    {
        if ( is_null($this->sampleCustomRequest) )
        {
            $access = Zero_App::$Config->AccessOutside['SampleCustomRequest'];
            // Инициализация ранее реализованного функционала для реализации запросов к нужному ресурсу

        }
        return $this->sampleCustomRequest;
    }
}
