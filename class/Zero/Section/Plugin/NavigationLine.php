<?php

/**
 * Formation of bread crumbs.
 *
 * @sample {plugin "Zero_Section_Plugin_NavigationLine" view=""}
 *
 * @package Zero.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 */
class Zero_Section_Plugin_NavigationLine extends Zero_Controller
{
    /**
     * Create views meta tags.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $index = __CLASS__ . '_' . Zero_App::$Section->ID;
        if ( false === $navigation = Zero_App::$Section->CH->Get($index) )
        {
            $navigation[] = [
                'Url' => URL,
                'Title' => Zero_App::$Section->Title,
                'NameMenu' => Zero_App::$Section->NameMenu
            ];
            $id = Zero_App::$Section->Section_ID;
            while ( 0 < $id )
            {
                $Zero_Section = Zero_Section::Make($id);
                $Zero_Section->Load("NameMenu, Title, SUBSTRING(Url, POSITION('/' IN Url)) as Url, Section_ID");
                $id = $Zero_Section->Section_ID;
                $navigation[] = ['Url' => $Zero_Section->Url, 'NameMenu' => $Zero_Section->NameMenu, 'Title' => $Zero_Section->Title];
            }
            $navigation = array_reverse($navigation);
            Zero_App::$Section->CH->Set($index, $navigation);
        }
        $this->Chunk_Init();
        $this->View->Assign('navigation', $navigation);
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