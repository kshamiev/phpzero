<?php

/**
 * Controller. Users list.
 *
 * To work with the catalog.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Users_Grid extends Zero_Crud_Grid
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = 'Zero_Users';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = 'Zero_Crud_Grid';

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    protected function Chunk_Init()
    {
        if ( !isset($this->Params['obj_parent_prop']) )
        {
            $this->Params['obj_parent_prop'] = 'Zero_Users_ID';
            $this->Params['obj_parent_id'] = 0;
            $this->Params['obj_parent_name'] = '';
            $this->Params['obj_parent_path'] = ['root'];
        }
        parent::Chunk_Init();

        if ( isset(Zero_App::$Route->Param['pid']) && $this->Params['obj_parent_id'] != Zero_App::$Route->Param['pid'] )
        {
            $this->Params['obj_parent_id'] = Zero_App::$Route->Param['pid'];
            //  move up
            if ( isset($this->Params['obj_parent_path'][Zero_App::$Route->Param['pid']]) )
            {
                $flag = true;
                foreach ($this->Params['obj_parent_path'] as $id => $name)
                {
                    if ( $id == Zero_App::$Route->Param['pid'] )
                        $flag = false;
                    else if ( false == $flag )
                        unset($this->Params['obj_parent_path'][$id]);
                }
            }
            //  move down
            else
            {
                $ObjectGo = Zero_Model::Make($this->ModelName, Zero_App::$Route->Param['pid']);
                $ObjectGo->DB->Select('Name');
                $this->Params['obj_parent_path'][Zero_App::$Route->Param['pid']] = $ObjectGo->Name;
                unset($ObjectGo);
            }
            Zero_Filter::Factory($this->Model)->Reset();
        }
    }

    /**
     * Moving.
     *
     * Moving a node or branch of a tree branch in the current parent
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Action_CatalogMove()
    {
        $this->Chunk_Init();
        $this->Chunk_CatalogMove();
        $this->Chunk_View();
        return $this->View;
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
        if ( !$_REQUEST['obj_id'] )
            return $this->Set_Message('Error_NotParam', 1, false);
        $prop = $this->Params['obj_parent_prop'];
        $Object = Zero_Model::Make($this->ModelName, $_REQUEST['obj_id']);
        if ( 'NULL' == $this->Params['obj_parent_id'] )
            $Object->$prop = null;
        else
            $Object->$prop = $this->Params['obj_parent_id'];
        $Object->DB->Update();
        return $this->Set_Message('Move', 0);
    }
}