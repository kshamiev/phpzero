<?php

/**
 * <Comment>
 *
 * @package Cron.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Cron_Controller_Sample extends Zero_Controller
{
    /**
     * Какой-то контроллер
     *
     * @return int статус выполнения (0 - ok, 1 - error)
     */
    public function Action_Default()
    {
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        foreach (glob($path . '/.+') as $file)
        {
            $timeOutMinute = (time() - filemtime($file)) * 60;
            if ( 60 < $timeOutMinute )
                unlink($file);
        }
        return 0;
    }
}
