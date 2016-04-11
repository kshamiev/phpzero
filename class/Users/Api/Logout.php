<?php

/**
 * Выход пользователя
 *
 * @package Zero.Api.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-03-15
 */
class Zero_Users_Api_Logout extends Zero_Controller
{
    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     *
     * @return boolean flag статус выполнения
     */
    public function Action_PUT()
    {
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', null, 0, '/');
        Zero_App::ResponseJson200(['redirect' => ZERO_HTTP]);
        return true;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
     * @return Zero_Users_Api_Logout
     */
    public static function Make($properties = [])
    {
        $Controller = new self();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }
}
