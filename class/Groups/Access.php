<?php

/**
 * Controller. Management of access rights.
 *
 * @package Zero.Groups.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Groups_Access extends Zero_Controller
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = 'Zero_Groups';

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Preservation of the rights of access
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Save()
    {
        $this->Chunk_Init();
        $this->Chunk_Save();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Copying permissions
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Copy()
    {
        $this->Chunk_Init();
        $this->Chunk_Copy();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        $this->Params['obj_parent_prop'] = 'Zero_Groups_ID';
        $this->Params['obj_parent_id'] = Zero_App::$Route->Param['pid'];
        $this->Params['obj_parent_name'] = '';
        $this->View = new Zero_View(__CLASS__);
        $this->Model = Zero_Model::Make($this->Source);
    }

    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        $Section = Zero_Model::Make('Zero_Section');
        $Section->DB->Sql_Where_IsNotNull('Controller');
        $section_list = $Section->DB->Select_Tree('ID, Name, Controller, Url, IsAuthorized');
        foreach ($section_list as $id => $row)
        {
            if ( 'no' == $row['IsAuthorized'] )
            {
                unset($section_list[$id]);
                continue;
            }
            //  read from the controllers
            $method_list = [];
            try
            {
                $reflection = new ReflectionClass($row['Controller']);
            } catch ( Exception $e )
            {
                throw new Exception($e->getMessage(), 500);
            }
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
            {
                $name = $method->getName();
                if ( 'Action_Default' == $name )
                    continue;
                if ( 'Action' == substr($name, 0, 6) )
                {
                    $name = str_replace('Action_', '', $name);
                    $index = "controller {$row['Controller']} action {$name}";
                    $method_list[$name] = Zero_I18n::T($row['Controller'], $index, $name);
                }
            }
            $section_list[$id]['action_list_all'] = $method_list;
            $section_list[$id]['action_list_all_count'] = count($method_list) + 1;

            $Action = Zero_Model::Make('Zero_Action');
            $Action->DB->Sql_Where('Zero_Section_ID', '=', $id);
            $Action->DB->Sql_Where('Zero_Groups_ID', '=', $this->Params['obj_parent_id']);
            $Action->DB->Sql_Order('Action', 'ASC');
            $action_list = $Action->DB->Select_Array_Index('Action');
            $section_list[$id]['action_list'] = $action_list;
        }

        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('section_list', $section_list);
        $this->View->Assign('Params', $this->Params);

        $Groups = Zero_Model::Make('Zero_Groups');
        $Groups->DB->Sql_Where('ID', '!=', $this->Params['obj_parent_id']);
        $Groups->DB->Sql_Order('Name', 'ASC');
        $groups_list = $Groups->DB->Select_List_Index('ID, Name');
        $this->View->Assign('groups_list', $groups_list);
        //  Navigation parent
        $this->View->Assign('url_parent', (0 < Zero_App::$Route->Param['pid']) ? '-pid-' . Zero_App::$Route->Param['pid'] : '');
    }

    /**
     * Preservation of the rights of access
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Chunk_Save()
    {
        foreach ($_REQUEST['RoleAccessSection'] as $section_id => $access)
        {
            Zero_DB::Set("DELETE FROM Zero_Action WHERE Zero_Groups_ID = {$this->Params['obj_parent_id']} AND Zero_Section_ID = {$section_id}");
            //  Access to the section for the group
            if ( 'access' == $access )
            {
                $Action = Zero_Model::Make('Zero_Action');
                $Action->Zero_Section_ID = $section_id;
                $Action->Zero_Groups_ID = $this->Params['obj_parent_id'];
                $Action->Action = 'Default';
                $Action->DB->Insert();
            }
            else
                continue;
            //  access to the controller actions
            if ( isset($_REQUEST['RoleAccessAction'][$section_id]) )
            {
                foreach ($_REQUEST['RoleAccessAction'][$section_id] as $action => $panel)
                {
                    if ( 'access' == $panel )
                    {
                        $Action = Zero_Model::Make('Zero_Action');
                        $Action->Zero_Section_ID = $section_id;
                        $Action->Zero_Groups_ID = $this->Params['obj_parent_id'];
                        $Action->Action = $action;
                        $Action->DB->Insert();
                    }
                }
            }
        }
        //  Reset Cache
        $Model = Zero_Model::Make('Zero_Groups', $this->Params['obj_parent_id']);
        $Model->Cache->Reset();
        return $this->Set_Message('RoleAccess', 0);
    }

    /**
     * Copying permissions
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Chunk_Copy()
    {
        Zero_DB::Set("DELETE FROM Zero_Action WHERE Zero_Groups_ID = {$_REQUEST['obj_id']}");
        $sql = "
        INSERT INTO `Zero_Action`
          (
          `Zero_Section_ID`,
          `Zero_Groups_ID`,
          `Action`
        ) SELECT
          `Zero_Section_ID`,
          {$_REQUEST['obj_id']},
          `Action`
        FROM `Zero_Action`
        WHERE
          `Zero_Groups_ID` = {$this->Params['obj_parent_id']}
        ";
        Zero_DB::Set($sql);
        return $this->Set_Message('AccessCopy', 0);
    }
}