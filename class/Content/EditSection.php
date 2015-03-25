<?php
/**
 * Changing the content blocks page of related by page.
 *
 * @package Zero.Content.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Content_EditSection extends Zero_Crud_Edit
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Content';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Edit';

    /**
     * Initialization of the input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Section_ID';
        $this->Params['obj_parent_name'] = '';
        parent::Chunk_Init();
        return true;
    }
}