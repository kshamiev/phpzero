<?php

/**
 * Авторизация пользователя.
 *
 * Авторизация пользователя по логину и паролю.
 * Восстановление логина и пароля по email.
 *
 * @package Zero.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Login extends Zero_Controller
{
    /**
     * Контроллер по умолчанию
     *
     * @return string собранный шаблон
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        return $this->Chunk_View();
    }

    /**
     * User authentication.
     *
     * Redirect referrer page
     *
     * @return string собранный шаблон
     */
    public function Action_Login()
    {
        $this->Chunk_Init();

        // Инициализация
        if ( !$_REQUEST['Login'] || !$_REQUEST['Password'] )
            return $this->Chunk_View();

        $Users = Base_Users::Make();
        $Users->AR->Sql_Where('Login', '=', $_REQUEST['Login']);
        $Users->AR->Load('*');

        //  Проверки
        if ( 0 == $Users->ID )
        {
            $this->SetMessage(-1, ["Пользователь не зарегистрирован"]);
            return $this->Chunk_View();
        }
        else if ( $Users->Password != md5($_REQUEST['Password']) )
        {
            $this->SetMessage(-1, ["Пароль не верный"]);
            return $this->Chunk_View();
        }
        else if ( !$Users->Groups_ID )
        {
            $this->SetMessage(-1, ["Пользователь не входит ни в одну группу"]);
            return $this->Chunk_View();
        }

        // Авторизация
        if ( isset($_REQUEST['Memory']) && $_REQUEST['Memory'] )
        {
            $Users->Token = crypt($_REQUEST['Password'], crypt($_REQUEST['Password']));
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', $Users->Token, time() + 2592000, '/');
        }
        else
        {
            $Users->Token = '';
            setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', null, 0, '/');
        }
        $Users->IsOnline = 'yes';
        $Users->DateOnline = date('Y-m-d H:i:s');
        $Users->AR->Save();

        Zero_App::$Users = $Users;
        Zero_App::$Users->Factory_Set();
        Zero_App::ResponseRedirect(Zero_App::$Users->UrlRedirect);
        return '';
    }

    /**
     * Recovery of user details.
     *
     * @return string собранный шаблон
     */
    public function Action_Reminder()
    {
        $this->Chunk_Init();

        $this->Model->VL->Validate($_REQUEST['Users'], 'reminder');

        if ( $_REQUEST['Users']['Keystring'] != Zero_App::$Users->Keystring )
        {
            $this->SetMessage(-1, ['Контрольная строка не верна']);
            return $this->Chunk_View();
        }

        $this->Model->AR->Sql_Where('Email', '=', $_REQUEST['Users']['Email']);
        $this->Model->AR->Load('ID, Name, Login');

        if ( 0 == $this->Model->ID )
        {
            $this->SetMessage(-1, ['Пользователь не найден']);
            return $this->Chunk_View();
        }

        $this->Model->AR->Load('*');
        $password = substr(md5(uniqid(mt_rand())), 0, 10);
        $this->Model->Password = md5($password);
        $this->Model->AR->Save();

        $subject = "Reminder access details " . HTTP;
        $View = new Zero_View('Zero_Users_ReminderMail');
        $View->Assign('Users', $this->Model);
        $View->Assign('password', $password);
        $message = $View->Fetch();

        $from = ['Email' => Zero_App::$Config->Site_Email, 'Name' => Zero_App::$Config->Site_Name];
        $to = [['Email' => $this->Model->Email, 'Name' => $this->Model->Name]];
        $reply = $from;
        Mail_Queue::SendMessage($reply, $from, $to, $subject, $message);

        $this->Model = Base_Users::Make();
        $this->SetMessage(0, ["Реквизиты отправлены на почту"]);
        return $this->Chunk_View();
    }

    /**
     * User exit. Redirect main page
     */
    public function Action_Logout()
    {
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        setcookie('i09u9Maf6l6sr7Um0m8A3u0r9i55m3il', null, 0, '/');
        Zero_App::ResponseRedirect(ZERO_HTTP);
    }

    /**
     * Initialization of the action and input parameters
     */
    protected function Chunk_Init()
    {
        if ( 0 < Zero_App::$Users->ID )
        {
            Zero_App::ResponseRedirect('/admin');
        }
        $this->Model = Base_Users::Make();
        $this->View = new Zero_View(get_class($this));
        if ( !Zero_App::$Users->UrlRedirect )
        {
            if ( 1 < count(explode($_SERVER["HTTP_HOST"], ZERO_HTTPH)) )
                Zero_App::$Users->UrlRedirect = ZERO_HTTPH;
            else
                Zero_App::$Users->UrlRedirect = '/';
        }
        Zero_App::$Users->Factory_Set();
    }

    /**
     * Формирование вывода
     *
     * @return string
     */
    protected function Chunk_View()
    {
        $this->View->Assign('Users', Zero_App::$Users);
        $this->View->Assign('Message', $this->GetMessage());
        return $this->View->Fetch();
    }
}