<?php

/**
 * Controller. Initialize the online status of the current user.
 *
 * Sample: {plugin "Zero_Users_Online" time="60"}
 * Только для зарегистрированных пользователей.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Online extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->Set_Chunk('View');
    }

    /**
     * Initialize the online status of the current user.
     *
     * Update the date and time of the presence of the current user
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View($action)
    {
        if ( isset($this->Params['time']) )
            $t = $this->Params['time'];
        else
            $t = 60;
        $time = time();
        if ( 0 < Zero_App::$Users->ID && $t < $time - Zero_App::$Users->Timeout )
        {
            Zero_App::$Users->Timeout = $time;
            Zero_App::$Users->IsOnline = 'yes';
            Zero_App::$Users->DateOnline = date('Y-m-d H:i:s', Zero_App::$Users->Timeout);
            $Model = Zero_Model::Make('Zero_Users', Zero_App::$Users->ID);
            $Model->IsOnline = Zero_App::$Users->IsOnline;
            $Model->DateOnline = Zero_App::$Users->DateOnline;
            $Model->DB->Update();
        }
    }
}