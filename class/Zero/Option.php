<?php

/**
 * Значения опций приложения
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2017-10-16
 */
class Zero_Option
{
    /**
     * Массив содержащий значения опций
     *
     * @var array
     */
    private $options = null;

    /**
     * Инициализация
     */
    private function init()
    {
        if ( Zero_App::$Config->Site_UseDB )
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
        if ( is_null($this->options) )
            $this->init();

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