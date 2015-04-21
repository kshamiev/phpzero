<?php
/**
 * Общие и системные функциональности.
 *
 * @package Api.Zero
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_Api_Upload extends Zero_Controller
{
    /**
     * (API) Загрузка бинарных данных.
     */
//    public function Action_Upload()
//    {
//        $this->View = new Zero_View();
//        switch ( $_SERVER['REQUEST_METHOD'] )
//        {
//            case 'GET':
//                break;
//            case 'POST':
//                $this->Action_UploadPut();
//                break;
//            case 'PUT':
//                break;
//            case 'DELETE':
//                break;
//            case 'OPTIONS':
//                break;
//        }
//        Zero_App::ResponseJson($_SERVER['REQUEST_METHOD'] . ':' . ZERO_URL, 409, 409, ["Запрос не реализован"]);
//    }
//
    /**
     * Загрузка бинарных данных
     */
    public function Action_POST()
    {
        $index = 'file';
        if ( !isset($_FILES[$index]) )
            Zero_App::ResponseJson("", 409, 409, ["Данные не получены"]);

        $sha1 = sha1_file($_FILES[$index]['tmp_name']);
        $path = dirname(ZERO_PATH_DATA) . '/temp';
        if ( !is_dir($path) )
            mkdir($path, 0777, true);
        $arr = explode('.', $_FILES[$index]['name']);
        $ext = array_pop($arr);
        $pathData = $path . '/' . $sha1 . "." . $ext;
        $pathInfo = $path . '/' . $sha1 . ".txt";
        // перемещение во временную папку
        if ( false == move_uploaded_file($_FILES[$index]['tmp_name'], $pathData) )
            Zero_App::ResponseJson(null, 409, 409, ["Файл не загружен"]);
        $_FILES[$index]['tmp_name'] = $pathData;
        // сохранение информации о загруженном файле
        $data = json_encode($_FILES[$index], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Zero_Helper_File::File_Save($pathInfo, $data);
        Zero_App::ResponseJson([$sha1, '/' . explode('/www/', $path)[1], $ext], 200, 0, ["Файл загружен"]);
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
