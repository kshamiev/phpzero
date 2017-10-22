<?php
/**
 * Controller. Generation cAPTCHA.
 *
 * @package Zero.Controller.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Users_Kcaptcha extends Zero_Controller
{
    /**
     * Генерация контрольной картинки и проверочного кода.
     *
     */
    public function Action_Default()
    {
        $Captcha = new Helper_Kcaptcha();
        Zero_App::$Users->Keystring = $Captcha->getKeyString();
        exit;
    }
}