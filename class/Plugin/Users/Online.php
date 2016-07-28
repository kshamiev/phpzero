<?php
/**
 * Controller. Initialize the online status of the current user.
 *
 * Sample: {plugin "Zero_Users_Plugin_Online" time="60"}
 * Только для зарегистрированных пользователей.
 *
 * @package Zero.Plugin.Users
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Plugin_Users_Online extends Zero_Controller
{
    /**
     * Initialize the online status of the current user.
     *
     * Update the date and time of the presence of the current user
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
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
            $Model = Zero_Model::Makes('Zero_Users', Zero_App::$Users->ID);
            $Model->IsOnline = Zero_App::$Users->IsOnline;
            $Model->DateOnline = Zero_App::$Users->DateOnline;
            $Model->Save();
        }
        return '';
    }
}