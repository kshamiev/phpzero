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
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        require_once ZERO_PATH_ZERO . '/assets/kcaptcha/kcaptcha.php';
        $Captcha = new KCAPTCHA();
        Zero_App::$Users->Keystring = $Captcha->getKeyString();
        exit;
    }
}