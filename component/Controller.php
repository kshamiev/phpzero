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
     * Stek chankov
     *
     * Skisok vy`polniaiushchii`khsia chankov.
     * Zapolniaetsia v Chunk_Init
     *
     * @var array
     */
    private static $_Chunks = [];

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
     * @return Zero_Controller
     */
    public static function Factory($class_name)
    {
        if ( !$result = Zero_Session::Get($class_name) )
        {
            $result = self::Make($class_name);
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
                $index = 'controller ' . get_class($this) . ' message ' . $key;
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
        return $code;
    }

    /**
     * Dobavlenie chanka v konetc steka vy`polneniia
     *
     * @param string $chunk imia dobavliaemogo chanka
     */
    final protected function Set_Chunk($chunk)
    {
        if ( in_array($chunk, self::$_Chunks) )
            return;
        self::$_Chunks[] = $chunk;
    }

    /**
     * Udalenie chanka iz steka vy`polneniia
     *
     * Esli imia chanka ne zadano, to udaliaiutsia vse chanki
     *
     * @param string $chunk imia udaliaemogo chanka iz steka
     */
    final protected function Rem_Chunk($chunk)
    {
        $key = array_search($chunk, self::$_Chunks);
        if ( false !== $key )
            unset(self::$_Chunks[$key]);
    }

    /**
     * Upravliaiushchii` metod. Tochka vhoda.
     *
     * Initcializiruet i vy`polniaet chanki v zavismosti ot dei`stviia ($action)
     * I vozvrashchaet rezul`tat raboty`
     *
     * @param string $action action
     * @return Zero_View|string rezul`tat (kak pravilo shablon s danny`mi)
     * @TODO Переработать. Метод - действие контроллера по умолчанию. Action_Default
     * @TODO Chunk_Init -> Action...
     */
    final public function Execute($action)
    {
        self::$_Chunks = [];
        Zero_Logs::Start('#{CONTROLLER.Action} ' . $action);
        $flag = $this->$action();
        Zero_Logs::Stop('#{CONTROLLER.Action} ' . $action);
        if ( true == $flag )
        {
            foreach (self::$_Chunks as $chunk)
            {
                $chunk = 'Chunk_' . $chunk;
                Zero_Logs::Start('#{CONTROLLER.Chunk} ' . $chunk);
                $flag = $this->$chunk($action);
                Zero_Logs::Stop('#{CONTROLLER.Chunk} ' . $chunk);
                if ( false == $flag )
                    break;
            }
        }
        return $this->View;
    }

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return boolean flag stop execute of the next chunk
     */
    protected function Action_Default()
    {
        $this->View = new Zero_View(get_class($this));
    }
}
