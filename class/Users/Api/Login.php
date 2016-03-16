<?php

/**
 * Авторизация пользователя
 *
 * @package Api.Zero.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-03-15
 */
class Zero_Users_Api_Login extends Zero_Controller
{
    /**
     * Редирект в случае успеха авторизации
     *
     * @var string
     */
    protected $UrlRedirect = '';

    /**
     * Какой-то контроллер
     *
     * @sample /api/v1/sample?param=value...
     *
     * @return boolean flag статус выполнения
     */
    public function Action_PUT()
    {
        // Инициализация
        if ( !$_REQUEST['Login'] || !$_REQUEST['Password'] )
            Zero_App::ResponseJson500(-1, ['Парамтеры не заданы']);

        $Model = Zero_Users::Make();
        $Model->Load_Login($_REQUEST['Login']);

        //  Проверки
        if ( 0 == $Model->ID )
        {
            Zero_App::ResponseJson500(-1, ['Пользователь не зарегистрирован']);
        }
        else if ( $Model->Password != md5($_REQUEST['Password']) )
        {
            Zero_App::ResponseJson500(-1, ['Пароль не верный']);
        }
        else if ( !$Model->Groups_ID )
        {
            Zero_App::ResponseJson500(-1, ['Пользователь не входит ни в одну группу']);
        }

        // Авторизация
        if ( isset($_REQUEST['Memory']) && $_REQUEST['Memory'] )
        {
            $Model->Token = crypt($_REQUEST['Password'], crypt($_REQUEST['Password']));
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', $Model->Token, time() + 2592000, '/');
        }
        else
        {
            $Model->Token = '';
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', null, 0, '/');
        }
        $Model->IsOnline = 'yes';
        $Model->DateOnline = date('Y-m-d H:i:s');
        $Model->Save();

        Zero_App::$Users = $Model;
        Zero_App::$Users->Factory_Set();

        if ( !$this->UrlRedirect )
        {
            if ( 1 < count(explode($_SERVER["HTTP_HOST"], ZERO_HTTPH)) )
                $this->UrlRedirect = ZERO_HTTPH;
            else
                $this->UrlRedirect = '/';
        }

        Zero_App::ResponseJson200(['access-token' => $Model->Token, 'redirect' => $this->UrlRedirect]);
        return true;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
     * @return Zero_Users_Api_Login
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
