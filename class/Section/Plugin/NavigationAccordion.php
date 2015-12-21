<?php

/**
 * A two-level navigation through the main sections of the site.
 *
 * - 2 и 3 уровень.
 * @sample {plugin "Zero_Section_Plugin_NavigationAccordion" view="" section_id="0"}
 *
 * @package Zero.Section.Navigation
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Section_Plugin_NavigationAccordion extends Zero_Controller
{
    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    public function Action_Default()
    {
        $index = __CLASS__ . Zero_App::$Users->Groups_ID . Zero_App::$Config->Site_DomainSub;
        $Section = Zero_Section::Make();
        /* @var $Section Zero_Section */
        if ( isset($this->Params['section_id']) && 0 < $this->Params['section_id'] )
        {
            $Section = Zero_Section::Make($this->Params['section_id']);
            $index .= $this->Params['section_id'];
        }
        else
            $Section->Init_Url('/');

        if ( false === $navigation = $Section->Cache->Get($index) )
        {
            $navigation = Zero_Section::DB_Navigation_Child($Section->ID);
            foreach (array_keys($navigation) as $id)
            {
                $navigation[$id]['child'] = Zero_Section::DB_Navigation_Child($id);
            }
            Zero_Cache::Set_Link('Section', $Section->ID);
            $Section->Cache->Set($index, $navigation);
        }
        $this->Chunk_Init();
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $navigation);
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