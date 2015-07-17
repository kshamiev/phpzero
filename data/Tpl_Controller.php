<?php

/**
 * <Comment>
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Sample extends Zero_Controller
{
    /**
     * Инициализация контроллера до его выполнения
     *
     * @param string $viewName имя шаблона вида
     * @return bool
     */
    protected function Chunk_Init($viewName = '')
    {
        $viewName = ( '' != $viewName ) ? $viewName : __CLASS__;
        $this->View = new Zero_View($viewName);
        $this->Model = Zero_Model::Makes('Zero_Users');
        return true;
    }

    /**
     * Вывод данных операции контроллера в шаблон
     *
     * @return bool
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
        return true;
    }

    ////
}
