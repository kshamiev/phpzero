<?php
/**
 * Controller. List Groups.
 *
 * @package Zero.Groups.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */
class Zero_Groups_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Groups';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';
}