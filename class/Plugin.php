<?php

/**
 * Component. Bazovy`i` abstraktny`i` plagin.
 *
 * Rabota plaginov realizovana s pomoshch`iu chankov.
 * Chankami mozhno upravliat`. Ikh mozhno pereopredeliat`.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
abstract class Zero_Plugin
{
    /**
     * Predstavlenie
     *
     * @var Zero_View
     */
    protected $View = null;

    /**
     * Sluzhebny`i` massiv dlia khraneniia i dostupa k razlichnoi` sluzhebnoi` informatcii.
     *
     * @var array
     */
    protected $Params = [];

    /**
     * Stek chankov
     *
     * Skisok vy`polniaiushchii`khsia chankov.
     * Zapolniaetsia v Init_Chunks
     *
     * @var array
     */
    private static $_Chunks = [];

    /**
     * Fabrika po sozdaniiu plaginov.
     *
     * @param string $class_name imia plagina e`ekzempliar kotorogo sozdaetsia
     * @param array $properties vhodny`e parametry` plagina
     * @return Zero_Controller
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
     * Dobavlenie chanka v stek vy`polneniia pered $before
     *
     * @param string $chunk imia dobavliaemogo chanka
     * @param string $before imia chanka pered kotory`m dobavliaetsia $chunk
     */
    final protected function Set_Chunk_Before($chunk, $before)
    {
        if ( in_array($chunk, self::$_Chunks) )
            return;
        $key = array_search($before, self::$_Chunks);
        if ( false !== $key )
            array_splice(self::$_Chunks, $key, 0, $chunk);
    }

    /**
     * Dobavlenie chanka v stek vy`polneniia posle $after
     *
     * @param string $chunk imia dobavliaemogo chanka
     * @param string $after imia chanka posle kotorogo dobavliaetsia $chunk
     */
    final protected function Set_Chunk_After($chunk, $after)
    {
        if ( in_array($chunk, self::$_Chunks) )
            return;
        $key = array_search($after, self::$_Chunks);
        if ( false !== $key )
            array_splice(self::$_Chunks, $key + 1, 0, $chunk);
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
     * @return Zero_View|string rezul`tat (kak pravilo shablon s danny`mi)
     */
    final public function Execute()
    {
        self::$_Chunks = [];
        $this->Init_Chunks();
        foreach (self::$_Chunks as $chunk)
        {
            $chunk = 'Chunk_' . $chunk;

            Zero_Logs::Start('#{PLUGIN.Chunk} ' . get_class($this) . ' ' . $chunk);
            $flag = $this->$chunk();
            Zero_Logs::Stop('#{PLUGIN.Chunk} ' . get_class($this) . ' ' . $chunk);

            if ( false === $flag )
                break;
        }
        return $this->View;
    }

    /**
     * Initialize the stack chunks.
     *
     * sample:
     * $this->Set_Chunk('View');
     */
    abstract protected function Init_Chunks();
}
