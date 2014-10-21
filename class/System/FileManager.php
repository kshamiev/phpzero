<?php

/**
 * Controller. File manager.
 *
 * The list of folders and files by page by page.
 *
 * - Downloading and deleting files.
 * - Create and delete folders.
 * - Change text files.
 *
 * @package Zero.System.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_System_FileManager extends Zero_Controller
{
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
    protected function Chunk_Init()
    {
        $this->View = new Zero_View(__CLASS__);
        if ( !isset($this->Params['obj_parent_path']) )
            $this->Params['obj_parent_path'] = array('..' => ZERO_PATH_SITE);
    }

    /**
     * Create views.
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_View()
    {
        $path = end($this->Params['obj_parent_path']);
        $folder_mas = array();
        $files_mas = array();
        $dr = opendir($path);
        while ( false != $file_name = readdir($dr) )
        {
            if ( '..' == $file_name || '.' == $file_name )
                continue;
            $file_path = $path . '/' . $file_name;
            if ( is_dir($file_path) )
                $folder_mas[$file_name]['edit'] = date("d.m.Y H:i:s", filemtime($file_path));
            else
            {
                $files_mas[$file_name]['edit'] = date("d.m.Y H:i:s", filemtime($file_path));
                $files_mas[$file_name]['size'] = (filesize($file_path) / 1000) . ' kb';
                $ar = explode('.', $file_name);
                $files_mas[$file_name]['ext'] = strtolower(array_pop($ar));
            }
        }
        closedir($dr);
        //  grid
        $this->View->Assign('Section', Zero_App::$Section);
        $this->View->Assign('Interface', Zero_App::$Section->Get_Navigation_Child());
        $this->View->Assign('Params', $this->Params);
        $this->View->Assign('path_parent', end($this->Params['obj_parent_path']));
        $this->View->Assign('Action', Zero_App::$Section->Get_Action_List());
        //  array folder and files
        ksort($folder_mas);
        $this->View->Assign('folder_mas', $folder_mas);
        ksort($files_mas);
        $this->View->Assign('files_mas', $files_mas);
        //  full count
        $this->View->Assign('DataCount', count($folder_mas) + count($files_mas));
        //  Allowed file extensions to edit
        $this->View->Assign('file_edit_flag', array('txt', 'ini', 'log', 'php', 'htm', 'html', 'css', 'js'));
    }

    public function Action_FolderGo()
    {
        $this->Chunk_Init();
        $this->Chunk_FolderGo();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Move to one level up or down for catalogs
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Chunk_FolderGo()
    {
        //  move up
        if ( isset($this->Params['obj_parent_path'][$_REQUEST['dir_name']]) )
        {
            $flag = true;
            foreach ($this->Params['obj_parent_path'] as $name => $path)
            {
                if ( $name == $_REQUEST['dir_name'] )
                    $flag = false;
                else if ( false == $flag )
                    unset($this->Params['obj_parent_path'][$name]);
            }
        }
        //  move down
        else
        {
            $path = end($this->Params['obj_parent_path']);
            $this->Params['obj_parent_path'][$_REQUEST['dir_name']] = $path . '/' . $_REQUEST['dir_name'];
        }
    }

    public function Action_FolderRemove()
    {
        $this->Chunk_Init();
        $this->Chunk_FolderRemove();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Delete the folder and its contents
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_FolderRemove()
    {
        if ( !$_REQUEST['dir_name'] )
            return $this->Set_Message('Error_FolderRemove', 1, false);
        $path = end($this->Params['obj_parent_path']) . '/' . $_REQUEST['dir_name'];
        Zero_Lib_FileSystem::Folder_Remove($path);
        return $this->Set_Message('FolderRemove', 0);
    }

    public function Action_FileRemove()
    {
        $this->Chunk_Init();
        $this->Chunk_FileRemove();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Deleting a file
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_FileRemove()
    {
        if ( !$_REQUEST['file_name'] )
            return $this->Set_Message('Error_FileRemove', 1, false);
        $path = end($this->Params['obj_parent_path']) . '/' . $_REQUEST['file_name'];
        unlink($path);
        return $this->Set_Message('FileRemove', 0);
    }

    public function Action_FileDownLoad()
    {
        $this->Chunk_Init();
        $this->Chunk_FileDownLoad();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * The download the user to the file
     */
    protected function Chunk_FileDownLoad()
    {
        Zero_App::ResponseFile(end($this->Params['obj_parent_path']) . '/' . $_REQUEST['file_name']);
    }

    public function Action_FileUpload()
    {
        $this->Chunk_Init();
        $this->Chunk_FileUpload();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Download the file to the server
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_FileUpload()
    {
        if ( 4 != $_FILES['FileUpload']['error'] )
        {
            //  файл не загружен или загружен с ошибками
            if ( !is_uploaded_file($_FILES['FileUpload']['tmp_name']) || 0 != $_FILES['FileUpload']['error'] )
            {
                Zero_Logs::Set_Message_Error("файловый менеджер - {$_FILES['FileUpload']['error']}");
                return $this->Set_Message('Error_FileUpload', 1, false);
            }
            $filename = Zero_Lib_String::Transliteration_FileName($_FILES['FileUpload']['name']);
            $path = end($this->Params['obj_parent_path']) . '/' . $filename;
            copy($_FILES['FileUpload']['tmp_name'], $path);
            chmod($path, 0666);
            $this->Set_Message('FileUpload', 0);
        }
        return true;
    }

    public function Action_FolderAdd()
    {
        $this->Chunk_Init();
        $this->Chunk_FolderAdd();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Creating a folder
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Chunk_FolderAdd()
    {
        if ( !isset($_REQUEST['FolderName']) || !$_REQUEST['FolderName'] )
            return $this->Set_Message('Error_FolderAdd', 1, false);
        $path = end($this->Params['obj_parent_path']) . '/' . Zero_Lib_String::Transliteration_FileName($_REQUEST['FolderName']);
        mkdir($path);
        chmod($path, 0777);
        return $this->Set_Message('FolderAdd', 0);
    }

    /**
     * Change the file
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_EditFile()
    {
        return $this->View;
    }
}