<?php
/**
 * Controller. Formation of abstract panel controllers actions.
 *
 * @package Zero.Plugin.Crud
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Crud_Plugin_Toolbar extends Zero_Controller
{
    /**
     * Create views.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->View = new Zero_View();
        $this->View->Add(Zero_App::$Section->Controller . 'Toolbar');
        $this->View->Add(__CLASS__);
        foreach ($this->Params as $prop => $value)
        {
            $this->View->Assign($prop, $value);
        }
        return $this->View;
    }
}