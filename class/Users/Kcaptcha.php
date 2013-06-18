<?php

/**
 * Plugin. Generation cAPTCHA.
 *
 * @package Zero.Users.Plugin
 * @subpackage Helper
 * @author Maxim Rautkin <mrautkin@gmail.com>
 * @version 2013.01.31
 * @since 1.0.0
 */
class Zero_Users_Kcaptcha extends Zero_Plugin
{
    /**
     * Initialize the stack chunks
     *
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('View');
    }

    /**
     * Create views.
     *
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_View()
    {
        include_once ZERO_PATH_PHPZERO . '/library/kcaptcha/kcaptcha.php';
        $Captcha = new KCAPTCHA();
        Zero_App::$Users->Keystring = $Captcha->getKeyString();
        Zero_App::$Response = 'img';
        return false;
    }
}