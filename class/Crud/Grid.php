<?php
/**
 * Controller. Abstract controller for viewing a list of items.
 *
 * @package Zero.Crud
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
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

        $Filter = Zero_Filter::Factory($this->Model);
        $Filter->Set($_REQUEST['Filter'], $_REQUEST['Search'], $_REQUEST['Sort']);

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
        if ( isset($_REQUEST['pid']) )
            $this->Params['obj_parent_id'] = $_REQUEST['pid'];
        else if ( empty($this->Params['obj_parent_id']) )
            $this->Params['obj_parent_id'] = 0;
        //
        if ( isset($_REQUEST['id']) )
            $this->Params['id'] = $_REQUEST['id'];
        else if ( empty($this->Params['id']) )
            $this->Params['id'] = 0;
        //
        $this->View = new Zero_View($this->Template);
        $this->Model = Zero_Model::Makes($this->ModelName);
        //
        $Filter = Zero_Filter::Factory($this->Model);
        if ( isset($_REQUEST['pg']) && 0 < $_REQUEST['pg'] )
            $Filter->Page = $_REQUEST['pg'];
        //
        return true;
    }

    /**
     *  Adding an object
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Add()
    {

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
        $props_grid = $this->Model->Get_Config_Grid(get_class($this));
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
        unset($props_grid['ID']);

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
        foreach (array_keys($this->Model->Get_Config_Prop(get_class($this))) as $prop)
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

        // Переводы значений Radio, Select, Checkbox
        $filterList = $Filter->Get_Filter();
        foreach ($data_grid as $key => $row)
        {
            foreach ($row as $prop => $value)
            {
                if ( isset($filterList[$prop]['Form']) )
                {
                    $f = $filterList[$prop]['Form'];
                    if ( $f == 'Radio' || $f == 'Select' || $f == 'Checkbox' )
                    {
                        $row[$prop] = $filterList[$prop]['List'][$value];
                    }
                }
            }
            $data_grid[$key] = $row;
        }

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
        $ObjectRem = Zero_Model::Makes(get_class($this->Model), $_REQUEST['id']);
        //  Remove binary data object
        $path = ZERO_PATH_DATA . '/' . strtolower($ObjectRem->Source) . '/' . Zero_Helper_File::Get_Path_Cache($ObjectRem->ID) . '/' . $ObjectRem->ID;
        if ( is_dir($path) )
            Zero_Helper_File::Folder_Remove($path);
        // Remove from session
        $ObjectRem->Factory_Unset(1);
        //  Reset Cache
        $ObjectRem->Cache->Reset();
        //  Remove
        if ( $ObjectRem->AR->Delete() )
        {
            $this->SetMessage(220, [$this->Model->Name, $this->Model->ID]);
            return true;
        }
        else
        {
            $this->SetMessage(520, [$this->Model->Name, $this->Model->ID]);
            return false;
        }
    }
}
