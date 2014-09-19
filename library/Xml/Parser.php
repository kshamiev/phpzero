<?php
/* vim: set expandtab tabstop=2 shiftwidth=2 softtabstop=2 foldmethod=marker: */
/**
 * @package Service
 */

/**
 * Класс реализующий работу с XML файлами.
 *
 * <lo>
 * <li>Парсинг XML.
 * <li>Создание XML.
 * </lo>
 *
 * @package Service
 * @subpackage XML
 * @author Konstantin Shamiev aka marko-polo <konstanta75@mail.ru>
 * @version 16.03.2010
 */
class Zero_Xml_Parser
{
    /**
     * Дескриптор выходного файла
     *
     * @var resource
     */
    private $_Fp;

    /**
     * Версия Xml
     *
     * @var string
     */
    private $_Version = '1.0';

    /**
     * Кодировка Xml данных
     *
     * @var string
     */
    private $_Encoding = 'utf-8';

    /**
     * Уровень вложенности ноды
     *
     * @var integer
     */
    private $_Level = 0;

    /**
     * Класс обработчик парсируемых xml нод
     *
     * @var object
     */
    private $_Handler;

    /**
     * Xml документ в виде древовидного объекта
     *
     * @var []Zero_Xml_Object
     */
    private $_Xml = [];

    /**
     * Конструткор
     *
     * @param string $encoding - кодировка xml
     * @param string $version - версия xml
     */
    public function __construct($encoding = 'utf-8', $version = '1.0')
    {
        $this->_Encoding = $encoding;
        $this->_Version = $version;
        $this->_Level = 0;
        $this->_Xml[$this->_Level] = new Zero_Xml_Object('root');
    }

    /**
     * Возвращает рабочий Zero_Xml_Object
     *
     * @return Zero_Xml_Object
     */
    public function Get_Xml()
    {
        return $this->_Xml[0];
    }

    /**
     * Устанавливает ранее обработанный Zero_Xml_Object
     *
     * @param Zero_Xml_Object $xml
     */
    public function Set_Xml(Zero_Xml_Object $xml)
    {
        $this->_Xml[0] = $xml;
    }

    /**
     * Стартовый обработчик очередной XML ноды
     *
     * @param resource $fp_xml - дескриптор XML файла
     * @param string $name - Имя тега
     * @param array $attrs - Массив атрибутов
     */
    private function _Node_Beg($fp_xml, $name, $attrs)
    {
        $this->_Level++;
        //  имя тега
        $this->_Xml[$this->_Level] = new Zero_Xml_Object($name);
        //  атрибуты
        foreach ($attrs as $a => $v)
        {
            $this->_Xml[$this->_Level]->Set_Attribute($a, $v);
        }
    }

    /**
     * Обработчик содержимого очередной XML ноды
     *
     * @param resource $fp_xml
     * @param string $value
     */
    private function _Node_Value($fp_xml, $value)
    {
        //  значение (данные) тега
        if ( '' != $value = trim($value) )
        {
            $this->_Xml[$this->_Level]->Set_Data($value);
        }
    }

    /**
     * Конечный обработчик очередной XML ноды
     *
     * @param resource $fp_xml
     * @param string $name
     */
    private function _Node_End($fp_xml, $name)
    {
        //  обработка ноды
        if ( method_exists($this->_Handler, $method = strtolower(str_replace('-', '_', $name))) )
        {
            $this->_Handler->$method($this->_Xml[$this->_Level]);
        }
        else
        {
            $this->_Xml[($this->_Level - 1)]->Node_Add($this->_Xml[$this->_Level]);
        }
        unset($this->_Xml[$this->_Level]);
        $this->_Level--;
    }

