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
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View($action)
    {
        include_once ZERO_PATH_ZERO . '/library/kcaptcha/kcaptcha.php';
        $Captcha = new KCAPTCHA();
        Zero_App::$Users->Keystring = $Captcha->getKeyString();
        Zero_App::$Response = 'img';
        return false;
    }
}