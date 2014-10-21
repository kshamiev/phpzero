<?php

/**
 * Lib. A helper class for working with the file system.
 *
 * @package Zero.Lib
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
final class Zero_Lib_FileSystem
{
    /**
     * Spisok papok raspolozheniia binarny`kh danny`kh ob``ekta (ot identifikatora, kratny`i` 100)
     *
     * @var array (34 => 'path', ...)
     */
    private static $_Path_Cache = [];

    /**
     * Version checking each other
     *
     * @param string $version_check Check the version (x.x.x)
     * @param string $version_target The standard version (x.x.x)
     * @return int -1 version down $version_target, 0 version equal, 1 version up $version_target
     */
    public static function Check_Version($version_check, $version_target)
    {
        if ( !$version_check )
            return -1;
        $arr1 = explode('.', $version_check);
        $arr2 = explode('.', $version_target);
        //  release
        if ( $arr1[0] < $arr2[0] )
            return -1;
        else if ( $arr1[0] > $arr2[0] )
            return 1;
        //  feature
        if ( $arr1[1] < $arr2[1] )
            return -1;
        else if ( $arr1[1] > $arr2[1] )
            return 1;
        //  bugfix
        if ( $arr1[2] < $arr2[2] )
            return -1;
        else if ( $arr1[2] > $arr2[2] )
            return 1;
        return 0;
    }

    public static function Get_Modules()
    {
        $result = [];
        foreach (glob(ZERO_PATH_APPLICATION . '/*', GLOB_ONLYDIR) as $path)
        {
            $result[] = basename($path);
        }
        return $result;
    }

    /**
     * Getting the module configuration
     *
     * @param string $module module
     * @return array
     */
    public static function Get_Config($module)
    {
        $configuration = [];
        if ( $module = strtolower($module) )
        {
            if ( is_dir($path = ZERO_PATH_APPLICATION . '/' . $module . '/config') )
            {
                foreach (glob($path . '/*.php') as $config)
                {
                    $section = substr(basename($config), 0, -4);
                    $configuration[$section] = require $config;
                }
            }
        }
        return $configuration;
    }

    /**
     * Izmenenie razresheniia kartinki s vozmozhnost`iu povorota s kratnost`iu 90 gradusov.
     *
     * Povorot kartinki proishodit prezhde resai`za.
     *
     * @param string $src - put` do ishodnogo fai`la
     * @param string $dest - put` do generiruemogo fai`la
     * @param integer $width - shirina generiruemogo izobrazheniia, v pikseliakh
     * @param integer $height - vy`sota generiruemogo izobrazheniia, v pikseliakh
     * @param integer $rotate - gradus rotatcii (-1 = -90, 1 = 90, 2 = 180)
     * @param string $rgb - tcvet fona, po umolchaniiu - bely`i`
     * @param integer $quality - kachestvo generiruemogo JPEG, po umolchaniiu - maksimal`noe (100)
     * @return boolean flag uspeshnosti operatcii
     */
    public static function Image_Resize($src, $dest, $width = 0, $height = 0, $rotate = 0, $rgb = '0xFFFFFF', $quality = 100)
    {
        if ( !file_exists($src) )
        {
            return false;
        }
        $size = getimagesize($src);
        if ( $size === false )
        {
            return false;
        }
        // Opredeliaem ishodny`i` format po MIME-informatcii, predostavlennoi`
        // funktciei` getimagesize, i vy`biraem sootvetstvuiushchuiu formatu
        // imagecreatefrom-funktciiu.
        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;
        if ( !function_exists($icfunc) )
        {
            return false;
        }
        if ( !$width && !$height )
        {
            $width = $size[0];
            $height = $size[1];
        }
        else if ( $width && $height )
        {
            //
        }
        else if ( $width < $size[0] && $width )
        {
            $coefficient = $size[0] / $width;
            $height = ceil($size[1] / $coefficient);
        }
        else if ( $height < $size[1] && $height )
        {
            $coefficient = $size[1] / $height;
            $width = ceil($size[0] / $coefficient);
        }
        else
        {
            $width = $size[0];
            $height = $size[1];
        }
        //  rotate
        if ( -1 == $rotate || 1 == $rotate )
        {
            $n = $size[0];
            $size[0] = $size[1];
            $size[1] = $n;
        }
        //
        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);
        //  rotate
        if ( 2 == $rotate )
        {
            $isrc = imagerotate($isrc, 180, 0);
        }
        else if ( 0 < $rotate )
        {
            $isrc = imagerotate($isrc, -90, 0);
        }
        else if ( $rotate < 0 )
        {
            $isrc = imagerotate($isrc, 90, 0);
        }
        //
        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];
        $ratio = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);
        $new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
        $new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
        $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        imagejpeg($idest, $dest, $quality);
        imagedestroy($isrc);
        imagedestroy($idest);
        return true;
    }

    /**
     * Formirovanie ini fai`la
     *
     * Formiruet danny`e v ini formate
     * I zapisy`vaet v fai`l po ukazannomu puti.
     * Leebo iavliaetsia vozvrashchaemy`m znacheniem, esli put` ne ukazan.
     *
     * @param array $data massiv danny`kh dlia zapisi
     * @param integer $flag flag rezhima formirovaniia (1 - odnomerny`i`, 2 - dvukhmerny`i`, 3 - dvukhmerny`i` gde pervy`i` e`lement stanovitsia indeksom)
     * @param string $filename absoliutny`i` put` fai`la
     * @return boolean or string flag operatcii libo sobranny`i` ini fai`l v stroke
     */
    public static function File_Ini_Create($data, $flag, $filename = '')
    {
        if ( false == is_array($data) )
            return false;
        $cache = '';
        if ( 1 == $flag || 2 == $flag )
        {
            foreach ($data as $key => $val)
            {
                if ( false == is_array($val) )
                    $cache .= $key . '="' . $val . '"' . "\n";
                else
                {
                    $cache .= '[' . $key . ']' . "\n";
                    foreach ($val as $key2 => $val2)
                    {
                        $cache .= $key2 . '="' . $val2 . '"' . "\n";
                    }
                    $cache .= "\n";
                }
            }
        }
        else if ( 3 == $flag )
        {
            foreach ($data as $val)
            {
                $cache .= '[' . array_shift($val) . ']' . "\n";
                foreach ($val as $key2 => $val2)
                {
                    $cache .= $key2 . '="' . $val2 . '"' . "\n";
                }
                $cache .= "\n";
            }
        }
        //  esli put` k fai`lu ne ukazan
        if ( '' == $filename )
            return trim($cache);
        //  esli put` k fai`lu ukazan
        return self::File_Save($filename, $cache);
    }

    /**
     * Copying the contents of a directory
     *
     * If the filter is not set all the directories are copied.
     *
     * @param string $path_input directory source
     * @param string $path_output the target directory (if it creates no)
     * @param string $filter Filter directory names
     * @return bool
     */
    public static function Folder_Copy($path_input, $path_output, $filter = '')
    {
        if ( !is_dir($path_input) )
            return true;
        if ( !is_dir($path_output) )
            mkdir($path_output);
        chmod($path_output, 0755);
        $fp_folder = opendir($path_input);
        while ( false != $name_file = readdir($fp_folder) )
        {
            if ( '.' == $name_file || '..' == $name_file )
                continue;
            if ( is_dir($path_input . '/' . $name_file) )
            {
                if ( '' == $filter || preg_match('~' . $filter . '~si', $name_file) )
                {
                    if ( !is_dir($path_output . '/' . $name_file) )
                        mkdir($path_output . '/' . $name_file);
                    chmod($path_output . '/' . $name_file, 0755);
                    self::Folder_Copy($path_input . '/' . $name_file, $path_output . '/' . $name_file, $filter);
                }
            }
            else
            {
                copy($path_input . '/' . $name_file, $path_output . '/' . $name_file);
                chmod($path_output . '/' . $name_file, 0644);
            }
        }
        closedir($fp_folder);
        return true;
    }

    /**
     * Peremeshchenie soderzhimogo kataloga
     *
     * Esli fil`tr ne zadan peremeshchaiutsia vse katalogi.
     *
     * @param string $path_input - katalog istochnik
     * @param string $path_output - tcelevoi` katalog (sozdaet esli ego net)
     * @param string $filter - fil`tr imen katalogov
     * @return bool
     */
    public static function Folder_Move($path_input, $path_output, $filter = '')
    {
        if ( !is_dir($path_input) )
            return true;
        if ( !is_dir($path_output) )
            mkdir($path_output);
        chmod($path_output, 0755);
        $fp_folder = opendir($path_input);
        while ( false != $name_file = readdir($fp_folder) )
        {
            if ( '.' == $name_file || '..' == $name_file )
                continue;
            if ( is_dir($path_input . '/' . $name_file) )
            {
                if ( '' == $filter || preg_match('~' . $filter . '~si', $name_file) )
                {
                    mkdir($path_output . '/' . $name_file);
                    chmod($path_output . '/' . $name_file, 0755);
                    self::Folder_Move($path_input . '/' . $name_file, $path_output . '/' . $name_file, $filter);
                    //rmdir($path_input . '/' . $name_file);
                }
            }
            else
            {
                rename($path_input . '/' . $name_file, $path_output . '/' . $name_file);
                chmod($path_output . '/' . $name_file, 0644);
            }
        }
        closedir($fp_folder);
        rmdir($path_input);
        return true;
    }

    /**
     * Udalenie soderzhimogo kataloga vcliuchaia ego samogo
     *
     * Esli fil`tr ne zadan udaliaiutsia vse katalogi.
     *
     * @param string $path - tcelevoi` katalog
     * @param string $filter - fil`tr imen katalogov
     * @return bool
     */
    public static function Folder_Remove($path, $filter = '')
    {
        if ( !is_dir($path) )
            return true;
        $fp_folder = opendir($path);
        while ( false != $name_file = readdir($fp_folder) )
        {
            if ( '.' == $name_file || '..' == $name_file )
                continue;
            if ( is_dir($path . '/' . $name_file) )
            {
                if ( '' == $filter || preg_match('~' . $filter . '~si', $name_file) )
                    self::Folder_Remove($path . '/' . $name_file, $filter);
            }
            else
                unlink($path . '/' . $name_file);
        }
        closedir($fp_folder);
        rmdir($path);
        return true;
    }

    /**
     * Udalenie soderzhimogo kataloga i vsekh ego podkatalogov.
     *
     * Metod rekursivny`i`.
     * Fil`tr rabotaet v rezhime reguliarnogo vy`razheniia
     * Esli fil`tr ne zadan udaliaiutsia vse fai`ly` kataloga.
     * Katalogi ne udaliaiutsia.
     *
     * @param string $path tcelevoi` katalog
     * @param string $filter fil`tr imen udaliaemy`kh fai`lov (reguliarnoe vy`razhenie)
     * @return bool
     */

    public static function File_Remove($path, $filter = '')
    {
        if ( !is_dir($path) )
            return true;
        foreach (glob($path . '/*') as $path)
        {
            if ( is_dir($path) )
                self::File_Remove($path, $filter);
            else
            {
                if ( file_exists($path) && ('' == $filter || preg_match('~' . $filter . '~si', $path)) )
                    unlink($path);
            }
        }
        return true;
    }

    /**
     * Konvertatciia fai`lov iz odnoi` kodirovki v druguiu
     *
     * Rekursivno obhodit vse podkatalogi
     *
     * @param string $path - tcelevoi` katalog
     * @param string $k_in - tekushchaia kodirovka fai`la
     * @param string $k_ot - tcelevaia kodirovka fai`la
     */
    public static function File_Convert($path, $k_in, $k_ot)
    {
        foreach (glob($path . '/*', GLOB_ONLYDIR) as $path_file)
        {
            self::File_Convert($path_file, $k_in = "WINDOWS-1251", $k_ot = "UTF-8");
        }
        foreach (glob($path . '/*.*') as $path_file)
        {
            file_put_contents($path_file, iconv($k_in, $k_ot, file_get_contents($path_file)));
            //  exec("iconv --from-code={$k_in} --to-code={$k_ot} {$path_file} > {$path_file}");
        }
    }

    /**
     * Kopirovanie fai`la i sokhranenie ego v svoi`stve.
     * S realizatciei` obrabotki kartinki v protcesse kopirovaniia (povorot i resai`z)
     * Dlia resai`za i povorota kartinki: $resize['X'=>25, 'Y'=>25, 'R'=>-1(-90)|1(90)|2(180)]
     *
     * @param string $path_file Fai`l (put` do fai`la kotory`i` kopiruem)
     * @param string $Source Istochnik danny`kh
     * @param string $ID - Identifikator tcelevogo ob``ekta
     * @param array $resize (X => , Y => , R => )
     * @return string - chast` puti do tcelevogo fai`la (v ramkakh sistemy`)
     */
    public static function File_Copy($path_file, $Source, $ID, $resize = [])
    {
        $path = ZERO_PATH_DATA . '/' . strtolower($Source) . '/' . self::Get_Path_Cache($ID) . '/' . $ID;
        if ( !is_dir($path) )
            mkdir($path, 0777, true);
        //  korrektciia imeni fai`la i polny`i` put` do fai`la
        $path .= '/' . Zero_Lib_String::Transliteration_FileName(basename($path_file));
        //  resize
        $row = getimagesize($path_file);
        if ( 0 < count($resize) && 'image' == substr($row['mime'], 0, 5) )
        {
            settype($resize['X'], "integer");
            settype($resize['Y'], "integer");
            settype($resize['R'], "integer");
            if ( $resize['X'] || $resize['Y'] || $resize['R'] )
            {
                //  exec('convert -resize [100]x[200] '.$imgs['tmp_name'].' -> ../path/goods/path/'.$goods_id.'.'.$ext);
                self::Image_Resize($path_file, $path_file . 'resize', $resize['X'], $resize['Y'], $resize['R']);
                $path_file .= 'resize';
            }
        }
        copy($path_file, $path);
        return str_replace(ZERO_PATH_DATA . '/', '', $path);
    }

    /**
     * Save danny`kh v fai`l. Perezapis`
     *
     * @param string $path_file put` do fai`la
     * @param mixed $value danny`e dlia sokhraneniia v fai`l
     * @return bool
     */
    public static function File_Save($path_file, $value)
    {
        if ( !is_dir(dirname($path_file)) )
            mkdir(dirname($path_file), 0777, true);
        file_put_contents($path_file, trim($value));
        return true;
    }

    /**
     * Save danny`kh v fai`l. Dopisy`vanie v konetc fai`la
     *
     * @param string $path_file put` do fai`la
     * @param mixed $value danny`e dlia sokhraneniia v fai`l
     * @return bool
     */
    public static function File_Save_After($path_file, $value)
    {
        if ( !is_dir(dirname($path_file)) )
            mkdir(dirname($path_file), 0777, true);
        $fp = fopen($path_file, 'a');
        fputs($fp, trim($value) . "\n");
        fclose($fp);
        chmod($path_file, 0666);
        return true;
    }

    /**
     * Opredeleniia tipa dokumenta po rasshchireniiu dlia zagolovka.
     *
     * @param string $file_name - imia fai`la ili put` do nego
     * @return string - tip dokumenta dlia zagolovka
     */
    public static function File_Type($file_name)
    {
        $str = pathinfo($file_name, PATHINFO_EXTENSION);
        if ( $str == 'gif' )
            return 'image/gif';
        else if ( $str == 'jpg' || $str == 'jpeg' )
            return 'image/jpeg';
        else if ( $str == 'png' )
            return 'image/png';
        else if ( $str == 'txt' )
            return 'text/plain';
        else if ( $str == 'html' || $str == 'htm' || $str == 'xml' )
            return 'text/html';
        else if ( $str == 'doc' )
            return 'application/msword';
        else if ( $str == 'xls' )
            return 'application/vnd.ms-excel';
        else if ( $str == 'xlsx' )
            return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        else if ( $str == 'csv' )
            return 'application/vnd.ms-excel';
        else if ( $str == 'swf' )
            return 'application/x-shockwave-flash';
        else if ( $str == 'pdf' )
            return 'application/pdf';
        else if ( $str == 'ppt' )
            return 'application/vnd.ms-powerpoint';
        else if ( $str == 'pps' )
            return 'application/vnd.ms-powerpoint';
        else if ( $str == 'mdb' )
            return 'application/msaccess';
        else if ( $str == 'vsd' )
            return 'application/vnd.visio';
        else if ( $str == 'rar' )
            return 'application/x-tar';
        else if ( $str == 'zip' )
            return 'application/x-zip-compressed';
        else if ( $str == 'mp3' )
            return 'audio/wav';
        else if ( $str == 'wav' )
            return 'audio/mpeg';
        else if ( $str == 'wmv' )
            return 'video/x-ms-wmv';
        else if ( $str == 'mpg' )
            return 'video/mpeg';
        else if ( $str == 'avi' )
            return 'video/x-msvideo';
        else
            return 'application/octet-stream';
    }

    /**
     * Algoritm postroeniia raspolozheniia kesha.
     *
     * Postrenie optimizirovannoi` struktury` raspolozheniia kesha.<br>
     * Dlia by`strogo dosutpa v fai`lovoi` sisteme.<br>
     * Maksimal`noe znachenie kesh-indeksa bigint(22)<br>
     *
     * @param integer $id identifikator ob``ekta
     * @return string optimizirovanny`i` put` (chast` puti ot id)
     */
    public static function Get_Path_Cache($id)
    {
        if ( empty(self::$_Path_Cache[$id]) )
        {
            self::$_Path_Cache[-1] = [];
            self::$_Path_Cache[$id] = implode('/', self::_Get_Path_Cache($id));
        }
        return self::$_Path_Cache[$id];
    }

    /**
     * Algoritm postroeniia raspolozheniia kesha.
     *
     * Postrenie optimizirovannoi` struktury` raspolozheniia kesha.
     * Dlia by`strogo dosutpa v fai`lovoi` sisteme.
     * Maksimal`noe znachenie kesh-indeksa bigint(20) + 1
     *
     * @param integer $id - identifikator ob``ekta
     * @param integer $depth - glubina prohoda ili vlozhennosti katalogov
     * @param integer $count - verkhniaia granitca kesh-indeksa (kratnoe 100)
     */
    private static function _Get_Path_Cache($id, $depth = 1, $count = 100000000000000000000)
    {
        $step = intval($id / $count);
        if ( $step < 1 )
        {
            self::$_Path_Cache[-1][$depth] = 100;
            if ( 100 != $count )
                return self::_Get_Path_Cache($id, $depth + 1, $count / 100);
        }
        else
        {
            self::$_Path_Cache[-1][$depth] = 100 + $step;
            if ( 100 != $count )
                return self::_Get_Path_Cache($id % $count, $depth + 1, $count / 100);
        }
        return self::$_Path_Cache[-1];
    }
}