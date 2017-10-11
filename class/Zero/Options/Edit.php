<?php

/**
 * Контроллер изменения объекта
 *
 * @package <Package>.Options
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.09.27
 */
class Zero_Options_Edit extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Options';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Edit';
}