<?php

/**
 * Controller. Abstract controller for viewing a list of items.
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
abstract class Zero_Crud_Grid extends Zero_Controller
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $ModelName = '';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = __CLASS__;

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_Filter();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Remove()
    {
        $this->Chunk_Init();
        $this->Chunk_Filter();
        $this->Chunk_Remove();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_FilterSet()
    {
        $this->Chunk_Init();
        $this->Chunk_Filter();

        $this->Chunk_Filter_Set();

        $this->Chunk_View();
        return $this->View;
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

        $this->Chunk_Filter();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization filters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Init()
    {
        if ( isset($_GET['pid']) )
            $this->Params['obj_parent_id'] = $_GET['pid'];
        else if ( empty($this->Params['obj_parent_id']) )
            $this->Params['obj_parent_id'] = 0;
        //
        if ( isset($_GET['id']) )
            $this->Params['id'] = $_GET['id'];
        else if ( empty($this->Params['id']) )
            $this->Params['id'] = 0;
        //
        $this->View = new Zero_View($this->Template);
        $this->Model = Zero_Model::Make($this->ModelName);
    }

    /**
     * Initialization and set filters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Filter()
    {
        $Filter = Zero_Filter::Factory($this->Model);
        if ( false == $Filter->IsInit )
        {
            $condition = Zero_App::$Users->Get_Condition();
            foreach ($this->Model->Get_Config_Filter() as $prop => $row)
            {
                $method = 'Add_Filter_' . $row['Form'];
                if ( method_exists($Filter, $method) )
                {
                    if ( isset($row['Visible']) && true == $row['Visible'] )
                        $row['Visible'] = 1;
                    else
                        $row['Visible'] = 0;
                    //
                    if ( isset($condition[$prop]) )
                    {
                        if ( 1 < count($condition[$prop]) )
                            $Filter->$method($prop, $row, $row['Visible'], $condition[$prop]);
                        else
                            $Filter->$method($prop, $row, 0, $condition[$prop]);
                    }
                    else
                        $Filter->$method($prop, $row, $row['Visible'], 1);
                }
                else if ( isset($row['DB']) )
                {
                    $method = '';
                    if ( $row['DB'] == 'I' || $row['DB'] == 'F' )
                        $method = 'Add_Search_Number';
                    else if ( $row['DB'] == 'T' )
                        $method = 'Add_Search_Text';

                    if ( method_exists($Filter, $method) )
                        $Filter->$method($prop, $row);

                    if ( $method != '' )
                    {
                        $Filter->Add_Sort($prop, $row);
                        if ( 'Sort' == $prop )
                            $Filter->Set_Sort($prop);
                    }
                }
//                if ( isset($row['Search']) && $row['Search'] )
//                {
//                    $method = 'Add_Search_' . $row['Search'];
//                    if ( method_exists($Filter, $method) )
//                        $Filter->$method($prop, $row);
//                }
//                if ( isset($row['Sort']) && $row['Sort'] )
//                {
//                    $Filter->Add_Sort($prop, $row);
//                    if ( 'Sort' == $prop )
//                        $Filter->Set_Sort($prop);
//                }
            }
            $Filter->IsInit = true;
        }

        //  Page by page
        if ( isset($_GET['pg']) && 0 < $_GET['pg'] )
            $Filter->Page = $_GET['pg'];
    }

    /**
     * Set Filters
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Filter_Set()
    {
        $Filter = Zero_Filter::Factory($this->Model);
        $Filter->IsSet = true;
        //  Filters
        if ( isset($_REQUEST['Filter']) )
        {
            foreach ($_REQUEST['Filter'] as $Prop => $Value)
            {
                $Filter->Set_Filter($Prop, $Value);
            }
            $Filter->Page = 1;
        }
        //  Search
        if ( isset($_REQUEST['Search']['List']) )
        {
            $Filter->Set_Search();
            foreach ($_REQUEST['Search']['List'] as $prop => $value)
            {
                $Filter->Set_Search($prop, $value);
            }
            $Filter->Page = 1;
        }
        else if ( isset($_REQUEST['Search']['Prop']) )
        {
            $Filter->Set_Search();
            $Filter->Set_Search($_REQUEST['Search']['Prop'], $_REQUEST['Search']['Value']);
            $Filter->Page = 1;
        }
        //  Sorting
        if ( isset($_REQUEST['Sort']['List']) )
        {
            $Filter->Set_Sort();
            foreach ($_REQUEST['Sort']['List'] as $prop => $value)
            {
                $Filter->Set_Sort($prop, $value);
            }
        }
        else if ( isset($_REQUEST['Sort']['Prop']) )
        {
            $Filter->Set_Sort();
            $Filter->Set_Sort($_REQUEST['Sort']['Prop'], $_REQUEST['Sort']['Value']);
        }
    }

    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        $Filter = Zero_Filter::Factory($this->Model);
        //  Move to one level up or down for catalogs

        //  МНМЦИАЛИЗАЦИЯ ПОЛЕЙ ГРИДА
        $props_grid = $this->Model->Get_Config_Grid();
        //  Remove the coupling condition
        if ( isset($this->Params['obj_parent_prop']) )
            unset($props_grid[$this->Params['obj_parent_prop']]);
        //  Remove the user conditions
        $users_condition = Zero_App::$Users->Get_Condition();
        foreach ($users_condition as $prop => $value)
        {
            if ( 1 == count($value) )
                unset($props_grid[$prop]);
        }
        $props = [];
        foreach ($props_grid as $prop => $row)
        {
            $props[] = $row['AliasDB'] . ' AS ' . $prop;
        }

        // УСЛОВИЯ ЗАПРОСА ДАННЫХ ДЛЯ ГРИДА
        $this->Model->AR->Sql_Where_Filter($Filter);
        //  Condition of cross connection
        $this->Model->DB_From($this->Params);
        //  The coupling condition
        if ( isset($this->Params['obj_parent_prop']) )
        {
            if ( 0 < $this->Params['obj_parent_id'] )
                $this->Model->AR->Sql_Where('z.' . $this->Params['obj_parent_prop'], '=', $this->Params['obj_parent_id']);
            else
                $this->Model->AR->Sql_Where_IsNull('z.' . $this->Params['obj_parent_prop']);
        }
        //  The user conditions
        foreach (array_keys($this->Model->Get_Config_Prop()) as $prop)
        {
            if ( isset($users_condition[$prop]) )
                $this->Model->AR->Sql_Where_In('z.' . $prop, array_keys($users_condition[$prop]));
        }
        unset($users_condition);

        //  ПОЛУЧЕНИЕ ДАННЫХ
        $data_grid = $this->Model->AR->Select_Array($props, false);
        //  Count
        $pager_count = $this->Model->AR->Select_Count();
        //  Unrelated
        //  TODO sample catalog move (for linked Add)
        $data_link = [];
        $this->Model->AR->Sql_Reset();

        //  Template
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('Interface', Zero_App::$Section->Get_Navigation_Child());
        $this->View->Assign('PropsGrid', $props_grid);
        $this->View->Assign('DataGrid', $data_grid);
        $this->View->Assign('Params', $this->Params);
        $this->View->Assign('Object', $this->Model);
        $this->View->Assign('DataLink', $data_link);
        $this->View->Assign('pid', $this->Params['obj_parent_id']);
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
        // Page by page
        $this->View->Assign('PagerPage', $Filter->Page);
        $this->View->Assign('PagerPageItem', Zero_App::$Config->View_PageItem);
        $this->View->Assign('PagerPageStep', Zero_App::$Config->View_PageStep);
        $this->View->Assign('PagerCount', $pager_count);
        //  Filter
        $this->View->Assign('Filter', $Filter->Get_Filter());
        $this->View->Assign('Search', $Filter->Get_Search());
        $this->View->Assign('Sort', $Filter->Get_Sort());
    }

    /**
     * Delete an object.
     *
     * - Reset Cache removed object
     * - Remove from session
     * - Remove binary data object
     * - Remove cross relation
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Remove()
    {
        $ObjectRem = Zero_Model::Make($this->ModelName, $_REQUEST['obj_id']);
        //  Remove binary data object
        $path = ZERO_PATH_DATA . '/' . strtolower($ObjectRem->Source) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($ObjectRem->ID) . '/' . $ObjectRem->ID;
        if ( is_dir($path) )
            Zero_Lib_FileSystem::Folder_Remove($path);
        // Remove from session
        $ObjectRem->Factory_Unset(1);
        //  Reset Cache
        $ObjectRem->Cache->Reset();
        //  Remove
        if ( $ObjectRem->AR->Delete() )
            return $this->Set_Message('Remove', 0);
        else
            return $this->Set_Message('Error_Remove', 1, false);
    }
}
