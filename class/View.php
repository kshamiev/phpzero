<?php

/**
 * Component. Representation.
 *
 * Implements a programmatic interaction of (business logic) with the presentation of data (patterns).
 * Collects and encapsulates data within the template.
 * Parsing templates.
 * Gathers ready-made template to the transferred data (as it executes a program.)
 * The mechanism of the multi-language templates
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
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
    const PATTERN_INCLUDE = '~\{include[ ]+[\'"]+([\w\d\/_]+)[\'"]+\}~si';

    /**
     * Reguliarnoe vy`razhenie dlia obrabotki direktiv plugin
     */
    const PATTERN_PLUGIN = '~\{plugin[ ]+[\'"]+([\w\d_]+)[\'"]+(\s+[^\{\}]+)?\}~si';

    /**
     * Reguliarnoe vy`razhenie dlia obrabotki direktiv translation
     */
    const PATTERN_TRANSLATION = '~\{translation[ ]+[\'"]+([\w\d_]+)[\'"]+[ ]+[\'"]+([^"\']+)[\'"]+\}~si';

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
            $this->_Template[] = $template;
    }

    /**
     * Dobavlenie shablona predstavleniia v stek
     *
     * @param string $template imia shablona (kak pravilo imia kontrollera[ + suffiks])
     */
    public function Template_Add($template)
    {
        $this->_Template[] = $template;
    }

    /**
     * Udalenie shablona predstavleniia iz steka
     *
     * Esli ne ukazan proishodit polnaia ochistka steka
     *
     * @param string $template imia shablona (kak pravilo imia kontrollera[ + suffiks])
     */
    public function Template_Rem($template = '')
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
    public function Remove($variable = '')
    {
        if ( $variable )
            unset($this->_Data[$variable]);
        else
            $this->_Data = [];
    }

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
        $html = '';
        $tpl = '';
        foreach ($this->_Template as $template)
        {
            $html = $this->Search_Template($template);
            if ( '' != $html )
            {
                $tpl = ZERO_PATH_CACHE . '/_tpl/' . $html . '_' . Zero_App::$Route->lang . '.tpl';
                if ( 1 == Zero_App::$Config->View_TemplateParsing || !file_exists($tpl) )
                    Zero_Helper_FileSystem::File_Save($tpl, $this->_Parsing(file_get_contents(ZERO_PATH_SITE . '/' . $html . '.html')));
                break;
            }
        }
        if ( '' == $html )
        {
            Zero_Logs::Set_Message("Not found template [{" . implode(', ', $this->_Template) . "}]");
            return '';
        }
        if ( Zero_App::$Config->View_TemplateParsing )
        {
            $this->_Data['__'] = $this->_Data;
            $this->_Data['_'] = array_keys($this->_Data);
        }
        ob_start();
        extract($this->_Data);
        $this->_Data = [];
        include $tpl;
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
     * @return string nai`denny`i` shablon ( put` ot kornia sai`ta )
     */
    public static function Search_Template($template)
    {
        $arr = explode('_', $template);
        $module = strtolower(array_shift($arr));
        $template = implode('/', $arr);

        $template_exists = '/' . Zero_App::$Config->Themes . '/' . $module . '/' . $template;
                echo $template . ' [THEMES] => ' . basename(ZERO_PATH_THEMES) . $template_exists . '.html <br><br>';
        if ( !file_exists(ZERO_PATH_THEMES . $template_exists . '.html') )
        {
            $template_exists = '/' . $module . '/view/' . $template;
                        echo $template . ' [APPLICATION] => ' . basename(ZERO_PATH_APPLICATION) . $template_exists . '.html <br><br>';
            if ( !file_exists(ZERO_PATH_APPLICATION . $template_exists . '.html') )
            {
                $template_exists = '/view/' . $template;
                                echo $template . ' [PHPZERO] => ' . basename(ZERO_PATH_PHPZERO) . $template_exists . '.html <br><br>';
                if ( !file_exists(ZERO_PATH_PHPZERO . $template_exists . '.html') )
                    return '';
                else
                    $template_exists = basename(ZERO_PATH_PHPZERO) . $template_exists;
            }
            else
                $template_exists = basename(ZERO_PATH_APPLICATION) . $template_exists;
        }
        else
            $template_exists = basename(ZERO_PATH_THEMES) . $template_exists;
        return $template_exists;
    }

    /**
     * Kompiliatciia html shablona v tpl shablon
     *
     * @param string $template stroka soderzhashchaia html shablon
     * @return string skompilirovanny`i` tpl shablon
     */
    private function _Parsing($template)
    {
        //  DIREKTIVY
        // podcliuchenie shablonov direktivoi` include {include "dirname/filename"}
        $template = preg_replace_callback(self::PATTERN_INCLUDE, [$this, '_Parsing_Include'], $template);
        // parsing iazy`kovy`kh konstruktcii`
        $template = preg_replace_callback(self::PATTERN_TRANSLATION, [$this, '_Parsing_Translation'], $template);
        // parsing plaginov
        $template = preg_replace_callback(self::PATTERN_PLUGIN, [$this, '_Parsing_Plugin'], $template);
        //
        // Vy`rezaem sluzhebny`e kommentarii
        $template = preg_replace('~{#(.*?)#}~s', '', $template);
        // Parsim konstanty` shabona
        /*
                $template = str_replace('{HTTP}', '<' . '?php echo Zero_App::$Config->Http; ?' . '>', $template);
                $template = str_replace('{HTTPA}', '<' . '?php echo Zero_App::$Config->Http_Assets; ?' . '>', $template);
                $template = str_replace('{HTTPD}', '<' . '?php echo Zero_App::$Config->Http_Upload; ?' . '>', $template);
                $template = str_replace('{HTTPH}', '<' . '?php echo Zero_App::$Config->Http_Ref; ?' . '>', $template);
          */
        $template = str_replace('{URL}', '<' . '?php echo Zero_App::$Route->url; ?' . '>', $template);
        if ( Zero_App::$Route->lang != Zero_App::$Config->LanguageDefault )
            $template = str_replace('{LANG}', '/<' . '?php echo Zero_App::$Route->lang; ?' . '>', $template);
        else
            $template = str_replace('{LANG}', '', $template);
        //	tcicly` i logika
        $template = preg_replace('~{((foreach|for|while|if|switch|case|default) .+?)}~si', '<' . '?php $1 { ?' . '>', $template);
        $template = preg_replace('~{(/|/foreach|/for|/while|/if|/switch|/case|/default)}~si', '<' . '?php } ?' . '>', $template);
        $template = preg_replace('~{else if (.+?)}~si', '<' . '?php } else if $1 { ?' . '>', $template);
        $template = preg_replace('~{else}~si', '<' . '?php } else { ?' . '>', $template);
        $template = preg_replace('~{(break|continue)}~si', '<' . '?php $1; ?' . '>', $template);
        //	peremenny`e ustanovka
        $template = preg_replace('~{set (\$[^}]{1,255})}~si', '<' . '?php $1; ?' . '>', $template);
        //	peremenny`e vy`vod
        $template = preg_replace('~{(\$[^}]{1,255})}~si', '<' . '?php echo $1; ?' . '>', $template);
        //	funktcii i konstanty`
        $template = preg_replace('~{([a-z]{1}[^}]{0,150})}~si', '<' . '?php echo $1; ?' . '>', $template);
        //  Adjustment of links translation
        /*
        if ( Zero_App::$Route->lang != Zero_App::$Config->LanguageDefault )
            $template = preg_replace('~(<a.+?href=[\'"]+)([^"\']+)([\'"])+~si', '$1/' . Zero_App::$Route->lang . '$2$3', $template);
        */
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
        if ( '' != $template = $this->Search_Template($matches[1]) )
            $matches = file_get_contents(ZERO_PATH_SITE . '/' . $template . '.html');
        else
            $matches = '';
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
        $default = str_replace('model prop ', '', $matches[2]);
        $default = str_replace('model ', '', $default);
        $default = str_replace('controller ', '', $default);
        return Zero_I18n::T($matches[1], $matches[2], $default);
    }

    /**
     * Obrabotka direktivy` plugin v shablonakh (rekursivnaia)
     *
     * @param array $matches parametry` tega, vziaty`e iz shablona
     * @return string
     */
    private function _Parsing_Plugin($matches)
    {
        $plugin_name = $matches[1];
        $properties = isset($matches[2]) ? trim($matches[2]) : '';
        if ( $properties )
        {
            $properties = preg_replace('!([\w\d_]+)\s*=\s*!si', ',' . "\n" . '"\\1" => ', $properties);
            $properties = trim($properties, ',');
        }
        return $plugin_name ? '<' . '?php echo $this->_Execute_Plugin("' . $plugin_name . '", [' . $properties . ']); ?' . '>' : '';
    }

    /**
     * Dinahmicheskoe vy`polnenie plaginov (rekusvino)
     *
     * @param string $plugin_name
     * @param array $properties
     * @return string
     */
    private function _Execute_Plugin($plugin_name, $properties = [])
    {
        Zero_Logs::Start('#{APP.Plugin} ' . $plugin_name);
        $Plugin = Zero_Plugin::Make($plugin_name, $properties);
        $View = $Plugin->Execute();
        if ( $View instanceof Zero_View )
        {
            Zero_Logs::Start('#{PLUGIN.View} ' . $plugin_name);
            $View = $View->Fetch();
            Zero_Logs::Stop('#{PLUGIN.View} ' . $plugin_name);
        }
        Zero_Logs::Stop('#{APP.Plugin} ' . $plugin_name);
        return $View;
    }
}
