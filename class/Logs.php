<?php

/**
 * Мониторинг и сниатие статистики работы приложения.
 *
 * Profilirovanny`e danny`e po prilozheniiam:
 * - Paulzovatel`skie soobshcheniia
 * - Oshibki programmirovaniia
 * - Iscliucheniia
 * - Tai`mery` vremeni vy`polneniia
 * - Zatrachennaia pamiat`
 * - Dei`stvii` pol`zovatelia
 *
 * @package General.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
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
    public static function Init($fileLog)
    {
        self::$_Message = [];
        self::$_StartTime = microtime(1);
        self::$_CurrentTime = [];
        self::$_FileLog = $fileLog;
    }

    /**
     * Получение полного времени выполнения на момент запроса этого метода
     *
     * @return string
     */
    public static function Get_FullTime()
    {
        return sprintf("%01.3f", microtime(1) - self::$_StartTime);
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     * @return mixed
     */
    public static function Set_Message_Error($value)
    {
        self::$_Message[] = [print_r($value, true), 'error'];
        if ( Zero_App::$Config->Log_Profile_Error )
        {
            self::Start(print_r($value, true));
            self::Stop(print_r($value, true));
        }
        return $value;
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    protected static function Set_Message_ErrorTrace($value)
    {
        self::$_Message[] = [print_r($value, true), 'errorTrace'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     * @return mixed
     */
    public static function Set_Message_Warninng($value)
    {
        self::$_Message[] = [print_r($value, true), 'warning'];
        if ( Zero_App::$Config->Log_Profile_Warning )
        {
            self::Start(print_r($value, true));
            self::Stop(print_r($value, true));
        }
        return $value;
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     * @return mixed
     */
    public static function Set_Message_Notice($value)
    {
        self::$_Message[] = [print_r($value, true), 'notice'];
        if ( Zero_App::$Config->Log_Profile_Notice )
        {
            self::Start(print_r($value, true));
            self::Stop(print_r($value, true));
        }
        return $value;
    }

    public static function Get_Message()
    {
        return self::$_Message;
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
     * Вывод отладочной информации в браузер длиа разработчиков
     *
     * @return string
     */
    public static function Output_Display()
    {
        $iterator_list = [];
        $iterator = Zero_Session::Get_Instance()->getIterator();
        while ( $iterator->valid() )
        {
            $iterator_list[$iterator->key()] = get_class($iterator->current());
            $iterator->next();
        }
        $View = new Zero_View(ucfirst(Zero_App::$Config->Site_DomainSub) . '_Debug_Info');
        $View->Add('Zero_Debug_Info');
        $View->Assign('output', self::Get_Usage_MemoryAndTime());
        $View->Assign('message', self::$_Message);
        $View->Assign('iterator_list', $iterator_list);
        return $View->Fetch();
    }

    /**
     * Vy`vod vsei` profilirovannoi` informatcii v log fai`ly`
     *
     */
    public static function Output_File()
    {
        //        self::$_FileLog = Zero_App::$Config->Site_DomainSub . '_' . self::$_FileLog;
        // Логируем работу приложения в целом
        if ( Zero_App::$Config->Log_Profile_Application )
        {
            $output = join("\n", self::Get_Usage_MemoryAndTime()) . "\n";
            self::File(self::$_FileLog . '.log', $output);
        }
        //
        if ( 0 < count(self::$_Message) )
        {
            $output = self::Get_Usage_MemoryAndTime()[0];
            $errors = [];
            $warnings = [];
            $notice = [];
            foreach (self::$_Message as $row)
            {
                if ( 'error' == $row[1] )
                    $errors[] = str_replace(["\r", "\t"], " ", $row[0]);
                else if ( 'warning' == $row[1] )
                    $warnings[] = str_replace(["\r", "\t"], " ", $row[0]);
                else if ( 'notice' == $row[1] )
                    $notice[] = str_replace(["\r", "\t"], " ", $row[0]);
            }
            // логирование ошибки в файл
            if ( Zero_App::$Config->Log_Profile_Error && 0 < count($errors) )
            {
                array_unshift($errors, str_replace(["\r", "\t"], " ", $output));
                $errors = preg_replace('![ ]{2,}!', ' ', join("\n", $errors));
                $errors = date('[d.m.Y H:i:s]') . "\n" . $errors;
                self::File(self::$_FileLog . '_errors.log', $errors);
            }
            // логирование предупреждений в файл
            if ( Zero_App::$Config->Log_Profile_Warning && 0 < count($warnings) )
            {
                array_unshift($warnings, str_replace(["\r", "\t"], " ", $output));
                $warnings = preg_replace('![ ]{2,}!', ' ', join("\n", $warnings));
                $warnings = date('[d.m.Y H:i:s]') . "\n" . $warnings;
                self::File(self::$_FileLog . '_warnings.log', $warnings);
            }
            // логирование предупреждений в файл
            if ( Zero_App::$Config->Log_Profile_Notice && 0 < count($notice) )
            {
                array_unshift($notice, str_replace(["\r", "\t"], " ", $output));
                $notice = preg_replace('![ ]{2,}!', ' ', join("\n", $notice));
                $notice = date('[d.m.Y H:i:s]') . "\n" . $notice;
                self::File(self::$_FileLog . '_notice.log', $notice);
            }
        }
        // логирование операций пользователиа в файл
        if ( Zero_App::$Config->Log_Profile_Action && Zero_App::MODE_CONSOLE != Zero_App::Get_Mode() && isset($_REQUEST['act']) && 'Action_Default' != $_REQUEST['act'] )
        {
            $act = date('[d.m.Y H:i:s]') . "\t";
            $act .= Zero_App::$Users->Login . "\t" . Zero_App::$Section->Controller . " -> " . $_REQUEST['act'] . "\t";
            $act .= ZERO_HTTP . $_SERVER['REQUEST_URI'];
            self::File(self::$_FileLog . '_action.log', $act);
        }
    }

    /**
     * Poluchenie ishodnogo kuska koda v oblasti oshibki fai`la
     *
     * @param string $file put` do fai`la
     * @param int $line stroka s oshibkoi`
     * @param int $range_file_error diapazon vy`vodimy`kh strok fai`la vokrug oshibochneoi` stroki
     * @return string
     */
    protected static function Get_SourceCode($file, $line, $range_file_error)
    {
        $file_line = explode('<br />', highlight_file($file, true));
        $offset = $line - $range_file_error;
        if ( $offset < 0 )
            $offset = 0;
        $length = isset($file_line[$line + $range_file_error]) ? $line + $range_file_error : count($file_line) - 1;
        $View = new Zero_View(ucfirst(Zero_App::$Config->Site_DomainSub) . '_Debug_SourceCode');
        $View->Add('Zero_Debug_SourceCode');
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
                self::$_OutputApplication = [date('[d.m.Y H:i:s]') . ' [' . $_SERVER['REQUEST_METHOD'] . '] ' . ZERO_HTTP . $_SERVER['REQUEST_URI']];
            else if ( Zero_App::Get_Mode() == Zero_App::MODE_CONSOLE && isset($_SERVER['argv'][1]) )
                self::$_OutputApplication = [date('[d.m.Y H:i:s]') . ' ' . $_SERVER['argv'][1]];
            // Sobiraem tai`mery` v kuchu
            foreach (self::$_CurrentTime as $description => $time)
            {
                $limit = -1;
                if ( isset($time['stop']) )
                {
                    $limit = round($time['stop'] - $time['start'], 4);
                }
                if ( $limit != -1 && $limit < self::$_CurrentTimeLimit )
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
            self::$_CurrentTime = [];
            self::$_OutputApplication[] = "#{System.Full} " . self::Get_FullTime();
            self::$_OutputApplication[] = "#{MEMORY} " . memory_get_usage();
        }
        return self::$_OutputApplication;
    }

    /**
     * Логирование в файл.
     *
     * Обциональное количество параметров после имени файла
     *
     * @param string $file_log имиа файл-лога
     * @return bool
     */
    public static function File($file_log)
    {
        $arr = func_get_args();
        $file_log = array_shift($arr);
        if ( 0 == count($arr) )
            return true;
        foreach ($arr as $val)
        {
            Zero_Helper_File::File_Save_After(ZERO_PATH_LOG . '/' . $file_log, $val);
        }
        return true;
    }

    /**
     * Трассировка данных исключения. (trace)
     *
     * @param Exception $exception
     */
    public static function Exception_Trace(Exception $exception)
    {
        $range_file_error = 10;
        $error = "#{ERROR_EXCEPTION} " . $exception->getMessage() . ' ' . $exception->getFile() . '(' . $exception->getLine() . ')';
        self::Set_Message_Error($error);
        if ( Zero_App::$Config->Log_Output_Display == true )
        {
            self::Set_Message_ErrorTrace(self::Get_SourceCode($exception->getFile(), $exception->getLine(), $range_file_error));
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
            self::Set_Message_Error($error);
            if ( Zero_App::$Config->Log_Output_Display == true )
            {
                self::Set_Message_ErrorTrace(self::Get_SourceCode($trace['file'], $trace['line'], $range_file_error));
            }
        }
    }
}
