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
     * Fabrika po sozdaniiu kontrollerov.
     *
     * @param string $class_name imia kontrollera e`ekzempliar kotorogo sozdaetsia
     * @param array $properties vhodny`e parametry` plagina
     * @return Zero_Controller
     * @throws Exception
     */
    public static function Make($class_name, $properties = [])
    {
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
        foreach (self::$_Message as $message => $row)
        {
            if ( 1 == count($row) )
            {
                self::$_Message[$message][] = Zero_I18n::Controller(get_class($this), $message);
            }
        }
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
        if ( !$message && !$code )
            self::$_Message = [];
        else
            self::$_Message[$message] = [$code];
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
