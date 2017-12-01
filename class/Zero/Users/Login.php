<?php

/**
 * Авторизация пользователя.
 *
 * Авторизация пользователя по логину и паролю.
 * Восстановление логина и пароля по email.
 *
 * @package Zero.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Login extends Zero_Controller
{
    /**
     * Пользователь
     *
     * @var Zero_Users
     */
    protected $Model = null;

    /**
     * Редирект в случае успеха авторизации
     *
     * @var string
     */
    protected $UrlRedirect = '';

    /**
     * Контроллер по умолчанию
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * User authentication.
     *
     * Redirect referrer page
     *
     * @return Zero_View
     */
    public function Action_Login()
    {
        $this->Chunk_Init();

        // Инициализация
        if ( !$_REQUEST['Login'] || !$_REQUEST['Password'] )
        {
            $this->Chunk_View();
            return $this->View;
        }

        $this->Model->Load_Login($_REQUEST['Login']);

        //  Проверки
        if ( 0 == $this->Model->ID )
        {
            $this->SetMessage(-1, ["Пользователь не зарегистрирован"]);
            $this->Chunk_View();
            return $this->View;
        }
        else if ( $this->Model->Password != md5($_REQUEST['Password']) )
        {
            $this->SetMessage(-1, ["Пароль не верный"]);
            $this->Chunk_View();
            return $this->View;
        }
        else if ( !$this->Model->Groups_ID )
        {
            $this->SetMessage(-1, ["Пользователь не входит ни в одну группу"]);
            $this->Chunk_View();
            return $this->View;
        }

        $this->Model->IsOnline = 'yes';
        $this->Model->DateOnline = date('Y-m-d H:i:s');
        $this->Model->Save();

        Zero_App::$Users = $this->Model;
        Zero_App::$Users->Factory_Set();
        Zero_Response::Redirect($this->UrlRedirect);
        return $this->View;
    }

    /**
     * Recovery of user details.
     *
     * @return Zero_View
     */
    public function Action_Reminder()
    {
        $this->Chunk_Init();

        if ( $_REQUEST['Users']['Keystring'] != Zero_App::$Users->Keystring )
        {
            $this->SetMessage(-1, ['Контрольная строка не верна']);
            $this->Chunk_View();
            return $this->View;
        }

        $this->Model->Load_Email($_REQUEST['Users']['Email']);
        if ( 0 == $this->Model->ID )
        {
            $this->SetMessage(-1, ['Пользователь не найден']);
            $this->Chunk_View();
            return $this->View;
        }

        $password = substr(md5(uniqid(mt_rand())), 0, 10);
        $this->Model->Password = md5($password);
        $this->Model->Save();

        $subject = "Reminder access details " . HTTP;
        $View = new Zero_View('Zero_Users_ReminderMail');
        $View->Assign('Users', $this->Model);
        $View->Assign('password', $password);
        $message = $View->Fetch();

        $email = [
            'Reply' => ['Name' => Zero_App::$Config->Site_Name, 'Email' => Zero_App::$Config->Site_Email],
            'From' => ['Name' => Zero_App::$Config->Site_Name, 'Email' => Zero_App::$Config->Site_Email],
            'To' => [
                $this->Model->Email => $this->Model->Name,
            ],
            'Subject' => "Reminder access details " . HTTP,
            'Message' => $message,
            'Attach' => [],
        ];
        $cnt = Helper_Mail::SendMessage($email);
        if ( 0 < $cnt )
            $this->SetMessageError(-1, ["Реквизиты не отправлены на почту"]);
        else
            $this->SetMessage(0, ["Реквизиты отправлены на почту"]);
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * User exit. Redirect main page
     */
    public function Action_Logout()
    {
        Zero_App::$Users->IsOnline = 'no';
        Zero_App::$Users->Save();
        Zero_Session::Unset_Instance();
        session_unset();
        session_destroy();
        Zero_Response::Redirect(ZERO_HTTP);
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        if ( 0 < Zero_App::$Users->ID )
        {
            Zero_App::ResponseRedirect('/zero');
        }
        if ( !$this->UrlRedirect )
        {
            if ( 1 < count(explode($_SERVER["HTTP_HOST"], ZERO_HTTPH)) )
                $this->UrlRedirect = ZERO_HTTPH;
            else
                $this->UrlRedirect = '/';
        }
        $this->Model = Zero_Users::Make();
        $this->View = new Zero_View(get_class($this));
        return true;
    }

    /**
     * Формирование вывода
     *
     * @return Zero_View
     */
    protected function Chunk_View()
    {
        return $this->View;
    }
}