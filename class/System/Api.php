<?php

/**
 * Общие и системные функциональности.
 *
 * @package Zero.System.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_System_Api extends Zero_Controller
{
    /**
     * Управляющий метод (API).
     */
    public function Action_Default()
    {
        $this->View = new Zero_View();
        switch ( $_SERVER['REQUEST_METHOD'] )
        {
            case 'GET':
                break;
            case 'POST':
                switch ( Zero_App::$Route->UrlSegment[2] )
                {
                    case 'upload':
                        $this->Put_Upload(); // Авторизация пользователя
                }
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;
            case 'OPTIONS':
                break;
        }
        Zero_App::ResponseJson($_SERVER['REQUEST_METHOD'] . ':' . ZERO_URL, 409, "Запрос не определен");
    }

    /**
     * Загрузка бинарных данных
     */
    protected function Put_Upload()
    {
        if ( !isset($_FILES['myFile']) )
            Zero_App::ResponseJson("", 409, "Данные не получены");

        $sha1 = sha1_file($_FILES['myFile']['tmp_name']);
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        if ( !is_dir($path) )
            mkdir($path, 0777, true);
        $arr = explode('.', $_FILES['myFile']['name']);
        $ext = array_pop($arr);
        $pathData = $path . '/' . $sha1 . "." . $ext;
        $pathInfo = $path . '/' . $sha1 . ".txt";
        // перемещение во временную папку
        if ( false == move_uploaded_file($_FILES['myFile']['tmp_name'], $pathData) )
            Zero_App::ResponseJson("", 409, "Файл не загружен");
        $_FILES['myFile']['tmp_name'] = $pathData;
        // сохранение информации о загруженном файле
        $data = json_encode($_FILES['myFile'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Zero_Lib_FileSystem::File_Save($pathInfo, $data);
        Zero_App::ResponseJson([$sha1, '/' . explode('/www/', $path)[1], $ext], 200, "Файл загружен");
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
    }
}
