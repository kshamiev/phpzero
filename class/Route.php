<?php
/**
 * The absolute http path to the project (site)
 */
define('ZERO_HTTP', Zero_App::$Config->Http);
define('HTTP', Zero_App::$Config->Http);
/**
 * http location of static data
 */
define('ZERO_HTTPA', Zero_App::$Config->Http_Assets);
define('HTTPA', Zero_App::$Config->Http_Assets);
/**
 * http location of binary data
 */
define('ZERO_HTTPD', Zero_App::$Config->Http_Upload);
define('HTTPD', Zero_App::$Config->Http_Upload);
/**
 * http location of history
 */
define('ZERO_HTTPH', Zero_App::$Config->Http_Ref);
define('HTTPH', Zero_App::$Config->Http_Ref);
/**
 * Component. RRouting or analysis of the incoming request.
 *
 * Definition and initialization of the input parameters of the request
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Route
{
    /**
     * Abstraktny`i` identifikator roditel`skogo ob``ekta
     *
     * @var integer
     */
    public $obj_parent_id = 0;

    /**
     * Abstraktny`i` identifikator ob``ekta
     *
     * @var integer
     */
    public $obj_id = 0;

    /**
     * Identifikator postranichnosti
     *
     * @var integer
     */
    public $page_id = 0;

    /**
     * Routing iazy`ka
     *
     * @var string
     */
    public $lang = '';

    /**
     * Identifikator iazy`ka
     *
     * @var integer
     */
    public $lang_id = 0;

    /**
     * Routing razdela s parametrami dlia navigatcii
     *
     * @var string
     */
    public $url = '';

    /**
     * Routing razdela bez parametrov dlia identifikatcii razdelov
     *
     * @var string
     */
    public $url_section = '';

    /**
     * Analiz request url
     *
     * @param string $request request url
     */
    public function __construct($request = '')
    {
        //  Language
        $language = Zero_App::$Config->Language;
        $this->lang = Zero_App::$Config->Site_Language;
        $this->lang_id = $language[$this->lang]['ID'];

        if ( !$request )
            return;

        $row = explode('/', strtolower($request));
        if ( $this->lang != $row[0] && isset($language[$row[0]]) )
        {
            $this->lang = array_shift($row);
            $this->lang_id = $language[$this->lang]['ID'];
            $this->url = '/' . $this->lang;
        }

        //  Section
        if ( 0 < count($row) )
        {
            //  Options
            $param = array_pop($row);
            if ( preg_match("~.+?-([^/]+-[^/]+)$~", $param, $arr) )
            {
                $row[] = str_replace('-' . $arr[1], '', $param);
                $param = explode('.', $arr[1]);
                $param = explode('-', $param[0]);
                while ( 1 < count($param) )
                {
                    $method = array_shift($param);
                    if ( method_exists($this, $method) )
                        $this->$method(array_shift($param));
                    else
                        array_shift($param);
                }
            }
            else
                $row[] = $param;

            $this->url_section = implode('/', $row);
            $this->url .= '/' . $this->url_section;
            $this->url_section = '/' . preg_replace("~(-[^/]+-[^/]+)/~i", "/", $this->url_section);
        }
    }

    /**
     * Initcializatciia identifikatora abstraktnogo roditel`skogo ob``ekta
     *
     * @param integer $param vhodnoi` parametr zaprosa
     */
    protected function pid($param)
    {
        $this->obj_parent_id = $param;
    }

    /**
     * Initcializatciia identifikatora abstraktnogo ob``ekta
     *
     * @param integer $param vhodnoi` parametr zaprosa
     */
    protected function id($param)
    {
        $this->obj_id = $param;
    }

    /**
     * Initcializatciia identifikatora tekushchei` stranitcy`
     *
     * @param integer $param vhodnoi` parametr zaprosa
     */
    protected function pg($param)
    {
        $this->page_id = $param;
    }
}
