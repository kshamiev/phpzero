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
     * Routing iazy`ka
     *
     * @var array
     */
    public $Param = [];

    /**
     * Routing iazy`ka
     *
     * @var array
     */
    public $ApiUrlSegment = [];

    /**
     * Routing iazy`ka
     *
     * @var array
     */
    public $ApiData = [];

    /**
     * Analiz request url
     */
    public function __construct()
    {
        $this->Lang = Zero_App::$Config->Site_Language;
        $this->LangId = Zero_App::$Config->Language[$this->Lang]['ID'];
        $this->Url = '/';

        $row = explode('/', strtolower(rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/')));

        // язык
        if ($this->Lang != $row[0] && isset(Zero_App::$Config->Language[$row[0]])) {
            $this->Lang = array_shift($row);
            $this->LangId = Zero_App::$Config->Language[$this->Lang]['ID'];
            $this->Url = '/' . $this->Lang;
        }

        // api
        if ( 'api' == $row[0] ) {
            $this->ApiUrlSegment = $row;
            $this->Url = '/' . $row[0] . (isset($row[1]) ? '/' . $row[1] : '');
            if ( $_SERVER['REQUEST_METHOD'] === "PUT" )
            {
                $data = file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']);
                $this->ApiData = json_decode($data, true);
            }
            else if ( $_SERVER['REQUEST_METHOD'] === "POST" )
            {
                $this->ApiData = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
            }
            return;
        }

        // парамтеры
        if (0 < count($row) && $row[0]) {
            $param = array_pop($row);
            if (preg_match("~.+?-([^/]+)$~", $param, $arr)) {
                $row[] = str_replace('-' . $arr[1], '', $param);
                foreach (explode('-', explode('.', $arr[1])[0]) as $segment) {
                    $arr = explode(':', $segment);
                    if (1 < count($arr))
                        $this->Param[$arr[0]] = $arr[1];
                }
            } else
                $row[] = $param;
            $this->Url .= implode('/', $row);
        }
    }
}
