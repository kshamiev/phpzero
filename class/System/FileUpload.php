<?php

/**
 * Controller. Editing a text file.
 *
 * @package Zero.System.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_System_FileUpload extends Zero_Controller
{
    public function Action_Default()
    {
        // инициализация
        $sha1 = sha1_file($_FILES['myFile']['tmp_name']);
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        if ( !is_dir($path) )
            mkdir($path, 0777, true);
        $path .= '/' . $sha1;
        // перемещение во временную папку
        if ( false == move_uploaded_file($_FILES['myFile']['tmp_name'], $path) )
            Zero_App::ResponseJson("", 409, "не загружено");
        $_FILES['myFile']['tmp_name'] = $path;
        // сохранение информации о загруженном файле
        $data = json_encode($_FILES['myFile'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Zero_Lib_FileSystem::File_Save($path . '.txt', $data);
        Zero_App::ResponseJson($sha1, 200, "загружено");
    }
}

/*
array (
 'myFile' =>
 array (
 'name' => 'Hydrangeas.jpg',
 'type' => 'image/jpeg',
 'tmp_name' => '/tmp/phpwNqbsw',
 'error' => 0,
 'size' => 595284,
 ),
)
*/