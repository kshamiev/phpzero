<?php
/**
 * Routing or analysis of the incoming request.
 *
 * Definition and initialization of the input parameters of the request
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
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
     * Routing razdela s parametrami dlia navigatcii
     *
     * @var string
     */
    public $Url = '/';

    /**
     * Routing iazy`ka
     *
     * @var array
     */
    public $UrlSegment = [];

    /**
     * Analiz request url
     */
    public function __construct()
    {
        $this->Lang = Zero_App::$Config->Site_Language;
        $this->Url = '/';

        // если запрос консольный либо главная страница
        if ( !isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/' )
            return;

        // инициализация
        if ( substr($_SERVER['REQUEST_URI'], -1) == '/' )
            Zero_App::ResponseRedirect(substr($_SERVER['REQUEST_URI'], 0, -1));
        $this->Url = '';
        $row = explode('/', strtolower(rtrim(ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'), '/')));

        // язык
        if ( $this->Lang != $row[0] && isset(Zero_App::$Config->Language[$row[0]]) )
        {
            $this->Lang = array_shift($row);
            $this->Url = '/' . $this->Lang;
            if ( count($row) == 0 )
                return;
        }
        $this->UrlSegment = $row;
        $this->Url .= '/' . implode('/', $row);
    }
}
