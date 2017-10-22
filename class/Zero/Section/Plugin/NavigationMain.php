<?php

/**
 * Navigating the root sections of the site.
 *
 * - two level
 * @sample {plugin "Zero_Section_Plugin_NavigationMain" view=""}
 *
 * @package Zero.Section.Navigation
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 */
class Zero_Section_Plugin_NavigationMain extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $Section = Zero_Section::Make();
        $Section->Load_Url('/');
        $this->Chunk_Init();
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $Section->Get_Navigation_Child());
        return $this->View;
    }

    /**
     * Инициализация контроллера
     *
     * @return bool статус выполнения чанка
     */
    protected function Chunk_Init()
    {
        // Шаблон
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else if ( isset($this->Params['tpl']) )
            $this->View = new Zero_View($this->Params['tpl']);
        else if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        // Модель (пример)
        // $this->Model = Zero_Model::Makes('Zero_Users');
        return true;
    }
}