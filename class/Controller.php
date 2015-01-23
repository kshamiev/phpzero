<?php

/**
 * Component. Abstract base controller.
 *
 * Rabota kontrollerov realizovana s pomoshch`iu chankov.
 * Chankami mozhno upravliat`. Ikh mozhno pereopredeliat`.
 * Vy`polnenie dei`stvii` s uchetom prav
 * Mehanizm soobshchenii` o rezul`tatakh dei`stvii`
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
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
    public static function Make($class_name, $properties = [])
    {
        if ( '' == $class_name )
            throw new Exception('Имя класса создаваемого контроллера не указано', 500);
        if ( false == Zero_App::Autoload($class_name) )
            throw new Exception('Контроллер "' . $class_name . '" отсутсвует в приложении', 500);
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
    public static function Factory($class_name, $properties = [])
    {
        if ( !$result = Zero_Session::Get($class_name) )
        {
            $result = self::Make($class_name, $properties);
            Zero_Session::Set($class_name, $result);
        }
        return $result;
    }

    /**
     * Poluchenie massiva soobshchenii` o rezul`tate dei`stvii` pol`zovatelia.
     *
     * S uchetom perevoda
     *
     * @return array soobshcheniia
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
     */
    public function Set_Message($message = '', $code = 1)
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
        }
        return $code ? false : true;
    }

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View or string
     */
    public function Action_Default()
    {
        $this->View = 'Controller -> ' . get_class($this);
        return $this->View;
    }
}
