<?php

/**
 * Component. Validatciia svoi`stv ob``ekta.
 *
 * Realizuet validatciiu svoi`stv ob``ekta pered ego sokhraneniem.
 * Soderzhit v sebe standartny`e metody` validatcii i upravliaiushchii` metod validatcii.
 * Proizvodny`e ili personal`ny`e validatory` raspolagaiutsia v modeli ob``ekta.
 * Po nei` zhe i proizvoditsia ikh poisk v upravliaiushchem metode validatcii.
 * - Poisk validatora v modeli po imeni svoi`stva
 * - Poisk validatora zdes` v validatore po forme predstavleniia svoi`stva
 * Esli ni odin validator ne by`l nai`den proishodit bezuslovnoe prisvoenie znachenie svoi`stvu ob``etu.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Validator
{
    /**
     * Shablony` validatcii
     */
    const PATTERN_EMAIL = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

    /**
     * Massiv soobshchenii` ob oshibkakh validatcii
     *
     * @var array
     */
    protected $Errors = [];

    /**
     * Ob``ekt, s kotory`m my` rabotaem
     *
     * @var Zero_Model
     */
    protected $Model;

    /**
     * Initcializatciia validatora.
     *
     * @param Zero_Model $Model Delegirovanny`i` ob``ekt s kotory`m my` rabotaem
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    /**
     * Poluchenie massiva soobshchenii` o rezul`tate validatcii.
     *
     * S uchetom perevoda
     *
     * @return array soobshcheniia
     */
    public function Get_Errors()
    {
        foreach ($this->Errors as $prop => $row)
        {
            if ( 1 == count($row) )
            {
                $this->Errors[$prop][] = Zero_I18n::Model(get_class($this->Model), $row[0]);
            }
        }
        return $this->Errors;
    }

    /**
     * Dobavlenie soobshchenii` ob oshibkakh validatcii.
     *
     * @param string $prop svoi`stvo
     * @param string $subj soobshchenie
     */
    public function Set_Errors($prop, $subj)
    {
        $this->Errors[$prop] = [$subj];
    }

    /**
     * Chisla
     *
     * @param mixed $value value to check
     * @param string $prop validiruemoe svoi`stvo
     * @return string
     */
    protected function VL_Checkbox($value, $prop)
    {
        if ( !is_array($value) )
            $value = [];
        $this->Model->$prop = $value;
    }

    /**
     * Validatciia binarny`kh danny`kh (kartinki) v ramkakh fai`lovoi` sistemy`.
     *
     * S realizatciei` obrabotki kartinki v protcesse sokhraneniia (povorot i resai`z)
     * Dlia resai`za i povorota kartinki: $resize['X'=>25, 'Y'=>25, 'R'=>-1(-90)|1(90)|2(180)]
     *
     * @param mixed $value value to check
     * @param string $prop validiruemoe svoi`stvo
     * @return string
     */
    protected function VL_Img($value, $prop)
    {
        return $this->VL_File($value, $prop);
    }

    /**
     * Validatciia binarny`kh danny`kh v ramkakh fai`lovoi` sistemy`.
     * [ImgAvatar] => [
     *      [name] => ififnfpf.jpg,
     *      [type] => image/jpeg,
     *      [tmp_name] => /tmp/php52y5Rf,
     *      [error] => 0, [size] => 218222
     *  ]
     *
     * @param mixed $value value to check
     * @param string $prop validiruemoe svoi`stvo
     * @return string
     */
    protected function VL_File($value, $prop)
    {
        //  udalenie starogo fai`la
        if ( isset($value['Rem']) && $value['Rem'] && $this->Model->$prop )
        {
            if ( file_exists($filename = ZERO_PATH_DATA . '/' . $this->Model->$prop) )
                unlink($filename);
            $this->Model->$prop = '';
        }

        if ( (Zero_App::$Mode == 'json' || Zero_App::$Mode == 'api') && isset($value['Hash']) && $value['Hash'] )
        {
            $pathInfo = dirname(ZERO_PATH_DATA) . '/temp/' . $value['Hash'] . '.txt';
            if ( !file_exists($pathInfo) )
                return 'Ошибка загрузки файла (информация)';
            $_FILES[$prop] = json_decode(file_get_contents($pathInfo), true);
            //            $pathData = dirname(ZERO_PATH_DATA) . '/temp/' . $_FILES[$prop]['tmp_name'];
            if ( !file_exists($_FILES[$prop]['tmp_name']) )
                return 'Ошибка загрузки файла (данные)';
        }

        //  Validatciia
        if ( isset($_FILES[$prop]) && 4 != $_FILES[$prop]['error'] )
        {
            //  fai`l ne zagruzhen ili zagruzhen s oshibkami
            //            if ( !is_uploaded_file($_FILES[$prop]['tmp_name']) || 0 != $_FILES[$prop]['error'] )
            if ( 0 != $_FILES[$prop]['error'] )
            {
                Zero_Logs::Set_Message_Error("{$this->Model->Source} - {$this->Model->ID} - {$_FILES[$prop]['error']} - Error Upload File");
                return 'Error Upload File';
            }
            //  resize kartinki
            if ( 'image' == substr($_FILES[$prop]['type'], 0, 5) )
            {
                settype($value['X'], "integer");
                settype($value['Y'], "integer");
                settype($value['R'], "integer");
                if ( $value['X'] || $value['Y'] || $value['R'] )
                {
                    //  exec('convert -resize [100]x[200] '.$imgs['tmp_name'].' -> ../path/goods/path/'.$goods_id.'.'.$ext);
                    if ( false == Zero_Lib_FileSystem::Image_Resize($_FILES[$prop]['tmp_name'], $_FILES[$prop]['tmp_name'] . 'resize', $value['X'], $value['Y'], $value['R']) )
                    {
                        Zero_Logs::Set_Message_Error("{$this->Model->Source} - {$this->Model->ID} - {$_FILES[$prop]['error']} - Error Image Resize");
                        return 'Error Image Resize';
                    }
                    $_FILES[$prop]['tmp_name'] .= 'resize';
                }
            }
            $_FILES[$prop]['name'] = Zero_Lib_String::Transliteration_FileName($_FILES[$prop]['name']);
            $this->Model->$prop = 'File Upload Ok';
        }
        return '';
    }

    /**
     * Validatciia vhodny`kh danny`kh dlia posleduiushchego izmeneniia ob``ekta.
     *
     * Validatciia proishodit po svoi`stvam ob``ekta ishodia iz peredanny`kh danny`kh
     * Imena validatorov svoi`stv nachinaiutsia s prefiksa 'Validator_'
     * Poriadok poiska metoda validatcii:
     * - Poisk metoda validatcii po imeni svoi`stva (Validator_PropName)
     * - Poisk metoda validatcii po forme predstavleniia (Validator_FormName)
     * Posle validatcii:
     * - $this->Errors budet soderazhat` soobshcheniia ob oshibkakh dlia svoi`stv ne proshedshikh proverku
     *
     * @param array $data Massiv vhodiashchikh danny`kh kotory`e neobhodimo proverit` ( $_POST['Prop'] )
     * @param string $scenario Scenarii` validatcii svoi`stv (forma iz ktoroi` prishli danny`e).
     * @return boolean rezul`tat proverki svoi`stv (true vse shorosho, false vse ploho)
     */
    public function Validate($data, $scenario = '')
    {
        $this->Errors = [];
        //  Obshchaia nachal`naia validatciia
        if ( method_exists($this->Model, $method = 'Validate_Before') )
            $data = $this->Model->Validate_Before($data, $scenario);

        $props = $this->Model->Get_Config_Form($scenario);

        foreach ($data as $prop => $value)
        {
            if ( isset($props[$prop]) && 'ReadOnly' == $props[$prop]['Form'] )
                continue;

            //  initcializatciia znacheniia ili pervichnach obrabotka
            if ( is_scalar($value) )
            {
                $value = trim($value);
                if ( 0 == strlen($value) )
                    $value = null;
            }

            // validatciia
            $subj = '';
            if ( isset($props[$prop]) && 'NO' == $props[$prop]['IsNull'] && !$value )
                $subj = 'Error_NotNull';
            else if ( method_exists($this->Model, $method = 'VL_' . $prop) )
                $subj = $this->Model->$method($value, $scenario);
            else if ( method_exists($this, $method = 'VL_' . $props[$prop]['Form']) )
                $subj = $this->$method($value, $prop);
            else
                $this->Model->$prop = $value;

            // oshibki validatcii
            if ( $subj )
                $this->Set_Errors($prop, $subj);
        }

        //  Obshchaia zavershaiushchaia validatciia
        if ( method_exists($this->Model, $method = 'Validate_After') )
            $this->Model->Validate_After($data, $scenario);

        //    zavershenie
        if ( 0 < count($this->Errors) )
            return false;
        else
            return true;
    }
}