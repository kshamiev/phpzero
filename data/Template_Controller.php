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
    protected function Action_GET()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    protected function Action_PUT()
    {
        Zero_App::ResponseJson($_REQUEST, 200, 0, []);
    }

    protected function Action_POST()
    {
        Zero_App::ResponseJson($_REQUEST, 200, 0, []);
    }

    protected function Action_DELETE()
    {
        Zero_App::ResponseJson('', 200, 0, []);
    }

    protected function Action_OPTIONS()
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
