<?php

/**
 * Контроллер изменения объекта
 *
 * @package Zero.AccessOutside
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017.10.08
 */
class Zero_AccessOutside_Edit extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_AccessOutside';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Edit';
}