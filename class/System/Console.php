<?php
/**
 * Controller. Удаление старых ошибочных загруженных файлов.
 *
 * @package Console.Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_Console extends Zero_Controller
{
    /**
     * Удаление устаревших загруженных бинарных файлов
     *
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
     }

    /**
     * Формирование документации
     *
     */
    public function Action_ApiGen()
    {
        Zero_Helper_File::Folder_Copy(ZERO_PATH_ZERO .'/data/doc', ZERO_PATH_SITE .'/doc');
        // ApiGen
        $command = Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_ZERO . '/apigen.phar generate';
        // source (источники)
        $command .= ' -s '. ZERO_PATH_ZERO .' -s '. ZERO_PATH_APPLICATION;
        // exclude (исключения)
        $command .= ' --exclude="setup/*" --exclude="i18n/*" --exclude="data/*"';
        // target (куда)
        $command .= ' -d '. ZERO_PATH_SITE .'/doc/api';
        // advanced
        $command .= ' --title="'. Zero_App::$Config->Site_Name. '" --access-levels="public" --groups="packages" --todo --deprecated --download';
        //
        Zero_Logs::Set_Message_Notice($command);
        exec($command);
     }
}