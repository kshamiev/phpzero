<?php
/**
 * Controller. Удаление старых ошибочных загруженных файлов.
 *
 * @package Zero.System.Console
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */
class Zero_System_Console extends Zero_Controller
{
    /**
     * Initialize the online status is not active users.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_RemoveTempFileUpload()
    {
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        foreach (glob($path . '/.+') as $file)
        {
            $timeOutMinute = (time() - filemtime($file)) * 60;
            if ( 60 < $timeOutMinute )
                unlink($file);
        }
        return $this->View;
    }
}