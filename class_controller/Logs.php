<?php

/**
 * Component. Monitoring i sniatie statistiki raboty` prilozheniia.
 *
 * Profilirovanny`e danny`e po prilozheniiam:
 * - Paulzovatel`skie soobshcheniia
 * - Oshibki programmirovaniia
 * - Iscliucheniia
 * - Tai`mery` vremeni vy`polneniia
 * - Zatrachennaia pamiat`
 * - Dei`stvii` pol`zovatelia
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Logs
{
    /**
     * Massiv soobshchenii` sistemy`
     *
     * @var array
     */
    private static $_Message = [];

    /**
     * Data i vremia v formate timestamp
     *
     * @var integer
     */
    private static $_StartTime;

    /**
     * Massiv vremenny`kh metok
     *
     * @var array
     */
    private static $_CurrentTime;

    /**
     * Uroven` vlozhennosti vremenny`kh metok
     *
     * @var int
     */
    private static $_CurrentTimeLevel = 0;

    /**
     * Dopustimy`i` vremennoi` porog. Pri prevy`shenii kotorogo rabotaiut tai`mery`.
     *
     * @var float
     */
    private static $_CurrentTimeLimit = 0.000;

    /**
     * Danny`e o rabote prilozheniia v tcelom.
     *
     * Obrabotanny`e danny`e profilirovaniia tai`merov i pamiati.
     *
     * @var array
     */
    private static $_OutputApplication;

    /**
     * Osnovnoe imia log fai`lov
     *
     * @var string
     */
    private static $_FileLog = '';

    /**
     * Initcializatciia soobshchenii`, log fai`la, tai`merov
     *
     */
    public static function Init($file_log = 'application')
    {
        self::$_Message = [];
        self::$_StartTime = microtime(1);
        self::$_CurrentTime = [];
        self::$_FileLog = $file_log;

        // Initialization of the profiled application processors
        set_error_handler(['Zero_Logs', 'Error_Handler']);
        set_exception_handler(['Zero_Logs', 'Exception_Handler']);
        register_shutdown_function(['Zero_Logs', 'Exit_Application']);
    }

    /**
     * Initcializatciia vhodiashchego sistemnogo soobshcheniia.
     *
     * $level mozhet prinimat` znacheniia:
     * error, warning, code
     *
     * @param string $value Soobshchenie ob oshibke
     * @param string $level Uroven` oshibki
     * @return string Level (code - only display debug, error, warning - display debug and save file log)
     */
    public static function Set_Message($value, $level = 'error')
    {
        self::$_Message[] = [$value, $level];
        return $level;
    }

    /**
     * Start tai`mera po cliuchu
     *
     * @param string $key cliuch
     */
    public static function Start($key)
    {
        self::$_CurrentTime[$key]['start'] = microtime(true);
        self::$_CurrentTime[$key]['level'] = self::$_CurrentTimeLevel;
        self::$_CurrentTimeLevel++;
    }

    /**
     * Ostanovka tai`mera po cliuchu
     *
     * @param string $key cliuch
     */
    public static function Stop($key)
    {
        self::$_CurrentTime[$key]['stop'] = microtime(true);
        self::$_CurrentTimeLevel--;
    }

    /**
     * Obrabotchik oshibok dlia funktcii set_error_handler()
     *
     * @param int $code kod oshibki
     * @param string $message soobshchenie ob oshibke
     * @param string $filename fai`l v kotorom proizoshla oshibka
     * @param string $line stroka, v kotoroi` proizoshla oshibka
     * @throws ErrorException
     */
    public static function Error_Handler($code, $message, $filename, $line)
    {
        throw new ErrorException($message, $code, 0, $filename, $line);
    }

    /**
     * Obrabotchik iscliuchenii` dlia funktcii set_exception_handler()
     *
     * - '403' standartny`i` otvet na zakry`ty`i` razdel (stranitcu sai`ta)
     * - '404' standartny`i` otvet ne nai`dennogo dokumenta
     * - '500' vse ostal`ny`e kriticheskie oshibki prilozheniia libo servera
     *
     * @param Exception $exception
     */
    public static function Exception_Handler(Exception $exception)
    {
        if ( 403 == $exception->getCode() )
        {
            self::$_CurrentTime = [];
            self::Set_Message('Section Url: ' . Zero_App::$Config->Host . Zero_App::$Route->UrlSection);
            header('HTTP/1.1 403 Access Denied');
        }
        else if ( 404 == $exception->getCode() )
        {
            self::$_CurrentTime = [];
            self::Set_Message('Section Url: ' . Zero_App::$Config->Host . Zero_App::$Route->UrlSection);
            header('HTTP/1.1 404 Not Found');
        }
        else
        {
            header('HTTP/1.1 500 Server Error');
            $range_file_error = 10;
            self::Set_Message("#{ERROR_EXCEPTION} " . $exception->getMessage() . ' ' . $exception->getFile() . '(' . $exception->getLine() . ')');
            self::Set_Message(self::Get_SourceCode($exception->getFile(), $exception->getLine(), $range_file_error), '');
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
                $error = "   #{" . $id . "}" . $trace['file'] . '(' . $trace['line'] . '): ' . $callback . "(" . str_replace("\n", "", $trace['args']) . ");";
                self::Set_Message($error);
                if ( $trace['file'] && $trace['line'] )
                    self::Set_Message(self::Get_SourceCode($trace['file'], $trace['line'], $range_file_error), 'code');
            }
        }

        ob_end_clean();
        $View = new Zero_View('Error');
        $View->Assign('http_status', $exception->getCode());
        echo $View->Fetch();
    }

    /**
     * Profilirovanie raboty` prilozheniia pri ego zavershenii
     *
     * - Sbor vsekh tai`merov i zatrachennoi` pamiati
     * - Zamer polnogo vremeni vy`polneniia prilozheniia
     * - Vy`vod vsei` profilirovannoi` informatcii v ukazanny`e istochniki
     */
    public static function Exit_Application()
    {
        // Logirovanie v brauzer
        if ( Zero_App::$Config->Log_Output_Display && 'html' == Zero_App::$Response )
            self::Output_Display();

        // zakry`vaem soedinenie s brauzerom (rabotaet tol`ko pod nginx)
        if ( function_exists('fastcgi_finish_request') )
            fastcgi_finish_request();

        // Logirovanie v fai`ly`
        if ( Zero_App::$Config->Log_Output_File )
            self::Output_File();
    }

    /**
     * Vy`vod otladochnoi` informatcii v brauzer dlia razrabotchikov
     */
    protected static function Output_Display()
    {
        $iterator_list = [];
        $iterator = Zero_Session::Get_Instance()->getIterator();
        while ( $iterator->valid() )
        {
            $iterator_list[$iterator->key()] = get_class($iterator->current());
            $iterator->next();
        }
        $View = new Zero_View('Zero_Logs_Debug');
        $View->Assign('output', self::Get_Usage_MemoryAndTime());
        $View->Assign('message', self::$_Message);
        $View->Assign('iterator_list', $iterator_list);
        echo $View->Fetch();
    }

    /**
     * Vy`vod vsei` profilirovannoi` informatcii v log fai`ly`
     *
     */
    protected static function Output_File()
    {
        self::$_FileLog = Zero_App::$Config->Host . '_' . self::$_FileLog;
        // Logiruem rabotu prilozheniia v tcelom
        if ( Zero_App::$Config->Log_Profile_Application )
        {
            $output = self::Get_Usage_MemoryAndTime();
            $output = date('[d.m.Y H:i:s]') . "\n" . join("\n", $output) . "\n\n";
            self::Save_File($output, self::$_FileLog);
        }
        //
        if ( 0 < count(self::$_Message) )
        {
            $output = self::Get_Usage_MemoryAndTime()[0];
            $errors = [];
            $warnings = [];
            foreach (self::$_Message as $row)
            {
                if ( 'error' == $row[1] )
                    $errors[] = str_replace(["\r", "\t"], " ", var_export($row[0], true));
                else if ( 'warning' == $row[1] )
                    $warnings[] = str_replace(["\r", "\t"], " ", var_export($row[0], true));
            }
            // logirovanie oshibki v fai`l
            if ( Zero_App::$Config->Log_Profile_Error && 0 < count($errors) )
            {
                array_unshift($errors, str_replace(["\r", "\t"], " ", $output));
                $errors = preg_replace('![ ]{2,}!', ' ', join("\n", $errors));
                $errors = date('[d.m.Y H:i:s]') . "\n" . $errors . "\n\n";
                self::Save_File($errors, self::$_FileLog . '_errors');
            }
            // logirovanie preduprezhdenii` v fai`l
            if ( Zero_App::$Config->Log_Profile_Warning && 0 < count($warnings) )
            {
                array_unshift($warnings, str_replace(["\r", "\t"], " ", $output));
                $warnings = preg_replace('![ ]{2,}!', ' ', join("\n", $warnings));
                $warnings = date('[d.m.Y H:i:s]') . "\n" . $warnings . "\n\n";
                self::Save_File($warnings, self::$_FileLog . '_warnings');
            }
        }
        // logirovanie operatcii` pol`zovatelia v fai`l
        if ( Zero_App::$Config->Log_Profile_Action && isset($_REQUEST['act']) && $_REQUEST['act'] )
        {
            $operation = date('[d.m.Y H:i:s]') . "\t";
            $operation .= Zero_App::$Users->Login . "\t";
            $operation .= Zero_App::$Section->Controller . '.' . $_REQUEST['act'] . "\t";
            foreach (Zero_App::Get_Variable('action_message') as $message)
            {
                $operation .= $message[0] . "\t";
            }
            $operation .= Zero_App::$Config->Http . $_SERVER['REQUEST_URI'] . "\n";
            self::Save_File($operation, self::$_FileLog . '_action');
        }
    }

    /**
     * Poluchenie ishodnogo kuska koda v oblasti oshibki fai`la
     *
     * @param $file put` do fai`la
     * @param $line stroka s oshibkoi`
     * @param $range_file_error diapazon vy`vodimy`kh strok fai`la vokrug oshibochneoi` stroki
     * @return string
     */
    protected static function Get_SourceCode($file, $line, $range_file_error)
    {
        $file_line = explode('<br />', highlight_file($file, true));
        $offset = $line - $range_file_error;
        if ( $offset < 0 )
            $offset = 0;
        $length = isset($file_line[$line + $range_file_error]) ? $line + $range_file_error : count($file_line) - 1;
        $View = new Zero_View('Zero_Logs_SourceCode');
        $View->Assign('file_line', $file_line);
        $View->Assign('offset', $offset < 0 ? 0 : $offset);
        $View->Assign('length', $length);
        $View->Assign('line', $line);
        return $View->Fetch();
    }

    /**
     * Obrabotka i poluchenie profilirovanny`kh tai`merov i zatrachennoi` pamiati.
     *
     * @return array
     */
    protected static function Get_Usage_MemoryAndTime()
    {
        if ( null === self::$_OutputApplication )
        {
            // initcializatciia logov
            if ( isset($_SERVER['REQUEST_URI']) )
                self::$_OutputApplication = ["#{REQUEST_URI} " . Zero_App::$Config->Http . $_SERVER['REQUEST_URI']];
            else if ( isset($_SERVER['argv'][1]) )
                self::$_OutputApplication = ["#{DEMON} " . $_SERVER['argv'][1] . ' ' . Zero_App::$Config->Http];
            // Sobiraem tai`mery` v kuchu
            foreach (self::$_CurrentTime as $description => $time)
            {
                if ( 3 == count($time) )
                {
                    $limit = round($time['stop'] - $time['start'], 4);
                    if ( $limit < self::$_CurrentTimeLimit )
                        continue;
                    $description = str_replace("\r", "", $description);
                    $description = preg_replace("~(\s+\n){1,}~si", "\n", $description);
                    $description = preg_replace('~[ ]{2,}~', ' ', $description);
                    if ( strpos($description, '#{SQL}') === 0 )
                        $description = str_replace("\n", "\n\t\t", $description);
                    $indent = '';
                    for ($i = 0; $i < $time['level']; $i++)
                    {
                        $indent .= "\t";
                    }
                    self::$_OutputApplication[] = $indent . '{' . $limit . '} ' . trim($description);
                }
                else
                    self::Set_Message($description . " timer error");
            }
            self::$_CurrentTime = [];
            self::$_OutputApplication[] = "#{System.Full} " . sprintf("%01.3f", microtime(1) - self::$_StartTime);
            self::$_OutputApplication[] = "#{MEMORY} " . memory_get_usage();
        }
        return self::$_OutputApplication;
    }

    /**
     * Save v fai`l
     *
     * @param string $variable statisticheskie danny`e
     * @param string $file_log imia fai`l-loga ('zero_application_error')
     */
    protected static function Save_File($variable, $file_log)
    {
        $path = ZERO_PATH_LOG . '/' . $file_log . '.log';
        $fp = fopen($path, 'a');
        fputs($fp, $variable);
        fclose($fp);
        chmod($path, 0666);
        return;
    }
}