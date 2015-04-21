<?php

/**
 * Controller. View a list of objects by page.
 *
 * To work with the item.
 *
 * @package <Package>.<Subpackage>.Controller
 * @author
 * @version $Id$
 * @ignore
 */
class Zero_Controller_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Model_Pattern';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Grid';
}