<?php

/**
 * A two-level navigation through the main sections of the site.
 *
 * - 2 и 3 уровень.
 * @sample {plugin "Zero_Section_Plugin_NavigationAccordion" view="" section_id="0"}
 *
 * @package Zero.Plugin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
 */
class Zero_Section_Plugin_NavigationAccordion extends Zero_Controller
{
    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $Section = Zero_Section::Make();
        if ( isset($this->Params['section_id']) && 0 < $this->Params['section_id'] )
            $Section = Zero_Section::Make($this->Params['section_id']);
        else
            $Section->Load_Url('/');
        $index = __CLASS__ . '_' . Zero_App::$Users->Groups_ID . '_' . $Section->ID;

        if ( false === $navigation = $Section->CH->Get($index) )
        {
            $navigation = Zero_Section::DB_Navigation_Child($Section->ID);
            foreach (array_keys($navigation) as $id)
            {
                $navigation[$id]['child'] = Zero_Section::DB_Navigation_Child($id);
            }
            Zero_Cache::Set_Link('Section', $Section->ID);
            $Section->CH->Set($index, $navigation);
        }
        $this->Chunk_Init();
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('navigation', $navigation);
        return $this->View;
    }
}