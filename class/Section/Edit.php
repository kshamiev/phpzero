<?php
/**
 * Section edit.
 *
 * To work with the catalog.
 *
 * @package Zero.Section.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Section_Edit extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Section';

    /**
     * Template view
     *
     * @var string
     */
    protected $ViewName = 'Zero_Crud_Edit';

    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Section_ID';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
    }
}