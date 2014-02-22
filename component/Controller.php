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
        foreach (self::$_Message as $key => $row)
        {
            if ( 1 == count($row) )
            {
//                $index = 'controller ' . get_class($this) . ' message ' . $key;
                $index = 'controller message ' . $key;
                self::$_Message[$key][] = Zero_I18n::T(get_class($this), $index, $key);
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
        return $code ? false : true ;
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

    /**
     * @param $method
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $params)
    {
        if ( !method_exists($this, $method) ) {
            throw new Exception('Метод контроллера остуствует в классе: ' .get_class($this) . '->' . $method, 500);
        }
        switch ( count($params) ) {
            case 0: {
                return $this->$method();
            }
            case 1: {
                return $this->$method($params[0]);
            }
            case 2: {
                return $this->$method($params[0], $params[1]);
            }
            case 3: {
                return $this->$method($params[0], $params[1], $params[2]);
            }
        }
        return $this->View;
    }



}
