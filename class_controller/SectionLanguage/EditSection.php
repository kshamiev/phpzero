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

    /**
     * Take into account the conditions user
     *
     * @var boolean
     */
    protected $User_Condition = true;

    /**
     * Initialization of the input parameters
     *
     * При изменение объектов без переходов по свяи (один ко многим) этот чанк нужно удалить или изменить
     *
     * @param string $action action
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init($action)
    {
        parent::Chunk_Init($action);
        //  relation transition  one to many  (CL)
        $this->Params['obj_parent_prop'] = 'Zero_Section_ID';
        $this->Params['obj_parent_id'] = Zero_App::$Route->Param['pid'];
        $this->Params['obj_parent_name'] = '';
    }
}