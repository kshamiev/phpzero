<?php

/**
 * Controller. Change in translation section
 *
 * @package Zero.SectionLanguage.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_SectionLanguage_EditSection extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_SectionLanguage';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Edit';

    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Zero_Section_ID';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
    }
}