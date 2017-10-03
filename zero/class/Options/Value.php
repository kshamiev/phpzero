<?php

/**
 * Значения опций приложения
 *
 * @package <Package>.Options
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2016.09.27
 */
class Zero_Options_Value
{
    /**
     * Массив содержащий значения опций
     *
     * @var array
     */
    private $options = [];

    /**
     * Конструктор
     *
     * @param bool $IsDB загружать ли опции из БД
     */
    public function __construct($IsDB = false)
    {
        if ( $IsDB )
        {
            foreach (Zero_DB::Select_Array("SELECT * FROM Options") as $row)
            {
                switch ( $row['Typ'] )
                {
                    case 'string':
                        $this->options[$row['Name']] = strval($row['Value']);
                        break;
                    case 'int':
                        $this->options[$row['Name']] = intval($row['Value']);
                        break;
                    case 'float':
                        $this->options[$row['Name']] = floatval($row['Value']);
                        break;
                    case 'array':
                        $this->options[$row['Name']] = unserialize($row['Value']);
                        break;
                }
            }
        }
    }

    /**
     * Геттер свойств
     *
     * @param $prop
     * @return mixed|null
     */
    public function __get($prop)
    {
        if ( isset($this->options[$prop]) )
        {
            return $this->options[$prop];
        }
        else
        {
            Zero_Logs::Set_Message_Error("обращение к несуществующей опции: {$prop}");
            return null;
        }
    }
}