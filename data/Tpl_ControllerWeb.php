<?php

/**
 * <Comment>
 *
 * Sample:  {plugin "Zero_Section_SeoTag" [view="Zero_Section_SeoTag" paramName="value" ...]}
 *
 * @package <Package>.<Subpackage>
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date <Date>
 */
class Zero_Controller_Sample extends Zero_Controller
{
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
     * Инициализация контроллера
     *
     * @return bool
     */
    protected function Chunk_Init()
    {
        // Шаблон
        if (isset($this->Params['view']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['view']);
        else if (isset($this->Params['tpl']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['tpl']);
        else if (isset($this->Params['template']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        // Модель (пример)
        // $this->Model = Zero_Model::Makes('Zero_Users');
        // $this->Model = Zero_Users::Make();
        return true;
    }

    /**
     * Вывод данных контроллера в шаблон.
     *
     * @return bool
     */
    protected function Chunk_View()
    {
        $this->View->Assign('variable', 'value');
        return true;
    }

    /**
     * Фабричный метод по созданию контроллера.
     *
     * @param array $properties входные параметры контроллера (обычно в режиме плагина)
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
     * Работает через сессию. Indeks: __CLASS__
     *
     * @param array $properties входные параметры контроллера (обычно в режиме плагина)
     * @return Zero_Controller
     */
    public static function Factory($properties = [])
    {
        if ( !$Controller = Zero_Session::Get(__CLASS__) )
        {
            $Controller = self::Make($properties);
            Zero_Session::Set(__CLASS__, $Controller);
        }
        return $Controller;
    }
}
