<?php

/**
 * Section list.
 *
 * To work with the catalog.
 *
 * @package Zero.Controller.Section
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Section_Grid extends Zero_Crud_Grid
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
    protected $ViewName = 'Zero_Crud_Grid';

    /**
     * Initialization filters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        parent::Chunk_Init();
        if ( !isset($this->Params['obj_parent_prop']) )
        {
            $this->Params['obj_parent_prop'] = 'Section_ID';
            $this->Params['obj_parent_name'] = 'Раздел - Страница';
            $this->Params['obj_parent_path'] = ['root'];
            $this->Params['obj_parent_id'] = 0;
        }
        if ( isset($_REQUEST['pid']) )
        {
            $this->Params['obj_parent_id'] = $_REQUEST['pid'];
            //  move up
            if ( isset($this->Params['obj_parent_path'][$_REQUEST['pid']]) )
            {
                $flag = true;
                foreach ($this->Params['obj_parent_path'] as $id => $name)
                {
                    if ( $id == $_REQUEST['pid'] )
                        $flag = false;
                    else if ( false == $flag )
                        unset($this->Params['obj_parent_path'][$id]);
                }
            }
            //  move down
            else
            {
                $ObjectGo = Zero_Model::Makes($this->ModelName, $_REQUEST['pid']);
                $this->Params['obj_parent_path'][$_REQUEST['pid']] = $ObjectGo->NameMenu;
                unset($ObjectGo);
            }
            Zero_Filter::Factory($this->Model)->Reset();
        }
        $Filter = Zero_Filter::Factory($this->Model);
        if ( false == $Filter->IsSet )
            $Filter->Set_Sort('Sort');
        return true;
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_CatalogMove()
    {
        $this->Chunk_Init();
        $this->Chunk_CatalogMove();
        $this->Chunk_View();
        return $this->View->Fetch();
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_CatalogMove()
    {
        if ( !$_REQUEST['id'] )
            return $this->SetMessage(5303);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Makes($this->ModelName, $_REQUEST['id']);
        if ( 0 == count($Object->Load('ID')) )
            return $this->SetMessage(5303);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->Save();
        return $this->SetMessage();
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_UpdateUrl()
    {
        $this->Chunk_Init();
        $this->Chunk_UpdateUrl();
        $this->Chunk_View();
        return $this->View->Fetch();
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_FilterReset()
    {
        $this->Chunk_Init();

        $Filter = Zero_Filter::Factory($this->Model);
        $Filter->Reset();
        $Filter->Set_Sort('Sort');
        $Filter->Page = 1;

        $this->Chunk_View();
        return $this->View->Fetch();
    }

    /**
     * Correcting an absolute reference.
     *
     * Correcting an absolute reference catalog and all its subdirectories (usually when moving).
     * - After changing the links, move a catalog, the new installation
     *
     * @param integer $section_id ID of the parent directory
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_UpdateUrl($section_id = null)
    {
        if ( !$section_id )
        {
            if ( !$this->Params['obj_parent_id'] )
                return $this->SetMessage(-1, ['Error_Update_Url']);
            $section_id = $this->Params['obj_parent_id'];
        }
        if ( true == Zero_Section::DB_Update_Url($section_id) )
            return $this->SetMessage();
        else
            return $this->SetMessage(-1, ['Error_Update_Url']);
    }
}