    /**
     * Парсер XML
     * Разбор XML файла и загрузка его в Xml объект (класс Zero_Xml_Object) свойства $this->_Xml
     * Пропускает парсинг через класс обработчик нод
     * Создавая для этого объект $class_handler в свойстве $this->_Handler.
     *
     * @param string $file - путь до xml файла
     * @param string $class_handler обработчик нод (по умолчанию класс Zero_Xml_Handler, для примера)
     * @return Zero_Xml_Object
     */
    public function Parser($file, $class_handler = 'Zero_Xml_Handler')
    {
        $this->_Level = 0;
        $this->_Xml[$this->_Level] = new Zero_Xml_Object('root');
        $this->_Handler = new $class_handler();
        //
        $fp_xml = xml_parser_create($this->_Encoding);
        //  отключить перевод тегов в верхний регистр
        xml_parser_set_option($fp_xml, XML_OPTION_CASE_FOLDING, 0);
        //  допускаются наличие пробелов в значениях
        xml_parser_set_option($fp_xml, XML_OPTION_SKIP_WHITE, 1);
        //  обработчики являются методами класса
        xml_set_object($fp_xml, $this);
        //  обработчики
        xml_set_element_handler($fp_xml, "_Node_Beg", "_Node_End");
        xml_set_character_data_handler($fp_xml, '_Node_Value');
        //
        $fp = fopen($file, "r");
        while ( false != $data = fread($fp, 65000) ) //  65536
        {
            if ( !xml_parse($fp_xml, $data, feof($fp)) )
            {
                die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($fp_xml)), xml_get_current_line_number($fp_xml)));
            }
        }
        xml_parser_free($fp_xml);
        $this->_Handler = null;
        return $this->Get_Xml();
    }

    /**
     * Сохранение xml данных в файл
     *
     * @param $file string абсолютный путь до файла
     */
    public function Save($file)
    {
        $this->_Fp = fopen($file, 'w');
        fputs($this->_Fp, '<?xml version="' . $this->_Version . '" encoding="' . $this->_Encoding . '"?>' . "\n");
        $this->_Save($this->_Xml[0]);
        fclose($this->_Fp);
        //  chmod($file_token, 0666);
    }

    private function _Save(Zero_Xml_Object $Zero_Xml_Object, $depth = 0)
    {
        foreach ($Zero_Xml_Object->Get_NodeTags() as $xml_list)
        {
            foreach ($xml_list as $Xml)
            {
                $string = '';
                /* @var $Xml Zero_Xml_Object */
                //  имя тега
                $string .= '<' . $Xml->Get_Name();
                //  атрибуты
                foreach ($Xml->Get_Attributes() as $a => $v)
                {
                    $string .= ' ' . $a . '="' . self::String_Xml($v, $this->_Encoding) . '"';
                }
                //  значение ноды и запись в файл
                if ( false != $v = $Xml->Get_Data() )
                {
                    //  конечная нода с содержимым
                    $string = $string . '><![CDATA[' . self::String_Xml($v, $this->_Encoding) . ']]></' . $Xml->Get_Name() . '>';
                    fputs($this->_Fp, str_repeat("\t", $depth) . $string . "\n");
                }
                else if ( 0 == count($Xml->Get_NodeTags()) )
                {
                    //  конечная нода без содержимого
                    $string = $string . '/>';
                    fputs($this->_Fp, str_repeat("\t", $depth) . $string . "\n");
                }
                else
                {
                    //  родительская нода
                    $string = $string . '>';
                    fputs($this->_Fp, str_repeat("\t", $depth) . $string . "\n");
                    $this->_Save($Xml, $depth + 1);
                    fputs($this->_Fp, str_repeat("\t", $depth) . '</' . $Xml->Get_Name() . '>' . "\n");
                }
            }
        }
    }

    //  преобразование строки в xml формат
    public static function String_Xml($str, $encoding = 'utf-8')
    {
        $str = html_entity_decode($str, ENT_QUOTES, $encoding);
        //  $str = htmlentities($str, ENT_QUOTES);
        $str = htmlspecialchars($str, ENT_QUOTES, $encoding);
        return $str;
    }

    //  преобразование строки в xml формат
    public static function String_Xml_NotHtml($str, $encoding = 'utf-8')
    {
        $str = html_entity_decode($str, $encoding);
        $str = strip_tags($str);
        return $str;
    }

    //  преобразование строки в xml формат
    public static function String_Xml_Old($str)
    {
        $str = str_replace('&', '&amp;', $str);
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace("'", '&apos;', $str);
        $str = str_replace('>', '&gt;', $str);
        $str = str_replace('<', '&lt;', $str);
        return $str;
    }
}
