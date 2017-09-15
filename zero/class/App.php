<?php
/**
 * The version of PhpZero
 */
define('VERSION_PHPZERO', '2.0.0');
/**
 * Режимы работы приложения
 */
define('ZERO_MODE_WEB', 'Web');
/**
 * Режимы работы приложения
 */
define('ZERO_MODE_API', 'Api');
/**
 * Режимы работы приложения
 */
define('ZERO_MODE_CONSOLE', 'Console');
/**
 * The absolute path to the project (site)
 */
define('ZERO_PATH_SITE', dirname(dirname(dirname(__DIR__))));
/**
 * Location of binary data
 */
define('ZERO_PATH_DATA', ZERO_PATH_SITE . '/upload/data');
/**
 * The location of the site log
 */
define('ZERO_PATH_LIBRARY', ZERO_PATH_SITE . '/library');
/**
 * The location of the site log
 */
define('ZERO_PATH_LOG', dirname(ZERO_PATH_SITE) . '/logs');
/**
 * Location cache
 */
define('ZERO_PATH_CACHE', dirname(ZERO_PATH_SITE) . '/cache');
/**
 * Communication with the outside world
 */
define('ZERO_PATH_EXCHANGE', ZERO_PATH_SITE . '/exchange');
/**
 * Location applications and modules
 */
define('ZERO_PATH_APPLICATION', ZERO_PATH_SITE . '/application');
/**
 * Location System
 */
define('ZERO_PATH_ZERO', ZERO_PATH_SITE . '/phpzero');

/**
 * Application.
 *
 * The main component of execute the application as a whole.
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 * @todo Реализовать полноценное добавление как отедблный функционал. Должно быть различие между добавлением и сохранением.
 * @todo Переработать Exception (только ошибки)
 */
class Zero_App
{
    /**
     * Запрошенный uri
     *
     * @var string
     */
    public static $Route = '';

    /**
     * Параметры uri
     *
     * @var array
     */
    public static $RouteParams = [];

    /**
     * Configuration
     *
     * @var Zero_Config
     */
    public static $Config = null;

    /**
     * Configuration
     *
     * @var Zero_OptionsValue
     */
    public static $Options = null;

    /**
     * Section (page)
     *
     * @var Zero_Section
     */
    public static $Section = null;

    /**
     * Controller
     *
     * @var Zero_Controllers
     */
    public static $Controller = null;

    /**
     * User
     *
     * @var Zero_Users
     */
    public static $Users = null;

    /**
     * Режим работы приложения (Api, Web, Console).
     *
     * @var string
     */
    public static $Mode;

    /**
     * Connection classes
     *
     * Setting up automatic downloads of files with the required classes
     *
     * @param string $class_name
     * @param bool $flagLog
     * @return bool
     */
    public static function Autoload($class_name, $flagLog = true)
    {
        if ( class_exists($class_name) )
            return true;
        $arr = explode('_', $class_name);
        $module = strtolower(array_shift($arr));
        $class = implode('/', $arr);
        //
        foreach (['class', 'api', 'sol'] as $folder)
        {
            $path = ZERO_PATH_APPLICATION . '/' . $module . '/' . $folder . '/' . $class . '.php';
            if ( file_exists($path) )
            {
                include_once $path;
                if ( class_exists($class_name) )
                    return true;
            }
            $path = ZERO_PATH_ZERO . '/' . $module . '/' . $folder . '/' . $class . '.php';
            if ( file_exists($path) )
            {
                include_once $path;
                if ( class_exists($class_name) )
                    return true;
            }
        }
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/class/' . $class . '.php';
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/class' . $class . '.php'; // old
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        $path = ZERO_PATH_APPLICATION . '/' . $module . '/' . $class . '.php'; // old
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        //
        $path = ZERO_PATH_ZERO . '/' . $module . '/class/' . $class . '.php';
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        $path = ZERO_PATH_ZERO . '/' . $module . '/class' . $class . '.php'; // old
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        $path = ZERO_PATH_ZERO . '/' . $module . '/' . $class . '.php'; // old
        if ( file_exists($path) )
        {
            include_once $path;
            if ( class_exists($class_name) )
                return true;
        }
        //        if ( isset(self::$Config->Mod[$module]) )
        if ( true == $flagLog )
            Zero_Logs::Set_Message_Error('Класс не найден: ' . $class_name);
        return false;
    }

