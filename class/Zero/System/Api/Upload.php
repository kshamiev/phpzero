<?php
/**
 * Загрузка бинарных данных через веб форму (ajax)
 *
 * @package Zero.Api
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_System_Api_Upload extends Zero_Controller
{
    /**
     * Загрузка бинарных данных через веб форму (ajax)
     */
    public function Action_POST()
    {
        $index = 'file';
        if ( !isset($_FILES[$index]) )
            Zero_App::ResponseJson200("", -1, ["Данные не получены"]);

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
            Zero_App::ResponseJson200(null, -1, ["Файл не загружен"]);
        $_FILES[$index]['tmp_name'] = $pathData;
        // сохранение информации о загруженном файле
        $data = json_encode($_FILES[$index], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Helper_File::File_Save($pathInfo, $data);
        Zero_App::ResponseJson200([$sha1, str_replace(ZERO_PATH_SITE, '', $path), $ext]);
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
