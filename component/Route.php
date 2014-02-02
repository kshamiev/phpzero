<?php

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
     * Routing iazy`ka
     *
     * @var string
     */
    public $Lang = '';

    /**
     * Identifikator iazy`ka
     *
     * @var integer
     */
    public $LangId = 0;

    /**
     * Routing razdela s parametrami dlia navigatcii
     *
     * @var string
     */
    public $Url = '';

    /**
     * Routing razdela bez parametrov dlia identifikatcii razdelov
     *
     * @var string
     */
    public $UrlSection = '';

    /**
     * Routing iazy`ka
     *
     * @var array
     */
    public $Param = [];

    /**
     * Analiz request url
     */
    public function __construct()
    {
        $this->Param['pid'] = 0;
        $this->Param['id'] = 0;
        $this->Param['pg'] = 0;

        //  Language
        $language = Zero_App::$Config->Language;
        $this->Lang = Zero_App::$Config->Site_Language;
        $this->LangId = $language[$this->Lang]['ID'];
        $this->Url = '/';

        if ( !isset($_SERVER['REQUEST_URI']) || '/' == $_SERVER['REQUEST_URI'] )
            return;

        $this->Url = '';

        $row = explode('/', strtolower(rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/')));
        if ( $this->Lang != $row[0] && isset($language[$row[0]]) )
        {
            $this->Lang = array_shift($row);
            $this->LangId = $language[$this->Lang]['ID'];
            $this->Url = '/' . $this->Lang;
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
                    $this->Param[array_shift($param)] = array_shift($param);
                }
            }
            else
                $row[] = $param;

            $this->UrlSection = implode('/', $row);
            $this->Url .= '/' . $this->UrlSection;
            $this->UrlSection = '/' . preg_replace("~(-[^/]+-[^/]+)/~i", "/", $this->UrlSection);
        }
    }
}
