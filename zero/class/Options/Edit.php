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

    /**
     * Initialization of the input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        //  relation transition one to many (CL)
        $this->Params['obj_parent_prop'] = 'relation_prop';
        $this->Params['obj_parent_name'] = '';
        //  relation transition many to many (CCL)
        $this->Params['obj_parent_table'] = 'relation_table';
        $this->Params['obj_parent_prop'] = 'relation_prop';
        $this->Params['obj_parent_name'] = '';
        //
        parent::Chunk_Init();
        return true;
    }
}