<?php

/**
 * Controller. Generation cAPTCHA.
 *
 * @package Zero.Users.Controller
 * @author Maxim Rautkin <mrautkin@gmail.com>
 * @version 2013.01.31
 * @since 1.0.0
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
        include_once ZERO_PATH_ZERO . '/library/kcaptcha/kcaptcha.php';
        $Captcha = new KCAPTCHA();
        Zero_App::$Users->Keystring = $Captcha->getKeyString();
        exit;
    }
}