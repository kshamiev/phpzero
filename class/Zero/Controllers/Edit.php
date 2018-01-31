<?php

/**
 * Контроллер изменения объекта
 *
 * @package Zero.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.07
 */
class Zero_Controllers_Edit extends Zero_Crud_Edit
{
    /**
     * Обрабатываемая модель
     *
     * @var Zero_Controllers
     */
    protected $Model = 'Zero_Controllers';

    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Controllers';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Edit';

    /**
     * Ручной запуск консольного контроллера
     *
     * @return Zero_View
     */
    public function Action_Run()
    {
        $this->Chunk_Init();

        $Controller = Zero_Controller::Makes($this->Model->Controller);
        $flag = $Controller->Action_Default();
        if ( !$flag )
            $this->SetMessageError(-1, ['Error']);
        else
        {
            $this->Model->DateExecute = date('Y-m-d H:i:s');
            $this->Model->Save();
            $this->SetMessage(0, ['Done']);
        }

        $this->Chunk_View();
        return $this->View;
    }
}