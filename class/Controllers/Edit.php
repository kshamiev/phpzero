<?php

/**
 * Контроллер изменения объекта
 *
 * @package Zero.Controllers
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.07
 */
class Zero_Controllers_Edit extends Zero_Crud_Edit
{
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
}