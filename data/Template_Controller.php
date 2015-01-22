<?php

/**
 * Controller. <Comment>
 *
 * @package <Package>.<Subpackage>.Controller
 * @author
 * @version $Id$
 */
class Zero_Controller_Sample extends Zero_Controller
{
    /**
     * Initialization of the stack chunks and input parameters
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->View = new Zero_View(__CLASS__);
        $this->Model = Zero_Model::Make('Zero_Users');
        return true;
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        $this->View->Assign('variable', 'value');
        return true;
    }

    /**
     * Some action.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Name()
    {
        return $this->View;
    }

    //// Пример контроллера для API
    /**
     * (API) Контроллер по умолчанию.
     */
    public function Action_Default()
    {
        $this->View = new Zero_View();
        switch ( $_SERVER['REQUEST_METHOD'] )
        {
            case 'GET':
                break;
            case 'POST':
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;
            case 'OPTIONS':
                break;
        }
        Zero_App::ResponseJson($_SERVER['REQUEST_METHOD'] . ':' . ZERO_URL, 409, 409, "Запрос не реализован");
    }
    protected function Action_DefaultGet()
    {
        Zero_App::ResponseJson('', 200, 200, '');
    }
    protected function Action_DefaultPut()
    {
        Zero_App::ResponseJson($_POST, 200, 200, '');
    }
    protected function Action_DefaultPost()
    {
        Zero_App::ResponseJson($_POST, 200, 200, '');
    }
    protected function Action_DefaultDelete()
    {
        Zero_App::ResponseJson('', 200, 200, '');
    }



    //// Пример консольного контроллера
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
