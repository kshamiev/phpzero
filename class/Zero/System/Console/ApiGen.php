<?php
/**
 * Формирование этой документации
 *
 * @package Zero.Console
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_Console_ApiGen extends Zero_Controller
{
    /**
     * Формирование документации проекта
     *
     * @return int
     */
    public function Action_Default()
    {
//        Helper_File::Folder_Copy(ZERO_PATH_ZERO .'/data/doc', ZERO_PATH_SITE .'/doc');
//        $tpl = file_get_contents(ZERO_PATH_SITE .'/doc/.htaccess');
//        $tpl = str_replace('PATHFRILEACCESS', ZERO_PATH_SITE, $tpl);
//        file_put_contents(ZERO_PATH_SITE .'/doc/.htaccess', $tpl);
        // ApiGen
        $command = Zero_App::$Config->Site_PathPhp . ' ' . ZERO_PATH_ZERO . '/apigen.phar generate';
        // source (источники)
        $command .= ' -s '. ZERO_PATH_ZERO .'/class -s '. ZERO_PATH_APP . '/class';
        // exclude (исключения)
//        $command .= ' --exclude="setup/*" --exclude="i18n/*" --exclude="data/* --exclude="assets/* --exclude="view/*"';
        // target (куда)
        $command .= ' -d '. ZERO_PATH_SITE .'/doc';
        // advanced
        $command .= ' --title="'. Zero_App::$Config->Site_Name. '" --access-levels="public" --groups="packages" --todo --deprecated --download';
        //
        exec($command);
        return 0;
     }
}