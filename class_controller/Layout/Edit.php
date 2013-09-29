<?php

/**
 * Controller. Layout edit
 *
 * @package Zero.Layout.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Layout_Edit extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_Layout';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Edit';

    /**
     * Take into account the conditions user
     *
     * @var boolean
     */
    protected $User_Condition = true;
}