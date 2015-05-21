<?php

/**
 * Abstract base controller.
 *
 * Rabota kontrollerov realizovana s pomoshch`iu chankov.
 * Chankami mozhno upravliat`. Ikh mozhno pereopredeliat`.
 * Vy`polnenie dei`stvii` s uchetom prav
 * Mehanizm soobshchenii` o rezul`tatakh dei`stvii`
 *
 * @package General.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
abstract class Zero_Controller
{

    /**
     * Massiv soobshchenii` sistemy`
     *
     * @var array
     */
    private static $_Message = [];

    /**
     * Obrabaty`vaemaia model` (ob``ekt)
     *
     * @var Zero_Model
     */
    protected $Model = null;

    /**
     * Predstavlenie
     *
     * @var Zero_View
     */
    protected $View = null;

    /**
     * Sluzhebny`i` massiv dlia khraneniia i dostupa k razlichnoi` sluzhebnoi` informatcii.
     *
     * Osobenno udobno pri khranenii kontrollera v sessii
     *
     * @var array
     */
    protected $Params = [];

    /**
     * Фабрика по созданию контроллеров.
     *
     * @param string $class_name имиа контроллера эекземплиар которого создаетсиа
     * @param array $properties входные параметры плагина
     * @return Zero_Controller
     * @throws Exception
     */
    public static function Makes($class_name, $properties = [])
    {
        if ( '' == $class_name )
            throw new Exception('Имя класса создаваемого контроллера не указано', -1);
        if ( false == Zero_App::Autoload($class_name) )
            throw new Exception('Контроллер "' . $class_name . '" отсутсвует в приложении', -1);
        $Controller = new $class_name();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }

    /**
     * Fabrika po sozdaniiu kontrollerov.
     *
     * Rabotaet cherez sessiiu. Indeks: $class_name
     *
     * @param string $class_name imia kontrollera e`ekzempliar kotorogo sozdaetsia
     * @param array $properties vhodny`e parametry` plagina
     * @return Zero_Controller
     */
    public static function Factories($class_name, $properties = [])
    {
        if ( !$result = Zero_Session::Get($class_name) )
        {
            $result = self::Makes($class_name, $properties);
            Zero_Session::Set($class_name, $result);
        }
        return $result;
    }

    public function SetMessage($code = 0, $params = [])
    {
        $arr = Zero_I18n::Message(get_class($this), $code, $params);
        if ( -1 == $code || 5000 <= $code )
            $errorStatus = true;
        else
            $errorStatus = false;

        self::$_Message = [
            'Code' => $arr[0],
            'Message' => $arr[1],
            'ErrorStatus' => $errorStatus,
        ];
    }

    public function GetMessage()
    {
        if ( count(self::$_Message) == 0 )
            $this->SetMessage();
        return self::$_Message;
    }

    /**
     * Poluchenie massiva soobshchenii` o rezul`tate dei`stvii` pol`zovatelia.
     *
     * S uchetom perevoda
     *
     * @return array soobshcheniia
     * @deprecated
     */
    public function Get_Message()
    {
        if ( count(self::$_Message) == 0 )
            $this->Set_Message();
        return self::$_Message;
    }

    /**
     * Dobavlenie soobshchenii` o rezul`tate dei`stvii` pol`zovatelia.
     *
     * @param string $message soobshchenie
     * @param int $code kod soobshcheniia
     * @return int
     * @deprecated
     */
    public function Set_Message($message = '', $code = 0)
    {
        self::$_Message = [
            'Code' => $code,
            'Message' => Zero_I18n::Controller(get_class($this), $message),
        ];

        $arr = func_get_args();
        switch ( count($arr) )
        {
            case 3:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2]);
                break;
            case 4:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3]);
                break;
            case 5:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4]);
                break;
            case 6:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4], $arr[5]);
                break;
            case 7:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6]);
                break;
        }
        return $code ? false : true;
    }

    /**
     * Контроллер по умолчанию
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        return $this->View;
    }
}