    /**
     * Запрос к стороннему сервису (серверу)
     *
     * API запросы в вормате json
     *
     * @param string $method
     * @param string $url
     * @param string $content
     * @param string $basicHttpAccess 'login:passw'
     * @return string
     * @todo Переработать в ответы Zero_Request::Json()
     */

    public static function RequestJson($method, $url, $content = '', $accessBasicHttp = '', $accessUser = '')
    {
        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $head = "Content-Type: application/json; charset=utf-8\r\n";
        $head .= "Content-Length: " . strlen($content) . "\r\n";
        if ( $accessBasicHttp )
            $head .= "Authorization: Basic " . base64_encode($accessBasicHttp) . "\r\n";
        if ( $accessUser )
            $head .= "AuthUser: " . md5($accessUser) . "\r\n";
        $opts = [
            'http' => [
                'method' => $method,
                'header' => $head,
                'content' => $content,
                'timeout' => 300,
            ],
            'ssl' => [
                'verify_peer' => false,
            ]
        ];
        //
        $fp = fopen($url, 'rb', false, stream_context_create($opts));
        if ( $fp == false )
        {
            Zero_Logs::Set_Message_Error('Обращение к не корректному ресурсу:');
            Zero_Logs::Set_Message_Error($url);
            Zero_Logs::Set_Message_Error($content);
            return '';
        }
        $response = stream_get_contents($fp);
        fclose($fp);
        $data = json_decode($response, true);
        if ( !$data )
            return $response;
        return $data;
    }

