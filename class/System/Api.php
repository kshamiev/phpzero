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
                $this->Chunk_GET();
                break;
            case 'POST':
                $this->Chunk_POST();
                break;
            case 'PUT':
                $this->Chunk_PUT();
                break;
            case 'DELETE':
                $this->Chunk_DELETE();
                break;
            case 'OPTIONS':
                $this->Chunk_OPTIONS();
                break;
        }
        Zero_App::ResponseJson($_SERVER['REQUEST_METHOD'], 409, "Метод запроса не определен");
    }

    /**
     * Получение (GET).
     */
    protected function Chunk_GET()
    {
        Zero_App::ResponseJson("", 409, "Операция не определена");
    }

    /**
     * Добавление (POST).
     *
     * Загрузка бинарных данных
     */
    protected function Chunk_POST()
    {
        switch ( Zero_App::$Route->ApiUrlSegment[2] )
        {
            case 'upload':
                $this->Put_Upload(); // Авторизация пользователя
                break;
        }
        Zero_App::ResponseJson("", 409, "Операция не определена");
    }

    /**
     * Изменение (PUT).
     */
    protected function Chunk_PUT()
    {
        Zero_App::ResponseJson("", 409, "Операция не определена");
    }

    /**
     * Удаление (DELETE).
     */
    protected function Chunk_DELETE()
    {
        Zero_App::ResponseJson("", 409, "Операция не определена");
    }

    /**
     * Получение опций (OPTIONS).
     */
    protected function Chunk_OPTIONS()
    {
        Zero_App::ResponseJson("", 409, "Операция не определена");
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
        $path .= '/' . $sha1;
        // перемещение во временную папку
        if ( false == move_uploaded_file($_FILES['myFile']['tmp_name'], $path) )
            Zero_App::ResponseJson("", 409, "не загружено");
        $_FILES['myFile']['tmp_name'] = $path;
        // сохранение информации о загруженном файле
        $data = json_encode($_FILES['myFile'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Zero_Lib_FileSystem::File_Save($path . '.txt', $data);
        Zero_App::ResponseJson([$sha1, '/' . explode('/www/', $path)[1]], 200, "загружено");
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