<?php

/**
 * Plugin. Abstract plug-in returns on a binary file or image from the database to the user
 *
 * @package Zero.Crud.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_UploadFile extends Zero_Plugin
{
    /**
     * Initialize the stack chunks.
     *
     */
    protected function Init_Chunks()
    {
        $this->Set_Chunk('View');
    }

    /**
     * Giving away a binary file or image from the database.
     *
     * @return boolean flag run of the next chunk
     * @throws Exception
     */
    protected function Chunk_View()
    {
        $Model = Zero_Model::Make($_REQUEST['source'], $_REQUEST['id']);
        if ( !$Model->$_REQUEST['prop'] )
            throw new Exception('Not Found', 404);

        if ( !file_exists($path = ZERO_PATH_DATA . '/' . $Model->$_REQUEST['prop']) )
        {
            if ( !is_dir(dirname($path)) )
                mkdir(dirname($path), 0777, true);
            $Prop = $_REQUEST['prop'] . 'B';
            file_put_contents($path, $Model->$Prop);
        }

        Zero_App::$Response = strtolower($Model->Get_Config_Form()[$_REQUEST['prop']]['Form']);
        $this->View = $path;
        return false;
    }
}