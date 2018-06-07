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
 * @package Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015-01-01
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
     * Инициализация логера
     *
     * @param string $path путь до файла лога
     */
    public static function Init($path)
    {
        self::$_Message = [];
        self::$_StartTime = microtime(1);
        self::$_CurrentTime = [];
        self::$_FileLog = $path;
    }

    /**
     * Переопределие файл лога
     *
     * @param string $fileLog файл лог
     */
    public static function Set_FileLog($fileLog = 'app')
    {
        $arr = explode('/', self::$_FileLog);
        array_pop($arr);
        self::$_FileLog = implode('/', $arr) . '/' . $fileLog;
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
     */
    public static function Set_Message_Error($value)
    {
        if ( !Zero_App::$Config->Log_Profile_Error )
            return;
        self::$_CurrentTime[] = [
            'datetime' => date("Y-m-d H:i:s"),
            'start' => 0,
            'level' => self::$_CurrentTimeLevel,
            'typ' => 'ERROR',
            'message' => print_r($value, true),
            'stop' => 0,
        ];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_ErrorTrace($value)
    {
        if ( !Zero_App::$Config->Log_Profile_Error )
            return;
        // self::$_Message[] = [print_r($value, true), 'errorTrace'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     */
    public static function Set_Message_Warning($value)
    {
        if ( !Zero_App::$Config->Log_Profile_Warning )
            return;
        self::$_CurrentTime[] = [
            'datetime' => date("Y-m-d H:i:s"),
            'start' => 0,
            'level' => self::$_CurrentTimeLevel,
            'typ' => 'WARNING',
            'message' => print_r($value, true),
            'stop' => 0,
        ];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     */
    public static function Set_Message_Info($value)
    {
        if ( !Zero_App::$Config->Log_Profile_Application )
            return;
        self::$_CurrentTime[] = [
            'datetime' => date("Y-m-d H:i:s"),
            'start' => 0,
            'level' => self::$_CurrentTimeLevel,
            'typ' => 'INFO',
            'message' => print_r($value, true),
            'stop' => 0,
        ];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param mixed $value Сообщение об ошибке
     */
    public static function Set_Message_Notice($value)
    {
        if ( !Zero_App::$Config->Log_Profile_Notice )
            return;
        self::$_CurrentTime[] = [
            'datetime' => date("Y-m-d H:i:s"),
            'start' => 0,
            'level' => self::$_CurrentTimeLevel,
            'typ' => 'NOTICE',
            'message' => print_r($value, true),
            'stop' => 0,
        ];
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
        if ( !Zero_App::$Config->Log_Profile_Application )
            return;
        $k = sha1($key);
        self::$_CurrentTime[$k]['datetime'] = date("Y-m-d H:i:s");
        self::$_CurrentTime[$k]['start'] = microtime(true);
        self::$_CurrentTime[$k]['level'] = self::$_CurrentTimeLevel;
        self::$_CurrentTime[$k]['typ'] = 'info';
        self::$_CurrentTime[$k]['message'] = $key;
        self::$_CurrentTimeLevel++;
    }

    /**
     * Ostanovka tai`mera po cliuchu
     *
     * @param string $key cliuch
     */
    public static function Stop($key)
    {
        if ( !Zero_App::$Config->Log_Profile_Application )
            return;
        $k = sha1($key);
        self::$_CurrentTime[$k]['stop'] = microtime(true);
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
        if ( isset($_SESSION['Session']) )
            foreach ($_SESSION['Session'] as $key => $val)
            {
                $iterator_list[$key]['name'] = get_class($val);
                $iterator_list[$key]['type'] = gettype($val);
            }
        if ( isset($_SESSION['Session']) )
            foreach ($_SESSION as $key => $val)
            {
                $iterator_list[$key]['name'] = '';
                $iterator_list[$key]['type'] = gettype($val);
            }
        unset($iterator_list['Session']);
        $View = new Zero_View('Zero_Debug_Info');
        $View->Assign('output', self::Get_Usage_MemoryAndTime());
        //        $View->Assign('message', self::$_Message);
        $View->Assign('iterator_list', $iterator_list);
        return $View->Fetch();
    }

    /**
     * Логируем работу приложения в целом
     *
     */
    public static function Output_File()
    {
        $output = self::Get_Usage_MemoryAndTime();
        //            $output = [str_replace(["\r", "\t"], " ", $output)];
        //        foreach (self::$_Message as $row)
        //        {
        //            if ( 'errorTrace' != $row[1] )
        //                $output[] = '[' . strtoupper($row[1]) . '] ' . str_replace(["\r", "\t"], " ", $row[0]);
        //        }
        //        $output = preg_replace('![ ]{2,}!', ' ', join("\n", $output));
        $output = join("\n", $output);
        Helper_File::File_Save_After(self::$_FileLog . '.log', $output);

        // логирование операций пользователиа в файл
        if ( Zero_App::$Config->Log_Profile_Action && isset($_REQUEST['act']) && 'Action_Default' != $_REQUEST['act'] )
            if ( is_object(Zero_App::$Users) && is_object(Zero_App::$Section) )
            {
                $act = date('[d.m.Y H:i:s]') . "\t";
                $act .= Zero_App::$Users->Login . "\t";
                if ( Zero_App::$Controller->Controller )
                    $act .= Zero_App::$Controller->Controller . " -> " . $_REQUEST['act'] . "\t";
                $act .= ZERO_HTTP . $_SERVER['REQUEST_URI'];
                Helper_File::File_Save_After(self::$_FileLog . '_action.log', $act);
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
    public static function Get_SourceCode($file, $line, $range_file_error)
    {
        if ( !$file )
            return '';
        $file_line = explode('<br />', highlight_file($file, true));
        $offset = $line - $range_file_error;
        if ( $offset < 0 )
            $offset = 0;
        $length = isset($file_line[$line + $range_file_error]) ? $line + $range_file_error : count($file_line) - 1;
        $View = new Zero_View('Zero_Debug_SourceCode');
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
                self::$_OutputApplication = ['START [' . $_SERVER['REQUEST_METHOD'] . '] ' . ZERO_HTTP . $_SERVER['REQUEST_URI']];
            else if ( isset($_SERVER['argv'][1]) )
                self::$_OutputApplication = ['START ' . $_SERVER['argv'][1]];
            else
                self::$_OutputApplication = ['START'];
            // Sobiraem tai`mery` v kuchu
            foreach (self::$_CurrentTime as $time)
            {
                $limit = -1;
                $description = $time['message'];
                if ( isset($time['stop']) )
                {
                    $limit = round($time['stop'] - $time['start'], 4);
                }
                $description = str_replace(["\r", "\t"], " ", $description);
                $description = preg_replace("~(\s+\n){1,}~si", "\n", $description);
                $description = preg_replace('~[ ]{2,}~', ' ', $description);
                if ( strpos($description, '#{SQL}') === 0 )
                    $description = str_replace("\n", "\n\t\t", $description);
                $indent = '';
                for ($i = 0; $i < $time['level']; $i++)
                {
                    $indent .= "\t";
                }
                self::$_OutputApplication[] = "[" . strtoupper($time['typ']) . "] [{$time['datetime']}]" . $indent . ' {' . $limit . '} ' . trim($description);
            }
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
     * @deprecated Custom
     */
    public static function File($file_log)
    {
        $arr = func_get_args();
        $file_log = array_shift($arr) . '.log';
        if ( 0 == count($arr) )
            return true;
        foreach ($arr as $val)
        {
            Helper_File::File_Save_After(self::$_FileLog . '_' . $file_log, print_r($val, true));
        }
        return true;
    }

    /**
     * Логирование в файл.
     *
     * @param string $file_log имиа файл-лога
     * @param array|string $data данные
     * @return bool
     */
    public static function Custom($file_log, $data)
    {
        Helper_File::File_Save_After(ZERO_PATH_LOG . '/' . $file_log . '.log', print_r($data, true));
        return true;
    }

    /**
     * Логирование в файл.
     *
     * Обциональное количество параметров после имени файла
     *
     * @param string $file_log имиа файл-лога
     * @return bool
     * @deprecated Custom_DateTime
     */
    public static function File_DateTime($file_log)
    {
        $arr = func_get_args();
        $file_log = array_shift($arr) . '.log';
        if ( 0 == count($arr) )
            return true;
        foreach ($arr as $val)
        {
            Helper_File::File_Save_After(self::$_FileLog . '_' . $file_log, date('[d.m.Y H:i:s]'));
            Helper_File::File_Save_After(self::$_FileLog . '_' . $file_log, print_r($val, true));
        }
        return true;
    }

    /**
     * Логирование в файл.
     *
     * @param string $file_log имя файл-лога
     * @param array|string $data данные
     * @return bool
     */
    public static function Custom_DateTime($file_log, $data)
    {
        Helper_File::File_Save_After(ZERO_PATH_LOG . '/' . $file_log . '.log', date('[d.m.Y H:i:s]'));
        Helper_File::File_Save_After(ZERO_PATH_LOG . '/' . $file_log . '.log', print_r($data, true));
        return true;
    }
}
