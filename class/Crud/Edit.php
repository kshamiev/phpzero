<?php

/**
 * Abstract controller for editing objects.
 *
 * @package Zero.Controller.Crud
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
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
     * Add
     *
     * @return Zero_View
     */
    public function Action_Add()
    {
        $this->Chunk_Init();
        $this->Chunk_Add();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Save
     *
     * @return string
     */
    public function Action_Save()
    {
        $this->Chunk_Init();
        $this->Chunk_Save();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * ������������� �������� ����������� �� ��� ����������
     *
     * @return boolean ���� ��������
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
        $this->View = new Zero_View($this->ViewName);
        $this->Model = Zero_Model::Makes($this->ModelName, $this->Params['id'], true);
        //
        Zero_Filter::Factory($this->Model);
        return true;
    }

    /**
     * ����� ������ �������� ����������� � ������
     *
     * @return boolean ���� ��������
     * @throws Exception
     */
    protected function Chunk_View()
    {
        $Filter = Zero_Filter::Factory($this->Model);
        //  Initialize the fields in the form
        $props_form = $this->Model->Get_Config_Form(get_class($this));
        //  Remove the coupling condition
        if ( isset($this->Params['obj_parent_prop']) )
            unset($props_form[$this->Params['obj_parent_prop']]);

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
        $pathObject = '/' . strtolower($this->Model->Source) . '/' . Zero_Helper_File::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID;
        if ( !is_dir(ZERO_PATH_DATA . $pathObject) )
            mkdir(ZERO_PATH_DATA . $pathObject, 0777, true);
        $_SESSION['pathObject'] = $pathObject;
        //
        return true;
    }

    /**
     *  Adding an object
     *
     * @return boolean ���� ��������
     */
    protected function Chunk_Add()
    {
        $this->Params['id'] = 0;
        $this->Model->ID = 0;
        foreach ($this->Model->Get_Config_Prop(get_class($this)) as $prop => $row)
        {
            if ( 'D' == $row['DB'] && 'NO' == $row['IsNull'] )
            {
                $this->Model->$prop = date('Y-m-d H:i:s');
            }
            else if ( 0 < strlen($row['Default']) )
                $this->Model->$prop = $row['Default'];
            else if ( 'S' == $row['DB'] )
                $this->Model->$prop = [];
            else
                $this->Model->$prop = null;
        }
        return true;
    }

    /**
     * Save object
     *
     * @return boolean ���� ��������
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

        $this->Model->VL->Validate($_REQUEST['Prop'], get_class($this));
        if ( 0 < count($this->Model->VL->Get_Errors()) )
        {
            $this->View->Assign('Error_Validator', $this->Model->VL->Get_Errors());
            $this->SetMessage(5100, [$this->Model->Name, $this->Model->ID]);
            return false;
        }

        // Save
        if ( 0 < $this->Model->ID )
        {
            if ( false == $this->Model->Save() )
            {
                $this->SetMessage(5000, [$this->Model->Name, $this->Model->ID]);
                return false;
            }
        }
        else
        {
            if ( false == $this->Model->Save() )
            {
                $this->SetMessage(5000, [$this->Model->Name, $this->Model->ID]);
                return false;
            }
            //  When you add an object having a cross (many to many) relationship with the parent object
            if ( isset($this->Params['obj_parent_table']) && method_exists($this->Model, $method = 'DB_Cross_' . $this->Params['obj_parent_table']) )
            {
                $this->Model->$method($this->Params['obj_parent_id']);
            }
        }
        $this->Params['id'] = $this->Model->ID;

        //  Reset Cache
        $this->Model->Cache->Reset();

        $this->SetMessage(2000, [$this->Model->Name, $this->Model->ID]);
        return true;
    }
}
