<?php

/**
 * View a list of objects by page.
 *
 * To work with the item.
 *
 * @package Zero.Controllers
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.06.07
 */
class Zero_Web_Controllers_Grid extends Zero_Web_Crud_Grid
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
    protected $ViewName = 'Zero_Crud_Grid';
}