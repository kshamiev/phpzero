<?php

/**
 * Controller. Abstract plug-in returns on a binary file or image from the database to the user
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Crud_UploadFile extends Zero_Controller
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
     * Giving away a binary file or image from the database.
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     * @throws Exception
     */
    protected function Chunk_View($action)
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