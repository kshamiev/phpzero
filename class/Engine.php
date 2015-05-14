<?php

/**
 * Sozdanie moedelei` i modulia po BD.
 *
 * Анализ струтуры БД и создание моделей на ее основе
 * Формы и типы  свойств (определиаемые при анализе):
 * <ol>
 * <li> Number    - (text) Chislo
 * <li> Text      - (text) Stroka teksta
 * <li> Select    - (select) Perechislenie
 * <li> Radio     - (radio) Perechislenie s umolchaniem (opredeliaet po ne nulevomu znacheniiu polia Enum)
 * <li> Checkbox  - (checkbox) Mnozhestvo
 * <li> Textarea  - (textarea) Tekst
 * <li> Date      - (text) Data
 * <li> Time      - (text) Vremia
 * <li> DateTime  - (text) Data i vremia
 * <li> Link      - (select) Svoi`stvo sviazi s drugim ob``ektom
 * </ol>
 * Формы и типы свойств (не определиаемые при анализе):
 * <ol>
 * <li> Hidden    - (hidden) Skry`toe pole
 * <li> ReadOnly  - (-) Tol`ko dlia chteniia
 * <li> Password  - (password) Stroka parol`
 * <li> File      - (file) Ssy`lka na fai`l (py`taetsia opredelit` po imeni polia)
 * <li> FileB    - (file) Binarny`e danny`e fai`la (py`taetsia opredelit` po imeni polia i tipu)
 * <li> Img       - (file) Ssy`lka na kartinku (py`taetsia opredelit` po imeni polia)
 * <li> ImgB     - (file) Binarny`e danny`e kartinki (py`taetsia opredelit` po imeni polia i tipu)
 * <li> Content   - (textarea) Bol`shei` tekst (vizevik) (py`taetsia opredelit` po imeni polia)
 * <li> LinkMore  - (select) Svoi`stvo sviazi s drugim ob``ektom (mnogochislenny`m - ajax)
 * </ol>
 *
 * @package Zero.Developer
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Engine
{

    /**
     * Путь до гереруемого модуля
     *
     * @var string
     */
    protected static $path = '';

    /**
     * Validatciia BD
     *
     * Spisok sootvetsvii` tipam polei` v BD i ikh obrabotchikov v php
     * Nizkourovnevy`e metody` obertki dlia perevoda danny`kh v standart khraneniia v BD i bezopasnosti
     *
     * @var array
     */
    protected static $PropValidatorDB = [
        //  stroki i teksty`
        'char' => 'T',
        'varchar' => 'T',
        'tinytext' => 'T',
        'text' => 'T',
        'mediumtext' => 'T',
        'longtext' => 'T',
        //  perechislenie
        'enum' => 'E',
        //  mnozhestva
        'set' => 'S',
        //  tcely`e chisla
        'bigint' => 'I',
        'mediumint' => 'I',
        'int' => 'I',
        'smallint' => 'I',
        'tinyint' => 'I',
        //  chisla s plavaiushchei` tochkoi`
        'float' => 'F',
        'double' => 'F',
        'decimal' => 'F',
        'real' => 'F',
        //  data i vremia
        'timestamp' => 'D',
        'datetime' => 'D',
        'date' => 'D',
        'time' => 'D',
        //  binarny`e danny`e
        'blob' => 'B',
        'longblob' => 'B',
        'mediumblob' => 'B',
        'tinyblob' => 'B'
    ];

    /**
     * Validatciia BD
     *
     * Spisok sootvetsvii` tipam polei` v BD i ikh pol`zovatel`skogo predstavleniia
     *
     * @var array
     */
    protected static $PropValidatorName = [
        //  строки и тексты
        'char' => 'Строка',
        'varchar' => 'Строка',
        'tinytext' => 'Строка',
        'text' => 'Текст',
        'mediumtext' => 'Текст',
        'longtext' => 'Текст',
        //  перечисление
        'enum' => 'Selekt',
        //  множества
        'set' => 'Chekboks',
        //  целые числа
        'bigint' => 'Целое число',
        'mediumint' => 'Целое число',
        'int' => 'Целое число',
        'smallint' => 'Целое число',
        'tinyint' => 'Целое число',
        //  числа с плавающей точкой
        'float' => 'Дробное число',
        'double' => 'Дробное число',
        'decimal' => 'Дробное число',
        'real' => 'Дробное число',
        //  дата и времиа
        'timestamp' => 'Дата и времиа',
        'datetime' => 'Дата и времиа',
        'date' => 'Дата',
        'time' => 'Времиа',
        //  бинарные данные
        'blob' => 'Бинарные данные',
        'longblob' => 'Бинарные данные',
        'mediumblob' => 'Бинарные данные',
        'tinyblob' => 'Бинарные данные'
    ];

    /**
     * Validatciia form po tipam polei` v BD
     *
     * Spisok sootvetsvii` polei` v BD i ikh formoi` otobrazheniia.
     *
     * @var array
     */
    protected static $PropValidatorForm = [
        //  stroki i teksty`
        'char' => 'Text',
        'varchar' => 'Text',
        'tinytext' => 'Text',
        'text' => 'Textarea',
        'mediumtext' => 'Textarea',
        'longtext' => 'Textarea',
        //  perechislenie
        'enum' => 'Select',
        //  mnozhestva
        'set' => 'Checkbox',
        //  tcely`e chisla
        'bigint' => 'Number',
        'mediumint' => 'Number',
        'int' => 'Number',
        'smallint' => 'Number',
        'tinyint' => 'Check',
        //  chisla s plavaiushchei` tochkoi`
        'float' => 'Number',
        'double' => 'Number',
        'decimal' => 'Number',
        'real' => 'Number',
        //  data i vremia
        'timestamp' => 'DateTime',
        'datetime' => 'DateTime',
        'date' => 'Date',
        'time' => 'Time',
        //  binarny`e danny`e
        'blob' => 'FileDB',
        'longblob' => 'FileDB',
        'mediumblob' => 'FileDB',
        'tinyblob' => 'FileDB'
    ];

    /**
     * Svoi`stva s danny`mi formami ispol`zuiutsia v fil`trakh
     *
     * @var array
     */
    protected static $PropValidatorFilter = [
        'Link',
        'LinkMore',
        'Select',
        'Radio',
        'DateTime',
        'Checkbox',
    ];

    /**
     * Svoi`stva s danny`mi formami uchavstvuiut v poiske
     *
     * @var array
     */
    protected static $PropValidatorSearch = [
        'Number',
        'Text',
        'Textarea',
        'Content',
    ];

    /**
     * Svoi`stva s danny`mi formami uchavstvuiut v sortirovke
     *
     * @var array
     */
    protected static $PropValidatorSort = [
        'Number',
        'Text',
    ];

    /**
     * Служебная переменная для формирования переводов
     *
     * @var array
     */
    protected $I18n = [];

    /**
     * Pervichny`i` analiz istonchika i poluchenie ruzl`tata
     *
     * - Nazvanie
     * - Forma predstvaleniia
     * - Metod obrabotchik BD
     * - Paulzovatel`skoe poredelenie
     *
     * @param string $source_name istochnik
     * @return array
     */
    public static function Get_Source_Info($source_name)
    {
        $result = [];
        foreach (Zero_DB::Select_Array("SHOW FULL COLUMNS FROM `{$source_name}`") as $row)
        {
            $type = explode('(', $row['Type']);
            $type = array_shift($type);
            $type = explode(' ', $type);
            $type = array_shift($type);
            if ( !isset(self::$PropValidatorDB[$type]) || !isset(self::$PropValidatorForm[$type]) )
            {
                Zero_Logs::Set_Message_Error('не определенный тип ' . $row['Type'] . ' поля ' . $row['Field'] . ' в таблице ' . $source_name);
                continue;
            }
            //  Определение базовых настроек полей
            $result[$row['Field']]['TypeFull'] = $row['Type'];
            $result[$row['Field']]['Type'] = $type;
            $result[$row['Field']]['DB'] = self::$PropValidatorDB[$type];
            $result[$row['Field']]['IsNull'] = $row['Null'];
            $result[$row['Field']]['Comment'] = $row['Comment'];
            $result[$row['Field']]['CommentType'] = self::$PropValidatorName[$type];
            //  Определеине значения по умолчанию
            $result[$row['Field']]['Default'] = $row['Default'];
            if ( 'D' == $result[$row['Field']]['DB'] && 'NO' == $row['Null'] )
                $result[$row['Field']]['Default'] = 'NOW';
            //  Определеине формы
            $result[$row['Field']]['Form'] = self::$PropValidatorForm[$type];
            if ( 'enum' == $type && 'NO' == $row['Null'] )
                $result[$row['Field']]['Form'] = 'Radio';
            else if ( 'ID' == $row['Field'] )
                $result[$row['Field']]['Form'] = '';
            //  Пытаемся определить форму свойства по его имени
            if ( substr($row['Field'], 0, strlen('_ID')) == '_ID' || substr($row['Field'], -strlen('_ID')) == '_ID' )
                $result[$row['Field']]['Form'] = 'Link';
            if ( substr($row['Field'], 0, strlen('Content')) == 'Content' || substr($row['Field'], -strlen('Content')) == 'Content' )
                $result[$row['Field']]['Form'] = 'Content';
            if ( substr($row['Field'], 0, strlen('File')) == 'File' || substr($row['Field'], -strlen('File')) == 'File' )
            {
                if ( 'B' == $result[$row['Field']]['DB'] )
                    $result[$row['Field']]['Form'] = 'FileB';
                else
                    $result[$row['Field']]['Form'] = 'File';
            }
            if ( substr($row['Field'], 0, strlen('Img')) == 'Img' || substr($row['Field'], -strlen('Img')) == 'Img' )
            {
                if ( 'B' == $result[$row['Field']]['DB'] )
                    $result[$row['Field']]['Form'] = 'ImgB';
                else
                    $result[$row['Field']]['Form'] = 'Img';
            }
        }
        return $result;
    }

    /**
     * Sozdanie/Izmenenie modelei`.
     *
     * Chitaet gruppu tablitc ili tcelevuiu tablitcu i sozdaet dlia nikh modeli.
     * Esli model` sushchestvuet proizvodit ee izmenenie:
     * - Initcializatciia konfiguratcii modeli
     * - Initcializatciia konfiguratcii svoi`stv
     * - Initcializatciia konfiguratcii sviazei` modeli
     * - Initcializatciia internatcionalizatcii
     *
     * @param string $connectDb имя коннекта к БД
     * @param boolean $flag_grid konfiguratciia modeli
     * @param boolean $flag_edit konfiguratciia svoi`stv modeli
     * @return boolean
     */
    public function Factory_Modules_DB($connectDb, $flag_grid = true, $flag_edit = true)
    {
        /**
         * Modeli i Kontrollery`
         */
        $sql = "SHOW TABLE STATUS WHERE `Name` NOT LIKE '\_%'";
        $table_list = Zero_DB::Select_Array($sql, $connectDb);
        if ( 0 == count($table_list) )
            return false;
        /**
         * Струтура папок
         */
        self::$path = ZERO_PATH_SITE . '/engine/' . $connectDb;
        $dir = self::$path;
        if ( !is_dir($dir) )
            mkdir($dir, 0777, true);
        //        $dir1 = $dir . '/assets';
        //        if ( !is_dir($dir1) )
        //            mkdir($dir1);
        $dir1 = $dir . '/class';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        //        $dir1 = $dir . '/config';
        //        if ( !is_dir($dir1) )
        //            mkdir($dir1);
        $dir1 = $dir . '/i18n';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        //        $dir1 = $dir . '/view';
        //        if ( !is_dir($dir1) )
        //            mkdir($dir1);
        // Модель и контроллеры
        $this->I18n = [];
        foreach ($table_list as $row)
        {
            $path_pattern = ZERO_PATH_ZERO . '/data/Tpl_Model.php';
            $path_model = $dir . '/class/' . $row['Name'] . '.php';
            $class = file_get_contents($path_pattern);
            $class = str_replace('<Comment>', $row['Name'], $class);
            $class = str_replace('<Subpackage>', $row['Name'], $class);
            $class = str_replace('<Date>', date('Y.m.d'), $class);
            $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
            Zero_Helper_File::File_Save($path_model, $class);
            //  Konfiguratciia modeli v tcelom
            //            $this->Config_Model($path_model, $row['Name']);
            //  Konfiguratciia svoi`stv motceli
            $this->Config_Model_Prop($path_model, $row['Name']);
            //  Konfiguratciia sviazei` motceli (mnogie ko mnogim)
            $this->Config_Model_Link($path_model, $row['Name']);
            //  Internatcionalizatciia
            $this->Config_Model_I18n($path_model, $row['Name']);
            //  Kontroller spiska
            $path_target = substr($path_model, 0, -4) . '/Grid.php';
            if ( $flag_grid )
            {
                //          echo 'CREATE CONTROLLER ' . $path_target . '<br>';
                $path_pattern = ZERO_PATH_ZERO . '/data/Tpl_Controller_Grid.php';
                $class = file_get_contents($path_pattern);
                $class = str_replace('<Comment>', 'Контроллер просмотра списка объектов', $class);
                $class = str_replace('<Subpackage>', $row['Name'], $class);
                $class = str_replace('<Date>', date('Y.m.d'), $class);
                $class = str_replace('Zero_Controller_Grid', $row['Name'] . '_Grid', $class);
                $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
                Zero_Helper_File::File_Save($path_target, $class);
            }
            //  Kontroller redaktirovaniia
            $path_target = substr($path_model, 0, -4) . '/Edit.php';
            if ( $flag_edit )
            {
                $path_pattern = ZERO_PATH_ZERO . '/data/Tpl_Controller_Edit.php';
                $class = file_get_contents($path_pattern);
                $class = str_replace('<Comment>', 'Контроллер изменения объекта', $class);
                $class = str_replace('<Subpackage>', $row['Name'], $class);
                $class = str_replace('<Date>', date('Y.m.d'), $class);
                $class = str_replace('Zero_Controller_Edit', $row['Name'] . '_Edit', $class);
                $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
                Zero_Helper_File::File_Save($path_target, $class);
            }
        }
        // Переводы
        $str = '';
        ksort($this->I18n);
        foreach ($this->I18n as $key => $val)
        {
            $str .= "'{$key}' => {$val},\n";
        }
        $str = substr(trim($str), 0, -1);
        $path1 = ZERO_PATH_ZERO . '/data/Tpl_I18n.php';
        foreach (array_keys(Zero_App::$Config->Language) as $lang)
        {
            $path2 = self::$path . '/i18n/' . $lang . '/Model.php';
            //        echo 'CONFIG I18N MODEL ' . $path2 . '<br>';
            $file_data = file_get_contents($path1);
            $file_data = str_replace("'<PROPERTY>'", $str, $file_data);
            Zero_Helper_File::File_Save($path2, $file_data);
        }
        return true;
    }

    /**
     * Konfiguratciia tipa ob``ekta
     *
     * Analiz tablitcy`. Opredeleniee ee signatury`.
     * Save ili obnovlenie v BD i sokhranenie v fai`lovoi` sisteme.
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_Model($path_model, $Table)
    {
        $config = [];
        //  proverka realizatcii sinkhronnoi` mul`tiiazy`chnosti ob``ekta
        $sql = "SHOW TABLE STATUS WHERE `Name` = '{$Table}Language';";
        $row = Zero_DB::Select_Row($sql);
        $config['Language'] = 0;
        if ( 0 < count($row) )
            $config['Language'] = 1;
        $str = "\n\t\t\t";
        foreach ($config as $key => $value)
        {
            $str .= "'" . $key . "' => '" . $value . "', ";
        }
        $str = substr($str, 0, -2);
        $class = file_get_contents($path_model);
        preg_match('~/\*BEG_CONFIG_MODEL\*/(.*?)/\*END_CONFIG_MODEL\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG MODEL ' . $path . '<br>';
            $class = preg_replace('~/\*BEG_CONFIG_MODEL\*/(.*?)/\*END_CONFIG_MODEL\*/~si', "{$str}\n", $class);
            file_put_contents($path_model, $class);
        }
    }

    /**
     * Konfiguratcii svoi`stv tipa ob``ekta
     *
     * Poluchneie i analiz stolbtcov v tablitcakh.
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_Model_Prop($path_model, $Table)
    {
        /**
         * Analiz i initcializatciia svoi`stv na osnove polei` v BD
         */
        $config_filter = [];
        $config_grid = ['ID'];
        $config = self::Get_Source_Info($Table);
        foreach ($config as $Prop => $row)
        {
            //  Opredeleine fil`trov
            $config_filter[$Prop]['Visible'] = "true";
            $config_filter[$Prop]['AR'] = "true";
            //  Opredelenie grida
            if ( 'Text' == $config[$Prop]['Form'] )
            {
                $config_grid[] = $Prop;
            }
        }
        //  Bazovaia konfiguratciia
        $str_props = "";
        $str_property = "";
        foreach ($config as $prop => $row)
        {
            $str_props .= "\t\t\t'" . $prop . "' => [\n";
            $str_props .= "\t\t\t\t'AliasDB' => 'z." . $prop . "',\n";
            $str_props .= "\t\t\t\t'DB' => '" . $row['DB'] . "',\n";
            $str_props .= "\t\t\t\t'IsNull' => '" . $row['IsNull'] . "',\n";
            $str_props .= "\t\t\t\t'Default' => '" . $row['Default'] . "',\n";
            $str_props .= "\t\t\t\t'Form' => '" . $row['Form'] . "',\n";
            //            $str_props = substr($str_props, 0, -2);
            $str_props .= "\t\t\t],\n";
            //
            if ( 'ID' == $prop )
            {
                continue;
            }

            if ( 'I' == $row['DB'] )
            {
                $str_property .= " * @property integer \${$prop}\n";
            }
            else if ( 'F' == $row['DB'] )
            {
                $str_property .= " * @property float \${$prop}\n";
            }
            else if ( 'S' == $row['DB'] )
            {
                $str_property .= " * @property array \${$prop}\n";
            }
            else if ( 'B' == $row['DB'] )
            {
                $str_property .= " * @property source \${$prop}\n";
            }
            else
            {
                $str_property .= " * @property string \${$prop}\n";
            }
        }
        $str_props = substr(trim($str_props), 0, -1);
        $str_property = substr(trim($str_property), 0, -1);

        /**
         * Faktoring / Refaktoring modeli po poluchennoi` konfiguratcii
         */
        $class = file_get_contents($path_model);
        //  Bazovaia konfiguratciia svoi`stv
        preg_match('~/\*BEG_CONFIG_PROP\*/(.*?)/\*END_CONFIG_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP ' . $path . '<br>';
            $class = preg_replace('~[*] <BEG_CONFIG_PROPERTY>(.*?)<END_CONFIG_PROPERTY>~si', "{$str_property}", $class);
            $class = preg_replace('~/\*BEG_CONFIG_PROP\*/(.*?)/\*END_CONFIG_PROP\*/~si', "{$str_props}\n", $class);
        }
        //  Konfiguratciia fil`tra
        preg_match('~/\*BEG_CONFIG_FILTER_PROP\*/(.*?)/\*END_CONFIG_FILTER_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP FILTER' . $path . '<br>';
            $str_props = "";
            foreach ($config_filter as $prop => $row)
            {
                $str_props .= "\t\t\t'" . $prop . "' => [";
                foreach ($row as $key => $value)
                {
                    $str_props .= "'" . $key . "' => " . $value . ", ";
                }
                $str_props = substr($str_props, 0, -2);
                $str_props .= "],\n";
            }
            $str_props = substr(trim($str_props), 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_FILTER_PROP\*/(.*?)/\*END_CONFIG_FILTER_PROP\*/~si', "{$str_props}\n", $class);
        }
        //  Konfiguratciia grida
        preg_match('~/\*BEG_CONFIG_GRID_PROP\*/(.*?)/\*END_CONFIG_GRID_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP GRID' . $path . '<br>';
            $str_props = "";
            foreach ($config_grid as $prop)
            {
                $str_props .= "\t\t\t'" . $prop . "' => [],\n";
            }
            $str_props = substr(trim($str_props), 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_GRID_PROP\*/(.*?)/\*END_CONFIG_GRID_PROP\*/~si', "{$str_props}\n", $class);
        }
        //  Konfiguratciia formy`
        preg_match('~/\*BEG_CONFIG_FORM_PROP\*/(.*?)/\*END_CONFIG_FORM_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP FORM ' . $path . '<br>';
            $str_props = "";
            foreach ($config as $prop => $row)
            {
                $str_props .= "\t\t\t'" . $prop . "' => [],\n";
                //                $str_props .= "\t\t\t'" . $prop . "' => array(";
                //                $str_props .= "'Form' => '" . $row['Form'] . "', ";
                //                $str_props .= "'IsNull' => '" . $row['IsNull'] . "', ";
                //                $str_props = substr($str_props, 0, -2);
                //                $str_props .= "),\n";
            }
            $str_props = substr(trim($str_props), 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_FORM_PROP\*/(.*?)/\*END_CONFIG_FORM_PROP\*/~si', "{$str_props}\n", $class);
        }
        file_put_contents($path_model, $class);
    }

    /**
     * Konfiguratciia roditel`skikh sviazei` tipa ob``ekta
     *
     * Poluchneie i analiz sviazei` mezhdu tablitcami.
     * Save v fai`lovoi` sisteme.
     * Tol`ko sviazi mnogie ko mnogim!
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_Model_Link($path_model, $Table)
    {
        $config = [];
        $sql = "SHOW TABLE STATUS WHERE `Name` LIKE '{$Table}_%' OR `Name` LIKE '%_{$Table}'";
        $rows = Zero_DB::Select_List($sql);
        foreach ($rows as $tbl)
        {
            //  zashchita ot skry`ty`kh i nekorretkny`kh tablitc
            if ( 2 != count(explode('_', $tbl)) )
                continue;
            //
            $sql = "SHOW FULL COLUMNS FROM `{$tbl}` WHERE `Field` LIKE '{$Table}_%ID';";
            $row = Zero_DB::Select_Row($sql);
            $PropThis = isset($row['Field']) ? $row['Field'] : '';
            $sql = "SHOW FULL COLUMNS FROM `{$tbl}` WHERE `Field` NOT LIKE '{$Table}_%ID';";
            $row = Zero_DB::Select_Row($sql);
            $PropTarget = isset($row['Field']) ? $row['Field'] : '';
            $TableTarget = zero_relation($PropTarget);
            $config[$TableTarget]['table_link'] = $tbl;
            $config[$TableTarget]['prop_this'] = $PropThis;
            $config[$TableTarget]['prop_target'] = $PropTarget;
        }
        //
        $str = "";
        foreach ($config as $prop => $row)
        {
            $str .= "\t\t\t'" . $prop . "' => array(";
            foreach ($row as $key => $value)
            {
                $str .= "'" . $key . "' => '" . $value . "', ";
            }
            $str = substr($str, 0, -2);
            $str .= "),\n";
        }
        $str = substr($str, 0, -2);
        $class = file_get_contents($path_model);
        preg_match('~/\*BEG_CONFIG_LINK\*/(.*?)/\*END_CONFIG_LINK\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG LINK ' . $path . '<br>';
            $class = preg_replace('~/\*BEG_CONFIG_LINK\*/(.*?)/\*END_CONFIG_LINK\*/~si', "{$str}\n", $class);
            file_put_contents($path_model, $class);
        }
    }

    /**
     * Internatcionalizatciia
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_Model_I18n($path_model, $Table)
    {
        foreach (Zero_DB::Select_Array("SHOW FULL COLUMNS FROM {$Table};") as $row)
        {
            $Type = explode('(', $row['Type']);
            $Type = array_shift($Type);
            $this->I18n["{$row['Field']}"] = "'" . $row['Comment'] . "'";
            //  Перечисления и множества
            if ( 'enum' == $Type || 'set' == $Type )
            {
                $list = explode("','", substr($row['Type'], strpos($row['Type'], "'") + 1, -2));
                $str = '[';
                foreach ($list as $val)
                {
                    $str .= "'" . $val . "' => '" . $val . "',";
                    //$config["model prop {$row['Field']} option {$val}"] = $val;
                }
                $this->I18n["{$row['Field']} options"] = substr($str, 0, -1) . "]";
            }
        }
    }
}
