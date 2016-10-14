<?php
/**
 * The absolute http path to the project (site)
 */
define('ZERO_HTTP', Zero_App::$Config->Site_Protocol . '://' . Zero_App::$Config->Site_DomainAlias);
define('HTTP', ZERO_HTTP);
/**
 * http location of static data
 */
define('ZERO_HTTPA', Zero_App::$Config->Site_Protocol . '://' . Zero_App::$Config->Site_DomainAssets . '/assets');
define('HTTPA', ZERO_HTTPA);
/**
 * http location of binary data
 */
define('ZERO_HTTPD', Zero_App::$Config->Site_Protocol . '://' . Zero_App::$Config->Site_DomainUpload . '/upload/data');
define('HTTPD', ZERO_HTTPD);
/**
 * http location of history
 */
define('ZERO_HTTPH', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ZERO_HTTP);
define('HTTPH', ZERO_HTTPH);

/**
 * The relative url path to the project (site)
 */
define('ZERO_URL', Zero_App::$Route);
define('URL', Zero_App::$Route);
/**
 * The language suffix
 */
define('ZERO_LANG', Zero_App::$Config->Site_Language);
define('LANG', Zero_App::$Config->Site_Language);

/**
 * Representation.
 *
 * Implements a programmatic interaction of (business logic) with the presentation of data (patterns).
 * Collects and encapsulates data within the template.
 * Parsing templates.
 * Gathers ready-made template to the transferred data (as it executes a program.)
 * The mechanism of the multi-language templates
 *
 * @package Zero.Component Шаблонизатор
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_View
{
    /**
     * Rasshirenie fai`lov predstavleniia (shablonov)
     */
    const EXT_VIEW = '.html';
    /**
     * Reguliarnoe vy`razhenie dlia obrabotki direktiv include (html shablonov)
     */
    const PATTERN_INCLUDE = '~\{inc(?:lude)?[ ]+[\'"]+([.\w\d\/_]+)[\'"]+\}~si';
    /**
     * Reguliarnoe vy`razhenie dlia obrabotki direktiv plugin
     */
    const PATTERN_PLUGIN = '~\{plugin[ ]+[\'"]+([\w\d_]+)[\'"]+(\s+[^\{\}]+)?\}~si';
    /**
     * Reguliarnoe vy`razhenie dlia obrabotki direktiv translation
     */
    const PATTERN_TRANSLATION = '~\{(?:translation|lang)[ ]+[\'"]+([\w\d_]+)[\'"]+[ ]+[\'"]+([^"\']+)[\'"]+\}~si';

    /**
     * Danny`e vstavliaemy`e v shablon
     *
     * @var array
     */
    private $_Data = [];

    /**
     * Shablon(y`) predstvaleniia
     *
     * @var array
     */
    private $_Template = [];

    /**
     * Initcializatciia ob``ekta predstavleniia
     *
     * @param string $template imia shablona (kak pravilo imia kontrollera[ + suffiks])
     */
    public function __construct($template = '')
    {
        if ( '' != $template )
            $this->_Template[$template] = $template;
    }

    /**
     * Dobavlenie shablona predstavleniia v stek
     *
     * @param string $template imia shablona (kak pravilo imia kontrollera[ + suffiks])
     */
    public function Add($template)
    {
        $this->_Template[$template] = $template;
    }

    /**
     * Udalenie shablona predstavleniia iz steka
     *
     * Esli ne ukazan proishodit polnaia ochistka steka
     *
     * @param string $template imia shablona (kak pravilo imia kontrollera[ + suffiks])
     */
    public function Delete($template = '')
    {
        if ( '' != $template )
            unset($this->_Template[$template]);
        else
            $this->_Template = [];
    }

    /**
     * Dobavlenie danny`kh v shablon.
     *
     * Dlia posleduiushchego vy`voda v shablon.
     *
     * @param string $variable peremennaia shablona
     * @param mixed $value ee znachenie
     */
    public function Assign($variable, $value)
    {
        $this->_Data[$variable] = $value;
    }

    /**
     * Udalenie danny`kh iz shablona. I sootvetsvenno iz posleduiushchego vy`voda v shablon.
     *
     * Esli $variable ne ukazan proishodit polny`i` sbros peredanny`kh danny`kh
     *
     * @param string $variable peremennaia shablona
     */
    //    public function Remove($variable = '')
    //    {
    //        if ( $variable )
    //            unset($this->_Data[$variable]);
    //        else
    //            $this->_Data = [];
    //    }

    /**
     * Poluchenie peremennoi` shablona
     *
     * Esli $variable ne ukazan vozvrashchaetsia ves` massiv danny`kh peredanny`kh v shablon
     *
     * @param string $variable imia peremennoi` shablona
     * @return mixed|null
     */
    public function Receive($variable = '')
    {
        if ( isset($this->_Data[$variable]) )
            return $this->_Data[$variable];
        else if ( '' == $variable )
            return $this->_Data;
        else
            return null;
    }

    /**
     * Poluchenie predstavleniia s danny`mi.
     *
     * - Poisk shablona presdtavleniia
     * - Kompiliatciia html shablona v tpl
     * - E`ksport daneny`kh
     * - Vy`polnenie shablona i vozvrat rezul`tata
     *
     * @return string sobranny`i` shablon so vstavlenny`mi danny`mi
     */
    public function Fetch()
    {
        if ( 0 == count($this->_Template) )
            return '';

        $arr = [];
        foreach ($this->_Template as $template)
        {
            $arr = self::Search_Template($template);
            if ( 0 < count($arr) )
            {
                if ( true == Zero_App::$Config->Site_TemplateParsing || !file_exists($arr[1]) )
                    Helper_File::File_Save($arr[1], $this->_Parsing(file_get_contents($arr[0])));
                break;
            }
        }
        if ( 0 == count($arr) )
        {
            Zero_Logs::Set_Message_Error('NOT FOUND view [' . implode(', ', $this->_Template) . ']');
            return '';
        }
        if ( Zero_App::$Config->Site_TemplateParsing )
        {
            $this->_Data['__'] = $this->_Data;
            $this->_Data['_'] = array_keys($this->_Data);
        }
        ob_start();
        extract($this->_Data);
        $this->_Data = [];
        include $arr[1];
        return ob_get_clean();
    }

    /**
     * Poisk shablona
     *
     * V imeni shablona '_' meniaetsia na '/'
     * Algoritm poiska:
     * - /themes/theme-name/Zero/Users/Login.html
     * - /application/Zero/view/Users/Login.html
     * - /zero/view/Users/Login.html
     *
     * @param string $template imia shablona
     * @return array найденный шаблон [0], путь до скомпилированного tpl [1] ( путь от корниа сайта )
     */
    protected static function Search_Template($template)
    {
        $arr = explode('_', $template);
        $module = strtolower(array_shift($arr));
        //
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/view/' . implode('/', $arr) . self::EXT_VIEW;
        if ( file_exists($path) )
        {
            return [$path, ZERO_PATH_CACHE . '/view/' . $module . '/' . implode('/', $arr) . '_' . ZERO_LANG . '.tpl'];
        }
        //
        $path = ZERO_PATH_ZERO . '/' . $module . '/view/' . implode('/', $arr) . self::EXT_VIEW;
        if ( file_exists($path) )
        {
            return [$path, ZERO_PATH_CACHE . '/view/' . $module . '/' . implode('/', $arr) . '_' . ZERO_LANG . '.tpl'];
        }
        return [];
    }

    /**
     * Kompiliatciia html shablona v tpl shablon
     *
     * @param string $template stroka soderzhashchaia html shablon
     * @return string skompilirovanny`i` tpl shablon
     */
    private function _Parsing($template)
    {
        //  ДИРЕКТИВЫ
        // подключение шаблонов директивой инклуде {инклуде "дирнаме/филенаме"}
        $template = preg_replace_callback(self::PATTERN_INCLUDE, [$this, '_Parsing_Include'], $template);
        // парсинг языковых конструкций
        $template = preg_replace_callback(self::PATTERN_TRANSLATION, [$this, '_Parsing_Translation'], $template);
        // парсинг плагинов
        $template = preg_replace_callback(self::PATTERN_PLUGIN, [$this, '_Parsing_Controller'], $template);
        //
        // Вырезаем служебные комментарии
        $template = preg_replace('~{#(.*?)#}~s', '', $template);
        //	циклы и логика
        $template = preg_replace('~{((foreach|for|while|if|switch|case|default) .+?)}~si', '<' . '?php $1 { ?' . '>', $template);
        $template = preg_replace('~{(/|/foreach|/for|/while|/if|/switch|/case|/default)}~si', '<' . '?php } ?' . '>', $template);
        $template = preg_replace('~{else if (.+?)}~si', '<' . '?php } else if $1 { ?' . '>', $template);
        $template = preg_replace('~{else}~si', '<' . '?php } else { ?' . '>', $template);
        $template = preg_replace('~{(break|continue)}~si', '<' . '?php $1; ?' . '>', $template);
        //	переменные установка
        $template = preg_replace('~{set (\$[^}]{1,255})}~si', '<' . '?php $1; ?' . '>', $template);
        //	переменные вывод
        $template = preg_replace('~{(\$[^}]{1,255})}~si', '<' . '?php echo $1; ?' . '>', $template);
        //	функции и константы
        $template = preg_replace('~{([a-z]{1}[^}]{0,150})}~si', '<' . '?php echo $1; ?' . '>', $template);
        //
        return $template;
    }

    /**
     * Obrabotka direktivy` include v shablonakh (rekursivnaia)
     *
     * @param array $matches parametry` tega, vziaty`e iz shablona
     * @return string
     */
    private function _Parsing_Include($matches)
    {
        $arr = self::Search_Template($matches[1]);
        if ( 0 < count($arr) )
            $matches = file_get_contents($arr[0]);
        else
        {
            $matches = '';
            Zero_Logs::Set_Message_Error('NOT FOUND view [Include] [' . $matches[1] . ']');
        }
        return preg_replace_callback(self::PATTERN_INCLUDE, [$this, '_Parsing_Include'], $matches);
    }

    /**
     * Obrabotka direktivy` translation v shablonakh (rekursivnaia)
     *
     * @param array $matches parametry` tega, vziaty`e iz shablona
     * @return string
     */
    private function _Parsing_Translation($matches)
    {
        /*
        $default = str_replace('model prop ', '', $matches[2]);
        $default = str_replace('model ', '', $default);
        $default = str_replace('controller ', '', $default);
        return Zero_I18n::T($matches[1], $matches[2], $default);
        */
        return Zero_I18n::View($matches[1], $matches[2]);
    }

    /**
     * Obrabotka direktivy` plugin v shablonakh (rekursivnaia)
     *
     * @param array $matches parametry` tega, vziaty`e iz shablona
     * @return string
     */
    private function _Parsing_Controller($matches)
    {
        $plugin_name = $matches[1];
        $properties = isset($matches[2]) ? trim($matches[2]) : '';
        if ( $properties )
        {
            //            $properties = preg_replace('!([\w\d_]+)\s*=\s*!si', ',' . "\n" . '"\\1" => ', $properties);
            $properties = preg_replace('!([\w\d_]+)\s*=\s*!si', ',' . '"\\1" => ', $properties);
            $properties = trim($properties, ',');
        }
        return $plugin_name ? '<' . '?php echo $this->_Execute_Controller("' . $plugin_name . '", [' . $properties . ']); ?' . '>' : '';
    }

    /**
     * Dinahmicheskoe vy`polnenie plaginov (rekusvino)
     *
     * @param string $plugin_name
     * @param array $properties
     * @return string
     */
    private function _Execute_Controller($plugin_name, $properties = [])
    {
        //        Zero_Logs::Start('#{APP.Controller} ' . $plugin_name);
        $Controller = Zero_Controller::Makes($plugin_name, $properties);
        /* @var $Controller Zero_Controller */
        Zero_Logs::Start('#{PLUGIN} ' . $plugin_name . ' -> Action_Default');
        $view = $Controller->Action_Default();
        if ( $view instanceof Zero_View )
        {
            $view = $view->Fetch();
        }
        Zero_Logs::Stop('#{PLUGIN} ' . $plugin_name . ' -> Action_Default');
        //        Zero_Logs::Stop('#{APP.Controller} ' . $plugin_name);
        return $view;
    }
}
