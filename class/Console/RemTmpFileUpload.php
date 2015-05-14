<?php
/**
 * Удаление старых бинарных данных загруженных через веб (ajax)
 *
 * @package Console.Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Console_RemTmpFileUpload extends Zero_Controller
{
    /**
     * Удаление старых бинарных данных загруженных через веб (ajax)
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
     }
}