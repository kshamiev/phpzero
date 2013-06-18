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
 */
abstract class Zero_Crud_Edit extends Zero_Controller
{
    /**
     * The table stores the objects handled by this controller.
     *
     * @var string
     */
    protected $Source = '';

    /**
     * Template view
     *
     * @var string
     */
    protected $Template = __CLASS__;

    /**
     * Take into account the conditions user
     *
     * @var boolean
     */
    protected $User_Condition = true;

    /**
     * Initialize the stack chunks
     *
     * @param string $action action
     */
    protected function Init_Chunks($action)
    {
        $this->Set_Chunk('Init');
        $this->Set_Chunk('Filter');
        $this->Set_Chunk('Action');
        $this->Set_Chunk('View');
    }

    /**
     * Initialization of the input parameters
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Init($action)
    {
        $this->View = new Zero_View($this->Template);
        $this->Model = Zero_Model::Make($this->Source, Zero_App::$Route->obj_id, true);
        return true;
    }

    /**
     * Initialization filters
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     */
    protected function Chunk_Filter($action)
    {
        $Filter = Zero_Filter::Factory($this->Model);
        if ( false == $Filter->IsInit )
        {
            $condition = Zero_App::$Users->Get_Condition();
            foreach ($this->Model->Get_Config_Filter() as $prop => $row)
            {
                if ( $row['Filter'] )
                {
                    $method = 'Add_Filter_' . $row['Filter'];
                    if ( isset($condition[$prop]) && $this->User_Condition )
                    {
                        if ( 1 < count($condition[$prop]) )
                            $Filter->$method($prop, 1, $condition[$prop]);
                        else
                            $Filter->$method($prop, 0, $condition[$prop]);
                    }
                    else
                        $Filter->$method($prop, 1, 1);
                }
                if ( $row['Search'] )
                {
                    $method = 'Add_Search_' . $row['Search'];
                    $Filter->$method($prop);
                }
                if ( $row['Sort'] )
                    $Filter->Add_Sort($prop);
            }
            $Filter->IsInit = true;
        }

        //  Page by page
        if ( 0 < Zero_App::$Route->page_id )
            $Filter->Page = Zero_App::$Route->page_id;

        return true;
    }

    /**
     * Create views.
     *
     * @param string $action action
     * @return boolean flag run of the next chunk
     * @throws Exception
     */
    protected function Chunk_View($action)
    {
        $Filter = Zero_Filter::Factory($this->Model);

        //  Initialize the fields in the form
        $props_form = $this->Model->Get_Config_Form();
        //  Remove the coupling condition
        if ( isset($this->Params['obj_parent_prop']) )
            unset($props_form[$this->Params['obj_parent_prop']]);
        //  Remove the user conditions
        if ( true == $this->User_Condition )
        {
            $users_condition = Zero_App::$Users->Get_Condition();
            foreach (array_keys($this->Model->Get_Config_Prop()) as $prop)
            {
                if ( isset($users_condition[$prop]) )
                {
                    if ( 0 < $this->Model->ID && !isset($users_condition[$prop][$this->Model->$prop]) )
                        throw new Exception('Access Denied', 403);
                    if ( 1 == count($users_condition[$prop]) )
                        unset($props_form[$prop]);
                }
            }
        }

        //  Data
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('Interface', Zero_App::$Section->Get_Navigation_Child());
        $this->View->Assign('Params', $this->Params);
        $this->View->Assign('Object', $this->Model);
        $this->View->Assign('Props', $props_form);
        //  Filter
        $this->View->Assign('Filter', $Filter->Get_Filter());
        //  Navigation parent
        $this->View->Assign('url_parent', (0 < Zero_App::$Route->obj_parent_id) ? '-pid-' . Zero_App::$Route->obj_parent_id : '');
        return true;
    }

    /**
     *  Adding an object
     *
     * @return boolean flag run of the next chunk
     */
    protected function Action_Add()
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
        return true;
    }

    /**
     * Save object
     *
     * @return boolean flag run of the next chunk
     */
    protected function Action_Save()
    {
        if ( !isset($_REQUEST['Prop']) )
            $_REQUEST['Prop'] = [];

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
            return $this->Set_Message('Error_Validate', 1);
        }

        // Save
        if ( 0 < $this->Model->ID )
        {
            if ( false == $this->Model->DB->Update() )
                return $this->Set_Message('Error_Save', 1);
        }
        else
        {
            if ( false == $this->Model->DB->Insert() )
                return $this->Set_Message('Error_Save', 1);

            //  When you add an object having a cross (many to many) relationship with the parent object
            if ( isset($this->Params['obj_parent_table']) )
            {
                //  target parent object
                $Object = Zero_Model::Make($this->Params['obj_parent_table'], $this->Params['obj_parent_id']);
                //  creating a connection
                if ( !$this->Model->DB->Insert_Cross($Object) )
                    return $this->Set_Message('Error_Save', 1);
            }
        }

        Zero_App::$Route->obj_id = $this->Model->ID;

        //  Reset Cache
        $this->Model->Cache->Reset();

        return $this->Set_Message('Save', 0);
    }
}
