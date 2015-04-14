<?php

/**
 * Controller. <Comment>
 *
 * @package <Package>.<Subpackage>.Controller
 * @author
 * @version $Id$
 * @ignore
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
        $this->Model = Zero_Model::Makes('Zero_Users');
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
     * Контроллер по умолчанию.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    //// Пример контроллера для API
    /**
     * (API) Контроллер по умолчанию.
     */
    public function Action_DefaultApi()
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
        Zero_App::ResponseJson($_SERVER['REQUEST_METHOD'] . ':' . ZERO_URL, 409, 409, ["Запрос не реализован"]);
    }

    protected function Action_DefaultApiGet()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    protected function Action_DefaultApiPut()
    {
        Zero_App::ResponseJson($_POST, 200, 0, []);
    }

    protected function Action_DefaultApiPost()
    {
        Zero_App::ResponseJson($_POST, 200, 0, []);
    }

    protected function Action_DefaultApiDelete()
    {
        Zero_App::ResponseJson('', 200, 0, []);
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

    ////

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры плагина
     * @return Zero_Controller
     */
    public static function Make($properties = [])
    {
        $Controller = new self();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * Работает через сессию.
     * Индекс: $class_name
     *
     * @param array $properties входные параметры контроллера
     * @return Zero_Controller
     */
    public static function Factory($properties = [])
    {
        if ( !$result = Zero_Session::Get(__CLASS__) )
        {
            $result = self::Make($properties);
            Zero_Session::Set(__CLASS__, $result);
        }
        return $result;
    }
}
