<?php

/**
 * Controller. Abstract controller for editing objects.
 *
 * @package Zero.Crud.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 * TODO 'obj_parent_table' доработать этот момент
 */
abstract class Zero_Crud_Edit extends Zero_Controller
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
        if ( $this->Params['id'] == 0 )
            $this->Chunk_Add();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Add()
    {
        $this->Chunk_Init();
        $this->Chunk_Filter();
        $this->Chunk_Add();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Initialization of the stack chunks and input parameters
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_Save()
    {
        $this->Chunk_Init();
        $this->Chunk_Filter();
        $this->Chunk_Save();
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
        $this->Model = Zero_Model::Make($this->ModelName, $this->Params['id'], true);
    }

    /**
     * Initialization filters
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
//                    $Filter->$method($prop, $row);
//                }
//                if ( isset($row['Sort']) && $row['Sort'] )
//                {
//                    $Filter->Add_Sort($prop, $row);
//                    if ( 'Sort' == $prop || 'ID' == $prop )
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
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     * @throws Exception
     */
    protected function Chunk_View()
    {
        $Filter = Zero_Filter::Factory($this->Model);
        //  Initialize the fields in the form
        $props_form = $this->Model->Get_Config_Form();
        //  Remove the coupling condition
        if ( isset($this->Params['obj_parent_prop']) )
            unset($props_form[$this->Params['obj_parent_prop']]);
        //  Remove the user conditions
        $users_condition = Zero_App::$Users->Get_Condition();
        foreach (array_keys($this->Model->Get_Config_Prop()) as $prop)
        {
            if ( isset($users_condition[$prop]) )
            {
                if ( 0 < $this->Model->ID && !isset($users_condition[$prop][$this->Model->$prop]) )
                    Zero_App::ResponseError(403);
                if ( 1 == count($users_condition[$prop]) )
                    unset($props_form[$prop]);
            }
        }

        //  Data
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('Interface', Zero_App::$Section->Get_Navigation_Child());
        $this->View->Assign('Params', $this->Params);
        $this->View->Assign('Object', $this->Model);
        $this->View->Assign('ObjectID', $this->Model->ID);
        $this->View->Assign('Props', $props_form);
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
        //  Filter
        $this->View->Assign('Filter', $Filter->Get_Filter());
        // CKEDITOR - this -> Object
        $pathObject = '/' . strtolower($this->Model->Get_Source()) . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID;
        //$pathObject = '/ssss';
        if ( !is_dir(ZERO_PATH_DATA . $pathObject) )
            mkdir(ZERO_PATH_DATA . $pathObject, 0777, true);
        $_SESSION['pathObject'] = $pathObject;
        //
        return true;
    }

    /**
     *  Adding an object
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Add()
    {
        $this->Model->Set_ID(0);
        foreach ($this->Model->Get_Config_Prop() as $prop => $row)
        {
            if ( 'NOW' == $row['Default'] )
                $this->Model->$prop = date('Y-m-d H:i:s');
            else if ( $row['Default'] )
                $this->Model->$prop = $row['Default'];
            else if ( 'S' == $row['DB'] )
                $this->Model->$prop = [];
            else
                $this->Model->$prop = null;
        }
    }

    /**
     * Save object
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_Save()
    {
        if ( !isset($_REQUEST['Prop']) )
            $_REQUEST['Prop'] = [];

        if ( 0 == $this->Model->ID )
            $this->Chunk_Add();

        //  Set to relation one to many
        if ( isset($this->Params['obj_parent_prop']) && 0 == $this->Model->ID )
        {
            $prop = $this->Params['obj_parent_prop'];
            if ( 0 < $this->Params['obj_parent_id'] )
                $this->Model->$prop = $this->Params['obj_parent_id'];
            else
                $this->Model->$prop = null;
        }

        //  Set the user conditions
        $users_condition = Zero_App::$Users->Get_Condition();
        foreach (array_keys($this->Model->Get_Config_Prop()) as $prop)
        {
            if ( isset($users_condition[$prop]) && 1 == count($users_condition[$prop]) )
                $this->Model->$prop = key($users_condition[$prop]);
        }

        $this->Model->VL->Validate($_REQUEST['Prop']);
        if ( 0 < count($this->Model->VL->Get_Errors()) )
        {
            $this->View->Assign('Error_Validator', $this->Model->VL->Get_Errors());
            return $this->Set_Message('Error_Validate', 1, false);
        }

        // Save
        if ( 0 < $this->Model->ID )
        {
            if ( false == $this->Model->AR->Update() )
                return $this->Set_Message('Error_Save', 1, false);
        }
        else
        {
            if ( false == $this->Model->AR->Insert() )
                return $this->Set_Message('Error_Save', 1, false);

            //  When you add an object having a cross (many to many) relationship with the parent object
            if ( isset($this->Params['obj_parent_table']) )
            {
                //  target parent object
                $Object = Zero_Model::Make($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
                //  creating a connection
                if ( !$this->Model->AR->Insert_Cross($Object) )
                    return $this->Set_Message('Error_Save', 1, false);
            }
        }

        $this->Params['id'] = $this->Model->ID;
        //        $_GET['id'] = $this->Model->ID;

        //  Reset Cache
        $this->Model->Cache->Reset();

        return $this->Set_Message('Save', 0);
    }
}