    /**
     * Отдача результата работы в формате json
     *
     * Для ответов на API запросы
     *
     * @param $content
     * @param int $status
     */
    public static function ResponseJson($content, $status = 200)
    {

        $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header("Content-Length: " . strlen($content));
        header('HTTP/1.1 ' . $status . ' ' . $status);
        echo $content;

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    /**
     * Отдача результата работы в формате json
     *
     * Для ответов на API запросы
     *
     * @param $content
     * @param int $code
     * @param array $params
     */
    public static function ResponseJson200($content = null, $code = 0, $params = [])
    {
        if ( self::$Controller->Controller )
            $message = Zero_I18n::Message(self::$Controller->Controller, $code, $params);
        else
            $message = Zero_I18n::Message('Zero', $code, $params);

        $data = [
            'Code' => $message[0],
            'Message' => $message[1],
            'ErrorStatus' => false,
        ];

        if ( $content )
            $data['Content'] = $content;

        $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header("Content-Length: " . strlen($data));
        header('HTTP/1.1 200 200');
        echo $data;

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    /**
     * Отдача ошибки в работе в формате json
     *
     * Для ответов на API запросы
     *
     * @param $content
     * @param int $code
     * @param array $params
     */
    public static function ResponseJson409($content = null, $code = -1, $params = [])
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header('HTTP/1.1 200 200');

        if ( self::$Controller->Controller )
            $message = Zero_I18n::Message(self::$Controller->Controller, $code, $params);
        else
            $message = Zero_I18n::Message('Zero', $code, $params);

        Zero_Logs::Set_Message_Error($message[1]);

        $data = [
            'Code' => $message[0],
            'Message' => $message[1],
            'ErrorStatus' => true,
        ];

        if ( $content )
            $data['Content'] = $content;

        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    /**
     * Отдача ошибки в работе в формате json
     *
     * Для ответов на API запросы
     *
     * @param int $code
     * @param array $params
     * @deprecated ResponseJson409
     */
    public static function ResponseJson500($code = -1, $params = [])
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: application/json; charset=utf-8");
        header('HTTP/1.1 200 200');

        if ( self::$Controller->Controller )
            $message = Zero_I18n::Message(self::$Controller->Controller, $code, $params);
        else
            $message = Zero_I18n::Message('Zero', $code, $params);

        Zero_Logs::Set_Message_Error($message[1]);

        $data = [
            'Code' => $message[0],
            'Message' => $message[1],
            'ErrorStatus' => true,
        ];

        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    /**
     * Сборка ответа клиенту
     *
     * @return bool
     */
    public static function ResponseConsole()
    {
        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( self::$Config->Log_Output_File )
            Zero_Logs::Output_File();
        exit;
    }

    public static function ResponseImg($path)
    {
        header("Content-Type: " . Helper_File::File_Type($path));
        header("Content-Length: " . filesize($path));
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    public static function ResponseFile($path)
    {
        header("Content-Type: " . Helper_File::File_Type($path));
        header("Content-Length: " . filesize($path));
        header('Content-Disposition: attachment; filename = "' . basename($path) . '"');
        if ( file_exists($path) )
            echo file_get_contents($path);
        exit;
    }

    /**
     * Application initialization
     *
     * - Include Components (Zero_Config, (Zero_Route,) Zero_Session, Zero_Cache, Zero_Logs, Zero_View)
     * - Monitoring of the work application. Component Zero_Logs
     * - The configuration and initialization of the application. Component Zero_Config
     * - Initialize cache subsystem. Component Zero_Cache
     * - Processing incoming request (GET). Component Zero_Route
     * - Session Initialization. Component Zero_Session
     *
     * @param string $appLog префикс лог файла приложения
     * @param string $mode режим работы приложения
     */
    public static function Init($appLog = 'main')
    {
        // Если инициализация уже произведена
        if ( !is_null(self::$Config) )
            return;

        //  Include Components
        require_once ZERO_PATH_ZERO . '/zero/class/Config.php';
        require_once ZERO_PATH_ZERO . '/zero/class/Logs.php';
        require_once ZERO_PATH_ZERO . '/zero/class/DB.php';
        require_once ZERO_PATH_ZERO . '/zero/class/Session.php';
        require_once ZERO_PATH_ZERO . '/zero/class/Cache.php';
        require_once ZERO_PATH_ZERO . '/function.php';
        require_once ZERO_PATH_APPLICATION . '/function.php';

        spl_autoload_register(['Zero_App', 'Autoload']);
        set_exception_handler(['Zero_App', 'Exception']);
        // register_shutdown_function(['Zero_App', 'Exit_Application']);

        //  Configuration
        self::$Config = new Zero_Config();

        // Определение режима работы и роутинг
        if ( empty($_SERVER['REQUEST_URI']) )
        {
            self::$Mode = ZERO_MODE_CONSOLE;
        }
        //        else if ( strpos($_SERVER['REQUEST_URI'], '/api/') === 0 || strtolower(ZERO_MODE_API) == Zero_App::$Config->Site_DomainSub )
//        else if ( preg_match("~/v[0-9|.]+/~si", $_SERVER['REQUEST_URI']) || preg_match("~^/json/~si", $_SERVER['REQUEST_URI']) )
        else if ( preg_match("~^/(api|json)/~si", $_SERVER['REQUEST_URI']) )
        {
            self::$Mode = ZERO_MODE_API;
            app_route();
            app_request_data_api();
        }
        else
        {
            self::$Mode = ZERO_MODE_WEB;
            app_route();
        }

        //  Initializing monitoring system (Zero_Logs)
        Zero_Logs::Init(ZERO_PATH_LOG . '/' . $appLog . '_' . self::$Mode);

        // DB init config
        foreach (self::$Config->Db as $name => $config)
        {
            Zero_DB::Config_Add($name, $config);
        }

        // Options
        if ( self::$Config->Site_UseDB )
            self::$Options = new Zero_OptionsValue(true);
        else
            self::$Options = new Zero_OptionsValue();

        //  Initialize cache subsystem (Zero_Cache)
        if ( 0 < count(self::$Config->Memcache['Cache']) && class_exists('Memcache') )
            Zero_Cache::InitMemcache(self::$Config->Memcache['Cache']);

        //  Session Initialization (Zero_Session)
        Zero_Session::Init(self::$Config->Site_Token);

        // Шаблонизатор
        require_once ZERO_PATH_ZERO . '/zero/class/View.php';
    }

    public static function Execute()
    {
        // Maintenance mode
        if ( count(self::$Config->Site_MaintenanceIp) && empty(self::$Config->Site_MaintenanceIp[self::$Config->Ip]) )
        {
            $view = new Zero_View('Zero_Maintenance');
            $view = $view->Fetch();
            self::ResponseHtml($view, 200);
        }

        // Доступ с определенных IP адресов
        if ( count(self::$Config->Site_AccessAllowIp) && empty(self::$Config->Site_AccessAllowIp[self::$Config->Ip]) )
        {
            throw new Exception('Page Forbidden', 403);
        }

        // General Authorization Application
        if ( Zero_App::$Config->Site_AccessLogin )
        {
            if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != Zero_App::$Config->Site_AccessLogin || $_SERVER['PHP_AUTH_PW'] != Zero_App::$Config->Site_AccessPassword )
            {
                header('WWW-Authenticate: Basic realm="Auth"');
                header('HTTP/1.0 401 Unauthorized');
                die('Auth Failed');
            }
        }

        if ( true == Zero_App::$Config->Site_UseDB )
            self::executeUseDB();
        else
            self::executeUseDB_Not();
    }

    /**
     * Method of console (crontab) application execution
     */
    private static function executeUseDB_Not()
    {
        //  Пользователь
        self::$Users = Zero_Users::Factor();

        self::$Section = Zero_Section::Make();

        // Поиск в программе
        $route = [];
        foreach (self::$Config->Modules as $route)
        {
            if ( isset($route['routeWeb']) && isset($route['routeWeb']->Route[ZERO_URL]) )
            {
                $route = $route['routeWeb']->Route[ZERO_URL];
            }
            else if ( isset($route['routeApi']) && isset($route['routeApi']->Route[ZERO_URL]) )
            {
                $route = $route['routeApi']->Route[ZERO_URL];
            }
            else
                continue;
            break;
        }
        if ( 0 == count($route) || empty($route['Controller']) )
        {
            throw new Exception('Page Not Found', 404);
        }

        //  КОНТРОЛЛЕР
        $view = '';
        $messageResponse = ['Code' => 0, 'Message' => ''];
        if ( $route['Controller'] )
        {
            if ( self::Autoload($route['Controller'], false) )
            {
                // инициализация и проверка существование метода действия
                $Controller = Zero_Controller::Factory($route['Controller']);
                if ( isset($_REQUEST['act']) && $_REQUEST['act'] )
                    $action = $_REQUEST['act'];
                else
                    $action = $_SERVER['REQUEST_METHOD'];
                if ( !method_exists($Controller, 'Action_' . $action) )
                {
                    if ( 'GET' != $action )
                        throw new Exception('Контроллер не имеет метода: ' . $action, 409);
                    $action = 'Default';
                }

                // доступные операции - методы контроллера с учетом прав.
                $actionList = Zero_Engine::Get_Method_From_Class($route['Controller'], 'Action');
                if ( !isset($actionList[$action]) )
                    throw new Exception('Page Forbidden', 403);

                // выполнение контроллера
                $action = 'Action_' . $action;
                Zero_Logs::Start('#{CONTROLLER} ' . $route['Controller'] . ' -> ' . $action);
                $view = $Controller->$action();
                $messageResponse = $Controller->GetMessage();
                if ( true == $view instanceof Zero_View )
                {
                    /* @var $view Zero_View */
                    $view->Assign('Message', $messageResponse);
                    $view->Assign('H1', Zero_App::$Section->Name);
                    $view->Assign('Content', Zero_App::$Section->Content);
                    $view = $view->Fetch();
                }
                Zero_Logs::Stop('#{CONTROLLER} ' . $route['Controller'] . ' -> ' . $action);
            }
            else
            {
                $view = new Zero_View($route['Controller']);
                $view->Assign('Message', $messageResponse);
                $view->Assign('H1', Zero_App::$Section->Name);
                $view->Assign('Content', Zero_App::$Section->Content);
                $view = $view->Fetch();
            }
        }

        //  РАЗДЕЛ - СТРАНИЦА
        if ( isset($route['View']) && $route['View'] )
        {
            $viewLayout = new Zero_View($route['View']);
            $viewLayout->Assign('Message', $messageResponse);
            $viewLayout->Assign('H1', Zero_App::$Section->Name);
            $viewLayout->Assign('Content', $view);
            $view = $viewLayout->Fetch();
        }
        self::ResponseHtml($view, 200);
    }

    /**
     * Method of application execution
     *
     * - Инициализация запрошенного раздела (ZSection)
     * - Инициализация пользователя (Users)
     * - Инициализация и выполнение контролера и его действия
     * - Формирование и вывод профилированного результата
     *
     * - Initialization of the requested section (Section)
     * - User Initialization (Users)
     * - Initialization and execution of the controller and its actions
     * - The formation results and conclusion of the profiled format
     *
     * @throws Exception
     */
    private static function executeUseDB()
    {
        //  Пользователь
        self::$Users = Zero_Users::Factor();

        // General Authorization Users
        if ( isset($_SERVER['HTTP_AUTHUSER']) && 0 == self::$Users->ID )
        {
            self::$Users->Load_Token($_SERVER['HTTP_AUTHUSER']);
            if ( 0 == self::$Users->ID )
            {
                throw new Exception('Page Forbidden', 403);
            }
        }

        //  ИНИЦИАЛИЗАЦИЯ Раздел - Страница и Контроллер
        self::$Section = Zero_Section::Instance();
        if ( 0 < self::$Section->ID )
        {
            // страница отключена, закрыта.
            if ( 'no' == self::$Section->IsEnable )
                throw new Exception('Page Not Found', 404);
            // редирект
            if ( self::$Section->UrlRedirect )
                self::ResponseRedirect(self::$Section->UrlRedirect);
            // проверка прав на авторизованную страницу
//            if ( 1 < self::$Users->Groups_ID && 'yes' == self::$Section->IsAuthorized && false == self::$Section->Get_Access() )
            if ( false == self::$Section->Get_Access() )
                throw new Exception('Page Forbidden', 403);
            // загрузка контроллера
            if ( 0 < self::$Section->Controllers_ID )
            {
                self::$Controller = Zero_Controllers::Make(self::$Section->Controllers_ID, true);
            }
        }
        else
        {
            self::$Controller = Zero_Controllers::Instance();
            // проверка на существование контроллера по запрошенному урлу
            if ( 0 == self::$Controller->ID )
                throw new Exception('Page Not Found', 404);
            // проверка прав на авторизованный контроллер
            if ( 1 < self::$Users->Groups_ID && 'yes' == self::$Controller->IsAuthorized && 0 == count(self::$Controller->Get_Action_List()) )
                throw new Exception('Controller Forbidden', 403);
        }

        //  КОНТРОЛЛЕР
        $view = '';
        $messageResponse = ['Code' => 0, 'Message' => ''];
        if ( 0 < self::$Controller->ID )
        {
            if ( self::Autoload(self::$Controller->Controller, false) )
            {
                // инициализация и проверка существование метода действия
                $Controller = Zero_Controller::Factory(self::$Controller->Controller);
                if ( isset($_REQUEST['act']) && $_REQUEST['act'] )
                    $action = $_REQUEST['act'];
                else
                    $action = $_SERVER['REQUEST_METHOD'];
                if ( !method_exists($Controller, 'Action_' . $action) )
                {
                    if ( 'GET' != $action )
                        throw new Exception('Контроллер не имеет метода: ' . $action, 409);
                    $action = 'Default';
                }

                // доступные операции - методы контроллера с учетом прав.
                $actionList = self::$Controller->Get_Action_List();
                if ( !isset($actionList[$action]) )
                    throw new Exception('Action Forbidden', 403);

                // выполнение контроллера
                $action = 'Action_' . $action;
                Zero_Logs::Start('#{CONTROLLER} ' . self::$Controller->Controller . ' -> ' . $action);
                $view = $Controller->$action();
                $messageResponse = $Controller->GetMessage();
                if ( true == $view instanceof Zero_View )
                {
                    /* @var $view Zero_View */
                    $view->Assign('Message', $messageResponse);
                    $view->Assign('H1', Zero_App::$Section->Name);
                    $view->Assign('Content', Zero_App::$Section->Content);
                    $view = $view->Fetch();
                }
                Zero_Logs::Stop('#{CONTROLLER} ' . self::$Controller->Controller . ' -> ' . $action);
            }
            else
            {
                $view = new Zero_View(self::$Controller->Controller);
                $view->Assign('Message', $messageResponse);
                $view->Assign('H1', Zero_App::$Section->Name);
                $view->Assign('Content', Zero_App::$Section->Content);
                $view = $view->Fetch();
            }
        }

        //  РАЗДЕЛ - СТРАНИЦА
        if ( self::$Section->Layout )
        {
            $viewLayout = new Zero_View(self::$Section->Layout);
            $viewLayout->Assign('Message', $messageResponse);
            $viewLayout->Assign('H1', Zero_App::$Section->Name);
            $viewLayout->Assign('Content', $view);
            $view = $viewLayout->Fetch();
        }
        self::ResponseHtml($view, 200);
    }

    /**
     * Redirect to the specified page
     *
     * @param string $url link to which page to produce redirect
     */
    public static function ResponseRedirect($url)
    {
        header('HTTP/1.1 301 Redirect');
        header('Location: ' . $url);
        exit;
    }

    /**
     * Ответ. Выдача контента в формате html
     *
     * @param mixed $content
     * @param int $status
     */
    public static function ResponseHtml($content, $status = 200)
    {
        header('Pragma: no-cache');
        header('Last-Modified: ' . date('D, d M Y H:i:s') . 'GMT');
        header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Content-Type: text/html; charset=utf-8");
        header('Access-Control-Allow-Origin: *');
        header('HTTP/1.1 ' . $status . ' ' . $status);
        echo $content;

        // Логирование (в браузер)
        if ( self::$Config->Log_Output_Display )
            Zero_Logs::Output_Display();

        // закрываем соединение с браузером (работает только под нгинx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Логирование в файлы
        if ( Zero_App::$Config->Log_Output_File )
        {
            Zero_Logs::Output_File();
        }
        exit;
    }

    /**
     * Obrabotchik iscliuchenii` dlia funktcii set_exception_handler()
     *
     * - '403' standartny`i` otvet na zakry`ty`i` razdel (stranitcu sai`ta)
     * - '404' standartny`i` otvet ne nai`dennogo dokumenta
     * - '500' vse ostal`ny`e kriticheskie oshibki prilozheniia libo servera
     *
     * @param Exception $exception
     * @todo Переработать в ответы Zero_Response::Html403()
     * @todo Переработать в ответы Zero_Response::Json()
     */
    public static function Exception(Exception $exception)
    {
        $codeList = [
            301 => 1,
            403 => 1,
            404 => 1,
        ];
        $code = $exception->getCode();
        if ( empty($codeList[$code]) )
        {
            $code = 409;
            self::exception_Trace($exception);
        }
        $viewLayout = new Zero_View('Zero_Exception');
        $viewLayout->Assign('code', $code);
        $viewLayout->Assign('message', $exception->getMessage());
        $controller = 'Zero_Exception_' . $code;
        if ( self::Autoload($controller) )
        {
            $Controller = Zero_Controller::Makes($controller);
            if ( method_exists($Controller, 'Action_Default') )
            {
                $viewController = $Controller->Action_Default();
                if ( true == $viewController instanceof Zero_View )
                {
                    /* @var $viewController Zero_View */
                    $viewController->Assign('code', $code);
                    $viewController->Assign('message', $exception->getMessage());
                    $view = $viewController->Fetch();
                }
                else
                {
                    $view = $viewController;
                }
            }
            else
            {
                Zero_Logs::Set_Message_Error("У контроллера '{$Controller}' нет метода по умолчанию");
                $view = '';
            }
            $viewLayout->Assign('Content', $view);
        }
        else
        {
            $view = new Zero_View($controller);
            $view->Assign('code', $code);
            $view->Assign('message', $exception->getMessage());
            $viewLayout->Assign('Content', $view->Fetch());
        }
        // Логирование (в браузер)
        if ( $code != 409 || self::$Config->Log_Output_Display )
            self::ResponseHtml($viewLayout->Fetch(), $code);
        else
            self::ResponseConsole();
    }

    /**
     * Трассировка данных исключения. (trace)
     *
     * @param Exception $exception
     */
    private static function exception_Trace(Exception $exception)
    {
        $range_file_error = 10;
        $error = "#{ERROR_EXCEPTION} " . $exception->getMessage() . ' ' . $exception->getFile() . '(' . $exception->getLine() . ')';
        Zero_Logs::Set_Message_Error($error);
        if ( Zero_App::$Config->Log_Output_Display == true )
        {
            Zero_Logs::Set_Message_ErrorTrace(Zero_Logs::Get_SourceCode($exception->getFile(), $exception->getLine(), $range_file_error));
        }

        $traceList = $exception->getTrace();
        array_shift($traceList);
        foreach ($traceList as $id => $trace)
        {
            if ( !isset($trace['args']) )
                continue;
            $args = [];
            $range_file_error = $range_file_error - 2;
            foreach ($trace['args'] as $arg)
            {
                if ( is_scalar($arg) )
                    $args[] = "'" . $arg . "'";
                else if ( is_array($arg) )
                    $args[] = print_r($arg, true);
                else if ( is_object($arg) )
                    $args[] = get_class($arg) . ' Object...';
            }
            $trace['args'] = join(', ', $args);
            if ( isset($trace['class']) )
                $callback = $trace['class'] . $trace['type'] . $trace['function'];
            else if ( isset($trace['function']) )
                $callback = $trace['function'];
            else
                $callback = '';
            if ( !isset($trace['file']) )
                $trace['file'] = '';
            if ( !isset($trace['line']) )
                $trace['line'] = 0;
            $error = "\t#{" . $id . "}" . $trace['file'] . '(' . $trace['line'] . '): ' . $callback . "(" . str_replace("\n", "", $trace['args']) . ");";
            Zero_Logs::Set_Message_Error($error);
            if ( Zero_App::$Config->Log_Output_Display == true )
            {
                Zero_Logs::Set_Message_ErrorTrace(Zero_Logs::Get_SourceCode($trace['file'], $trace['line'], $range_file_error));
            }
        }
    }
}
