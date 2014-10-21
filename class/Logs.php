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
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_Action($value)
    {
        self::$_Message[] = [print_r($value, true), 'action'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_Error($value)
    {
        self::$_Message[] = [print_r($value, true), 'error'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_ErrorTrace($value)
    {
        self::$_Message[] = [print_r($value, true), 'errorTrace'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_Warninng($value)
    {
        self::$_Message[] = [print_r($value, true), 'warning'];
    }

    /**
     * Инициализация входиащего системного сообщения.
     *
     * @param string $value Soobshchenie ob oshibke
     */
    public static function Set_Message_Notice($value)
    {
        self::$_Message[] = [print_r($value, true), 'notice'];
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
        $View = new Zero_View(ucfirst(Zero_App::$Config->Site_DomainSub) . '_Include_Debug');
        $View->Template_Add('Zero_Include_Debug');
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
        self::$_FileLog = Zero_App::$Config->Site_DomainSub . '_' . self::$_FileLog;
        // Logiruem rabotu prilozheniia v tcelom
        if ( Zero_App::$Config->Log_Profile_Application )
        {
            $output = self::Get_Usage_MemoryAndTime();
            $output = date('[d.m.Y H:i:s]') . "\n" . join("\n", $output) . "\n";
            self::Save_File($output, self::$_FileLog . '.log');
        }
        //
        if ( 0 < count(self::$_Message) )
        {
            $output = self::Get_Usage_MemoryAndTime()[0];
            $errors = [];
            $warnings = [];
            $notice = [];
            $action = [];
            foreach (self::$_Message as $row)
            {
                if ( 'error' == $row[1] )
                    //                    $errors[] = str_replace(["\r", "\t"], " ", var_export($row[0], true));
                    $errors[] = str_replace(["\r", "\t"], " ", $row[0]);
                else if ( 'warning' == $row[1] )
                    //                    $warnings[] = str_replace(["\r", "\t"], " ", var_export($row[0], true));
                    $warnings[] = str_replace(["\r", "\t"], " ", $row[0]);
                else if ( 'notice' == $row[1] )
                    //                    $warnings[] = str_replace(["\r", "\t"], " ", var_export($row[0], true));
                    $notice[] = str_replace(["\r", "\t"], " ", $row[0]);
                else if ( 'action' == $row[1] )
                    $action[] = $row[0];
            }
            // логирование ошибки в файл
            if ( Zero_App::$Config->Log_Profile_Error && 0 < count($errors) )
            {
                array_unshift($errors, str_replace(["\r", "\t"], " ", $output));
                $errors = preg_replace('![ ]{2,}!', ' ', join("\n", $errors));
                $errors = date('[d.m.Y H:i:s]') . "\n" . $errors;
                self::Save_File($errors, self::$_FileLog . '_errors.log');
            }
            // логирование предупреждений в файл
            if ( Zero_App::$Config->Log_Profile_Warning && 0 < count($warnings) )
            {
                array_unshift($warnings, str_replace(["\r", "\t"], " ", $output));
                $warnings = preg_replace('![ ]{2,}!', ' ', join("\n", $warnings));
                $warnings = date('[d.m.Y H:i:s]') . "\n" . $warnings;
                self::Save_File($warnings, self::$_FileLog . '_warnings.log');
            }
            // логирование предупреждений в файл
            if ( Zero_App::$Config->Log_Profile_Notice && 0 < count($notice) )
            {
                array_unshift($notice, str_replace(["\r", "\t"], " ", $output));
                $notice = preg_replace('![ ]{2,}!', ' ', join("\n", $notice));
                $notice = date('[d.m.Y H:i:s]') . "\n" . $notice;
                self::Save_File($notice, self::$_FileLog . '_notice.log');
            }
            // логирование операций пользователиа в файл
            if ( Zero_App::$Config->Log_Profile_Action && 0 < count($action) )
            {
                $act = date('[d.m.Y H:i:s]') . "\t";
                $act .= Zero_App::$Users->Login . "\t" . Zero_App::$Section->Controller . " -> " . join($action, ", ") . "\t";
                $act .= ZERO_HTTP . $_SERVER['REQUEST_URI'];
                self::Save_File($act, self::$_FileLog . '_action.log');
            }
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
    public static function Get_SourceCode($file, $line, $range_file_error)
    {
        $file_line = explode('<br />', highlight_file($file, true));
        $offset = $line - $range_file_error;
        if ( $offset < 0 )
            $offset = 0;
        $length = isset($file_line[$line + $range_file_error]) ? $line + $range_file_error : count($file_line) - 1;
        $View = new Zero_View(ucfirst(Zero_App::$Config->Site_DomainSub) . '_Include_SourceCode');
        $View->Template_Add('Zero_Include_SourceCode');
        $View->Assign('file_line', $file_line);
        $View->Assign('offset', $offset < 0 ? 0 : $offset);
        $View->Assign('length', $length);
        $View->Assign('line', $line);
        return $View->Fetch(true);
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
                self::$_OutputApplication = ["#{REQUEST_URI} " . ZERO_HTTP . $_SERVER['REQUEST_URI']];
            else if ( isset($_SERVER['argv'][1]) )
                self::$_OutputApplication = ["#{DEMON} " . $_SERVER['argv'][1] . ' ' . ZERO_HTTP];
            // Sobiraem tai`mery` v kuchu
            foreach (self::$_CurrentTime as $description => $time)
            {
                if ( !isset($time['stop']) )
                    $time['stop'] = microtime(true);
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
            self::$_CurrentTime = [];
            self::$_OutputApplication[] = "#{System.Full} " . sprintf("%01.3f", microtime(1) - self::$_StartTime);
            self::$_OutputApplication[] = "#{MEMORY} " . memory_get_usage();
        }
        return self::$_OutputApplication;
    }

    /**
     * Save v fai`l
     *
     * @param string $data statisticheskie danny`e
     * @param string $file_log imia fai`l-loga ('zero_application_error')
     * @return bool
     */
    public static function Save_File($data, $file_log)
    {
        return Zero_Lib_FileSystem::File_Save_After(ZERO_PATH_LOG . '/' . $file_log, $data);
    }
}