<?php

/**
 * Controller. Abstract controller for viewing a list of items.
 *
 * @package Zero.Controller.Crud
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
abstract class Zero_Web_Crud_Grid extends Zero_Controller
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
    protected $ViewName = __CLASS__;

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Удаление
     *
     * @return Zero_View
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

        $filter = isset($_REQUEST['Filter']) ? $_REQUEST['Filter'] : [];
        $search = isset($_REQUEST['Search']) ? $_REQUEST['Search'] : [];
        $sort = isset($_REQUEST['Sort']) ? $_REQUEST['Sort'] : [];
        $Filter = Zero_Filter::Factory($this->Model);

        //  Filters
        foreach ($Filter->Get_Filter() as $Prop => $row)
        {
            if ( isset($filter[$Prop]) )
                $Filter->Set_Filter($Prop, $filter[$Prop]);
            else
                $Filter->Set_Filter($Prop, null);
        }
        //  Search
        $Filter->Set_Search();
        if ( isset($search['List']) )
        {
            foreach ($search['List'] as $prop => $value)
            {
                $Filter->Set_Search($prop, $value);
            }
        }
        else if ( isset($search['Prop']) )
        {
            $Filter->Set_Search($search['Prop'], $search['Value']);
        }
        //  Sorting
        $Filter->Set_Sort();
        if ( isset($sort['List']) )
        {
            foreach ($sort['List'] as $prop => $value)
            {
                $Filter->Set_Sort($prop, $value);
            }
        }
        else if ( isset($sort['Prop']) )
        {
            $Filter->Set_Sort($sort['Prop'], $sort['Value']);
        }
        // page
        $Filter->Page = 1;
        $Filter->IsSet = true;

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
        $Filter->Page = 1;

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
        $this->View = new Zero_View($this->ViewName);
        $this->Model = Zero_Model::Makes($this->ModelName);
        //
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
        $Filter = Zero_Filter::Factory($this->Model);
        if ( isset($_REQUEST['pg']) && 0 < $_REQUEST['pg'] )
            $Filter->Page = $_REQUEST['pg'];
        else if ( 0 == $Filter->Page )
            $Filter->Page = 1;
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

        //  ИНИЦИАЛИЗАЦИЯ ПОЛЕЙ ГРИДА
        $props_grid = $this->Model->Get_Config_Grid(get_class($this));
        if ( isset($this->Params['obj_parent_prop']) )
        {
            unset($props_grid[$this->Params['obj_parent_prop']]);
        }
        $props = [];
        foreach ($props_grid as $prop => $row)
        {
            $props[] = $row['AliasDB'] . ' AS ' . $prop;
        }
        unset($props_grid['ID']);
        unset($props_grid['Id']);
        unset($props_grid['id']);

        //  FROM
        if ( method_exists($this->Model, 'Get_Query_From') )
            $this->Model->AR->Sql_From($this->Model->Get_Query_From($this->Params));
        if ( method_exists($this->Model, 'AR_From') )
            $this->Model->AR_From($this->Params);

        // WHERE
        $this->Model->AR->Sql_Where_Filter($Filter);

        // WHERE Custom
        if ( method_exists($this->Model, 'AR_Where') )
            $this->Model->AR_Where($this->Params);

        // WHERE Прямая родительская связь
        if ( isset($this->Params['obj_parent_prop']) )
        {
            if ( 0 < $this->Params['obj_parent_id'] )
                $this->Model->AR->Sql_Where('z.' . $this->Params['obj_parent_prop'], '=', $this->Params['obj_parent_id']);
            else
                $this->Model->AR->Sql_Where_IsNull('z.' . $this->Params['obj_parent_prop']);
        }

        //  ПОЛУЧЕНИЕ ДАННЫХ
        $data_grid = $this->Model->AR->Select_Array($props, false);
        //  Count
        $pager_count = $this->Model->AR->Select_Count();
        //  Unrelated
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
                        if ( isset($filterList[$prop]['List'][$value]) )
                            $row[$prop] = $filterList[$prop]['List'][$value];
                        else
                        {
                            Zero_Logs::Custom_DateTime('ERROR', [get_class($this->Model), $prop]);
                            $row[$prop] = 'не известно';
                        }
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
        return true;
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
        if ( $ObjectRem->Remove() )
        {
            $this->SetMessage(2200, [$this->Model->Name, $this->Model->ID]);
            return true;
        }
        else
        {
            $this->SetMessage(5200, [$this->Model->Name, $this->Model->ID]);
            return false;
        }
    }
}
