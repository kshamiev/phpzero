<?php

/**
 * Component. Sozdanie moedelei` i modulia po BD.
 *
 * Analiz strutury` BD i sozdanie modelei` na ee osnove
 * Formy` i tipy`  svoi`stv (opredeliaemy`e pri analize):
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
 * Formy` i tipy` svoi`stv (ne opredeliaemy`e pri analize):
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
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Engine
{
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
        //  stroki i teksty`
        'char' => 'Stroka',
        'varchar' => 'Stroka',
        'tinytext' => 'Stroka',
        'text' => 'Tekst',
        'mediumtext' => 'Tekst',
        'longtext' => 'Tekst',
        //  perechislenie
        'enum' => 'Selekt',
        //  mnozhestva
        'set' => 'Chekboks',
        //  tcely`e chisla
        'bigint' => 'TCeloe chislo',
        'mediumint' => 'TCeloe chislo',
        'int' => 'TCeloe chislo',
        'smallint' => 'TCeloe chislo',
        'tinyint' => 'TCeloe chislo',
        //  chisla s plavaiushchei` tochkoi`
        'float' => 'Drobnoe chislo',
        'double' => 'Drobnoe chislo',
        'decimal' => 'Drobnoe chislo',
        'real' => 'Drobnoe chislo',
        //  data i vremia
        'timestamp' => 'Data i vremia',
        'datetime' => 'Data i vremia',
        'date' => 'Data',
        'time' => 'Vremia',
        //  binarny`e danny`e
        'blob' => 'Binarny`e danny`e',
        'longblob' => 'Binarny`e danny`e',
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
        'tinyint' => 'Number',
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
     * Poluchenie modulei` na sonove BD
     *
     * @return array
     */
    public static function Get_Modules_DB()
    {
        $result = [];
        foreach (Zero_DB::Select_List("SHOW TABLES;") as $table)
        {
            $arr = explode('_', $table);
            $result[] = array_shift($arr);
        }
        $result = array_flip($result);
        return array_flip($result);
    }

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
            $arr = explode('(', $row['Type']);
            $type = array_shift($arr);
            if ( !isset(self::$PropValidatorDB[$type]) || !isset(self::$PropValidatorForm[$type]) )
            {
                Zero_Logs::Set_Message_Error('не определенный тип ' . $row['Type'] . ' поля ' . $row['Field'] . ' в таблице ' . $source_name);
                continue;
            }
            //  Opredelenie bazovy`kh nastroek polei`
            $result[$row['Field']]['TypeFull'] = $row['Type'];
            $result[$row['Field']]['Type'] = $type;
            $result[$row['Field']]['DB'] = self::$PropValidatorDB[$type];
            $result[$row['Field']]['IsNull'] = $row['Null'];
            $result[$row['Field']]['Comment'] = $row['Comment'];
            $result[$row['Field']]['CommentType'] = self::$PropValidatorName[$type];
            //  Opredeleine znacheniia po umolchaniiu
            $result[$row['Field']]['Default'] = $row['Default'];
            if ( 'D' == $result[$row['Field']]['DB'] && 'NO' == $row['Null'] )
                $result[$row['Field']]['Default'] = 'NOW';
            //  Opredeleine formy`
            $result[$row['Field']]['Form'] = self::$PropValidatorForm[$type];
            if ( 'enum' == $type && 'NO' == $row['Null'] )
                $result[$row['Field']]['Form'] = 'Radio';
            else if ( 'ID' == $row['Field'] )
                $result[$row['Field']]['Form'] = '';
            //  Py`taemsia opredelit` formu svoi`stva po ego imeni
            if ( substr($row['Field'], 0, strlen('_ID')) == '_ID' || substr($row['Field'], -strlen('_ID')) == '_ID' )
                $result[$row['Field']]['Form'] = 'Link';
            if ( substr($row['Field'], 0, strlen('Content')) == 'Content' || substr($row['Field'], -strlen('Content')) == 'Content' )
                $result[$row['Field']]['Form'] = 'Content';
            if ( substr($row['Field'], 0, strlen('File')) == 'File' || substr($row['Field'], -strlen('File')) == 'File' )
            {
                if ( 'B' == $result[$row['Field']]['DB'] )
                    $result[$row['Field']]['Form'] = 'FileData';
                else
                    $result[$row['Field']]['Form'] = 'File';
            }
            if ( substr($row['Field'], 0, strlen('Img')) == 'Img' || substr($row['Field'], -strlen('Img')) == 'Img' )
            {
                if ( 'B' == $result[$row['Field']]['DB'] )
                    $result[$row['Field']]['Form'] = 'ImgData';
                else
                    $result[$row['Field']]['Form'] = 'Img';
            }
        }
        return $result;
    }

    /**
     * Poluchneie puti do classa.
     *
     * Dlia prilozheniia
     *
     * @param string $class_name imia classa (modeli, kontrollera)
     * @return string
     */
    protected static function Get_Path_Class($class_name)
    {
        $class_name = str_replace('_', '/', $class_name);
        $str_pos = strpos($class_name, '/');
        return ZERO_PATH_APPLICATION . '/' . strtolower(substr($class_name, 0, $str_pos)) . '/class' . substr($class_name, $str_pos) . '.php';
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
     * @param string $module tcelevaia tablitca libo paket tablitc (modelei`)
     * @param boolean $flag_grid konfiguratciia modeli
     * @param boolean $flag_edit konfiguratciia svoi`stv modeli
     * @return boolean
     */
    public function Factory_Modules_DB($module, $flag_grid = true, $flag_edit = true)
    {
        /**
         * Modeli i Kontrollery`
         */
        $sql = "SHOW TABLE STATUS WHERE `Name` LIKE '{$module}\_%' AND `Name` NOT LIKE '%\_2\_%'";
        $table_list = Zero_DB::Select_Array($sql);
        if ( 0 == count($table_list) )
            return false;
        /**
         * Strutura papok
         */
        $dir = ZERO_PATH_APPLICATION . '/' . strtolower($module);
        if ( !is_dir($dir) )
            mkdir($dir);
        $dir1 = $dir . '/assets';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/class';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/component';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/config';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/i18n';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/library';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        $dir1 = $dir . '/view';
        if ( !is_dir($dir1) )
            mkdir($dir1);
        //  Razdel v BD
        $Section = Zero_Model::Make('Zero_Section');
        /* @var $Section Zero_Section */
        $Section->Init_Url(Zero_App::$Config->Site_DomainSub . '/admin');
        $url = strtolower($module);
        $Section_Two = Zero_Model::Make('Zero_Section');
        /* @var $Section_Two Zero_Section */
        $Section_Two->AR->Sql_Where('Url', '=', Zero_App::$Config->Site_DomainSub . '/admin/' . $url);
        $Section_Two->AR->Select('ID');
        if ( 0 == $Section_Two->ID )
        {
            $Section_Two->Zero_Section_ID = $Section->ID;
            $Section_Two->Url = Zero_App::$Config->Site_DomainSub . '/admin/' . $url;
            $Section_Two->UrlThis = $url;
            $Section_Two->Layout = 'Zero_Main';
            $Section_Two->Controller = 'Zero_Content_Page';
            $Section_Two->IsAuthorized = 'yes';
            $Section_Two->IsVisible = 'yes';
            $Section_Two->Sort = 1;
            $Section_Two->Name = $module;
            $Section_Two->Title = $module;
            $Section_Two->Keywords = $module;
            $Section_Two->Description = $module;
            $Section_Two->AR->Insert();
            $Section_Two->Cache->Reset();
        }
        foreach ($table_list as $row)
        {
            //  zashchita ot skry`ty`kh i nekorretkny`kh tablitc
            $package = explode('_', $row['Name']);
            if ( 2 != count($package) )
                continue;
            //  Sozdanie modeli
            $path_pattern = ZERO_PATH_ZERO . '/data/Template_Model.php';
            $path_model = self::Get_Path_Class($row['Name']);
            if ( !file_exists($path_model) )
            {
                //        echo 'CREATE MODEL ' . $path_model . '<br>';
                //  Model
                $class = file_get_contents($path_pattern);
                $class = str_replace('<Comment>', $row['Name'], $class);
                $class = str_replace('<Package>', $package[0], $class);
                $class = str_replace('<Subpackage>', $package[1], $class);
                $class = str_replace('<Date>', date('Y.m.d'), $class);
                $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
                Zero_Lib_FileSystem::File_Save($path_model, $class);
            }
            //  Konfiguratciia modeli v tcelom
            $this->Config_Model($row['Name']);
            //  Konfiguratciia svoi`stv motceli
            $this->Config_Model_Prop($row['Name']);
            //  Konfiguratciia sviazei` motceli (mnogie ko mnogim)
            $this->Config_Model_Link($row['Name']);
            //  Kontroller spiska
            $path_target = substr($path_model, 0, -4) . '/Grid.php';
            if ( $flag_grid && !file_exists($path_target) )
            {
                //          echo 'CREATE CONTROLLER ' . $path_target . '<br>';
                $path_pattern = ZERO_PATH_ZERO . '/data/Template_Controller_Grid.php';
                $class = file_get_contents($path_pattern);
                $class = str_replace('<Comment>', 'Контроллер просмотра списка объектов', $class);
                $class = str_replace('<Package>', $package[0], $class);
                $class = str_replace('<Subpackage>', $package[1], $class);
                $class = str_replace('<Date>', date('Y.m.d'), $class);
                $class = str_replace('Zero_Controller_Grid', $row['Name'] . '_Grid', $class);
                $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
                Zero_Lib_FileSystem::File_Save($path_target, $class);
            }
            //  Razdel v BD
            $url = strtolower($package[0] . '/' . $package[1]);
            $Section_Three = Zero_Model::Make('Zero_Section');
            /* @var $Section_Three Zero_Section */
            $Section_Three->AR->Sql_Where('Url', '=', Zero_App::$Config->Site_DomainSub . '/admin/' . $url);
            $Section_Three->AR->Select('ID');
            if ( $flag_grid && 0 == $Section_Three->ID )
            {
                $Section_Three->Zero_Section_ID = $Section_Two->ID;
                $Section_Three->Url = Zero_App::$Config->Site_DomainSub . '/admin/' . $url;
                $Section_Three->UrlThis = strtolower($package[1]);
                $Section_Three->Layout = 'Zero_Main';
                $Section_Three->Controller = $row['Name'] . '_Grid';
                $Section_Three->IsAuthorized = 'yes';
                $Section_Three->IsVisible = 'yes';
                $Section_Three->Sort = 1;
                $Section_Three->Name = $row['Comment'];
                $Section_Three->Title = $row['Comment'];
                $Section_Three->Keywords = $row['Comment'];
                $Section_Three->Description = $row['Comment'];
                $Section_Three->AR->Insert();
                $Section_Three->Cache->Reset();
            }
            //  Kontroller redaktirovaniia
            $path_target = substr($path_model, 0, -4) . '/Edit.php';
            if ( $flag_edit && !file_exists($path_target) )
            {
                //          echo 'CREATE CONTROLLER ' . $path_target . '<br>';
                $path_pattern = ZERO_PATH_ZERO . '/data/Template_Controller_Edit.php';
                $class = file_get_contents($path_pattern);
                $class = str_replace('<Comment>', 'Контроллер изменения объекта', $class);
                $class = str_replace('<Package>', $package[0], $class);
                $class = str_replace('<Subpackage>', $package[1], $class);
                $class = str_replace('<Date>', date('Y.m.d'), $class);
                $class = str_replace('Zero_Controller_Edit', $row['Name'] . '_Edit', $class);
                $class = str_replace('Zero_Model_Pattern', $row['Name'], $class);
                Zero_Lib_FileSystem::File_Save($path_target, $class);
            }
            //  Razdel v BD
            $url = strtolower($package[0] . '/' . $package[1] . '/edit');
            $Section_Four = Zero_Model::Make('Zero_Section');
            /* @var $Section_Four Zero_Section */
            $Section_Four->AR->Sql_Where('Url', '=', Zero_App::$Config->Site_DomainSub . '/admin/' . $url);
            $Section_Four->AR->Select('ID');
            if ( $flag_edit && 0 == $Section_Four->ID && 0 < $Section_Three->ID )
            {
                $Section_Four->Zero_Section_ID = $Section_Three->ID;
                $Section_Four->Url = Zero_App::$Config->Site_DomainSub . '/admin/' . $url;
                $Section_Four->UrlThis = 'edit';
                $Section_Four->Layout = 'Zero_Main';
                $Section_Four->Controller = $row['Name'] . '_Edit';
                $Section_Four->IsAuthorized = 'yes';
                $Section_Four->IsVisible = 'no';
                $Section_Four->Sort = 1;
                $Section_Four->Name = $row['Comment'] . ' изменение';
                $Section_Four->Title = $row['Comment'] . ' изменение';
                $Section_Four->Keywords = $row['Comment'] . ' изменение';
                $Section_Four->Description = $row['Comment'] . ' изменение';
                $Section_Four->AR->Insert();
                $Section_Four->Cache->Reset();
            }
            //  Internatcionalizatciia
            $this->Config_I18n($row['Name']);
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
    protected function Config_Model($Table)
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
        $path = self::Get_Path_Class($Table);
        $class = file_get_contents($path);
        preg_match('~/\*BEG_CONFIG_MODEL\*/(.*?)/\*END_CONFIG_MODEL\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG MODEL ' . $path . '<br>';
            $class = preg_replace('~/\*BEG_CONFIG_MODEL\*/(.*?)/\*END_CONFIG_MODEL\*/~si', "/*BEG_CONFIG_MODEL*/{$str}\n\t\t\t/*END_CONFIG_MODEL*/", $class);
            file_put_contents($path, $class);
        }
        unset($str);
        unset($path);
        unset($class);
        unset($config);
    }

    /**
     * Konfiguratcii svoi`stv tipa ob``ekta
     *
     * Poluchneie i analiz stolbtcov v tablitcakh.
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_Model_Prop($Table)
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
            //  Opredelenie grida
            if ( 'Text' == $config[$Prop]['Form'] )
            {
                $config_grid[] = $Prop;
            }
        }
        //  Bazovaia konfiguratciia
        $str_props = "\n";
        $str_property = "\n";
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
        $str_props = substr($str_props, 0, -1);
        $str_property = substr($str_property, 0, -1);

        /**
         * Faktoring / Refaktoring modeli po poluchennoi` konfiguratcii
         */
        $path = self::Get_Path_Class($Table);
        $class = file_get_contents($path);
        //  Bazovaia konfiguratciia svoi`stv
        preg_match('~/\*BEG_CONFIG_PROP\*/(.*?)/\*END_CONFIG_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP ' . $path . '<br>';
            $class = preg_replace('~<BEG_CONFIG_PROPERTY>(.*?)<END_CONFIG_PROPERTY>~si', "<BEG_CONFIG_PROPERTY>{$str_property}\n * <END_CONFIG_PROPERTY>", $class);
            $class = preg_replace('~/\*BEG_CONFIG_PROP\*/(.*?)/\*END_CONFIG_PROP\*/~si', "/*BEG_CONFIG_PROP*/{$str_props}\n\t\t\t/*END_CONFIG_PROP*/", $class);
        }
        //  Konfiguratciia fil`tra
        preg_match('~/\*BEG_CONFIG_FILTER_PROP\*/(.*?)/\*END_CONFIG_FILTER_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP FILTER' . $path . '<br>';
            $str_props = "\n";
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
            $str_props = substr($str_props, 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_FILTER_PROP\*/(.*?)/\*END_CONFIG_FILTER_PROP\*/~si', "/*BEG_CONFIG_FILTER_PROP*/{$str_props}\n\t\t\t/*END_CONFIG_FILTER_PROP*/", $class);
        }
        //  Konfiguratciia grida
        preg_match('~/\*BEG_CONFIG_GRID_PROP\*/(.*?)/\*END_CONFIG_GRID_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP GRID' . $path . '<br>';
            $str_props = "\n";
            foreach ($config_grid as $prop)
            {
                $str_props .= "\t\t\t'" . $prop . "' => [],\n";
            }
            $str_props = substr($str_props, 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_GRID_PROP\*/(.*?)/\*END_CONFIG_GRID_PROP\*/~si', "/*BEG_CONFIG_GRID_PROP*/{$str_props}\n\t\t\t/*END_CONFIG_GRID_PROP*/", $class);
        }
        //  Konfiguratciia formy`
        preg_match('~/\*BEG_CONFIG_FORM_PROP\*/(.*?)/\*END_CONFIG_FORM_PROP\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG PROP FORM ' . $path . '<br>';
            $str_props = "\n";
            foreach ($config as $prop => $row)
            {
                $str_props .= "\t\t\t'" . $prop . "' => [],\n";
                //                $str_props .= "\t\t\t'" . $prop . "' => array(";
                //                $str_props .= "'Form' => '" . $row['Form'] . "', ";
                //                $str_props .= "'IsNull' => '" . $row['IsNull'] . "', ";
                //                $str_props = substr($str_props, 0, -2);
                //                $str_props .= "),\n";
            }
            $str_props = substr($str_props, 0, -1);
            $class = preg_replace('~/\*BEG_CONFIG_FORM_PROP\*/(.*?)/\*END_CONFIG_FORM_PROP\*/~si', "/*BEG_CONFIG_FORM_PROP*/{$str_props}\n\t\t\t/*END_CONFIG_FORM_PROP*/", $class);
        }
        file_put_contents($path, $class);
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
    protected function Config_Model_Link($Table)
    {
        $config = [];
        $sql = "SHOW TABLE STATUS WHERE `Name` LIKE '{$Table}_2_%' OR `Name` LIKE '%_2_{$Table}'";
        $rows = Zero_DB::Select_List($sql);
        foreach ($rows as $tbl)
        {
            //  zashchita ot skry`ty`kh i nekorretkny`kh tablitc
            if ( 5 != count(explode('_', $tbl)) )
            {
                continue;
            }
            //
            $sql = "SHOW FULL COLUMNS FROM `{$tbl}` WHERE `Field` LIKE '{$Table}_%ID';";
            $row = Zero_DB::Select_Row($sql);
            $PropThis = isset($row['Field']) ? $row['Field'] : '' ;
            $sql = "SHOW FULL COLUMNS FROM `{$tbl}` WHERE `Field` NOT LIKE '{$Table}_%ID';";
            $row = Zero_DB::Select_Row($sql);
            $PropTarget = isset($row['Field']) ? $row['Field'] : '' ;
            $TableTarget = zero_relation($PropTarget);
            $config[$TableTarget]['table_link'] = $tbl;
            $config[$TableTarget]['prop_this'] = $PropThis;
            $config[$TableTarget]['prop_target'] = $PropTarget;
        }
        //
        $str = "\n";
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
        $path = self::Get_Path_Class($Table);
        $class = file_get_contents($path);
        preg_match('~/\*BEG_CONFIG_LINK\*/(.*?)/\*END_CONFIG_LINK\*/~si', $class, $arr);
        if ( strlen(trim($arr[1])) < 3 )
        {
            //      echo 'CONFIG LINK ' . $path . '<br>';
            $class = preg_replace('~/\*BEG_CONFIG_LINK\*/(.*?)/\*END_CONFIG_LINK\*/~si', "/*BEG_CONFIG_LINK*/{$str}\n\t\t\t/*END_CONFIG_LINK*/", $class);
            file_put_contents($path, $class);
        }
    }

    /**
     * Internatcionalizatciia
     *
     * @param string $Table tablitca v BD kotoruiu dolzhna obsluzhivat` model`
     */
    protected function Config_I18n($Table)
    {
        $config = [];
        $folder_list = explode('_', $Table);
        /*
         * Model`
         */
        $sql = "SHOW TABLE STATUS WHERE `Name` = '{$Table}';";
        $row = Zero_DB::Select_Row($sql);
        $config['model'] = $row['Comment'];
        foreach (Zero_DB::Select_Array("SHOW FULL COLUMNS FROM {$Table};") as $row)
        {
            $Type = explode('(', $row['Type']);
            $Type = array_shift($Type);
            $config["model prop {$row['Field']}"] = $row['Comment'];
            //            $config["model prop {$row['Field']} validate Error_NotNull"] = 'Value is not set';
            //  Перечисления и множества
            if ( 'enum' == $Type || 'set' == $Type )
            {
                $list = explode("','", substr($row['Type'], strpos($row['Type'], "'") + 1, -2));
                foreach ($list as $val)
                {
                    $config["model prop {$row['Field']} option {$val}"] = $val;
                }
            }
        }

        $str = '';
        ksort($config);
        foreach ($config as $key => $val)
        {
            $str .= "\t'{$key}' => '{$val}',\n";
        }
        $str = substr($str, 0, -1);
        $path1 = ZERO_PATH_ZERO . '/data/Template_I18n.php';
        foreach (array_keys(Zero_App::$Config->Language) as $lang)
        {
            $path2 = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
            if ( file_exists($path2) )
                continue;
            $path2 = ZERO_PATH_APPLICATION . '/' . strtolower($folder_list[0]) . '/i18n/' . $lang . '/' . $folder_list[1] . '.php';
            //        echo 'CONFIG I18N MODEL ' . $path2 . '<br>';
            $file_data = file_get_contents($path1);
            $file_data = str_replace('# CONFIG', $str, $file_data);
            Zero_Lib_FileSystem::File_Save($path2, $file_data);
        }
    }
}
