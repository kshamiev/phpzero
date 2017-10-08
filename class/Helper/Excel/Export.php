<?php
require ZERO_PATH_LIBRARY . '/PHPExcel/Classes/PHPExcel.php';
/**
 * Controller. <Comment>
 *
 * @package Helper.Excel
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */
class Helper_Excel_Export
{
    /**
     * Экспорт табличных данных в формате excel
     *
     * @param array $head заголовок документа (индекс имя столбца, значение массив стилевого оформления данного столбца)
     * $head = ['fieldName' => ['width' => 20, ...], ...];
     * @param array $data массив данных вставляекмый в шаблон
     * $data = [['Название свойства', 'значение свойства'], ...]
     * @param bool $flagFile сохранить в файл по умолчанию false отдается в stdout
     * @param string $tpl имя используемого шаблона
     * @return array 0 - абсолютный путь до файла, 1 - код ошибки
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public static function Table($head, $data, $flagFile = false, $tpl = 'default')
    {
        // Проверки
        if ( 5000 < count($data) )
        {
            Zero_Logs::Set_Message_Error("Объем экспортируемых данных слишком велик");
            return ['', 1];
        }
//        require ZERO_PATH_LIBRARY . '/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        // Set properties
        $Property = $objPHPExcel->getProperties();
        $Property->setCreator(Zero_App::$Config->Site_Name . " <" . Zero_App::$Config->Site_Email . ">");
        $Property->setLastModifiedBy(Zero_App::$Config->Site_Name);
        $Property->setTitle("Отчет");
        $Property->setSubject("Отчет");
        $Property->setKeywords("Отчет");
        $Property->setDescription("Отчет");
        $Property->setCategory('Отчет');

        // Установка заголовков и стилевого оформления документа
        self::_set_Style_Head($objPHPExcel, $tpl, $head);
        self::_set_Style_Body($objPHPExcel, $tpl);

        // Заполнение документа данными
        $worksheet = $objPHPExcel->getActiveSheet();
        $r = count($head) + 1;
        foreach ($data as $row)
        {
            $c = 0;
            foreach ($row as $d)
            {
                $worksheet->setCellValueByColumnAndRow($c, $r, $d);
                $c++;
            }
            $r++;
        }

        return self::_output($objPHPExcel, $flagFile);
    }

    /**
     * Экспорт одного элемента (объекта) в формате excel
     *
     * @param array $head заголовок документа (индекс имя столбца, значение массив стилевого оформления данного столбца)
     * $head = ['fieldName' => ['width' => 20, ...], ...];
     * @param array $data массив данных вставляекмый в шаблон
     * $data = [['Название свойства', 'значение свойства'], ...]
     * @param bool $flagFile сохранить в файл по умолчанию false отдается в stdout
     * @param string $tpl имя используемого шаблона
     * @return array 0 - абсолютный путь до файла, 1 - код ошибки
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public static function Item($head, $data, $flagFile = false, $tpl = 'default')
    {
        // Проверки
        if ( 5000 < count($data) )
        {
            Zero_Logs::Set_Message_Error("Объем экспортируемых данных слишком велик");
            return ['', 1];
        }
//        require ZERO_PATH_LIBRARY . '/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        // Set properties
        $Property = $objPHPExcel->getProperties();
        $Property->setCreator(Zero_App::$Config->Site_Name . " <" . Zero_App::$Config->Site_Email . ">");
        $Property->setLastModifiedBy(Zero_App::$Config->Site_Name);
        $Property->setTitle("Отчет");
        $Property->setSubject("Отчет");
        $Property->setKeywords("Отчет");
        $Property->setDescription("Отчет");
        $Property->setCategory('Отчет');

        // Установка заголовков и стилевого оформления документа
        self::_set_Style_Head($objPHPExcel, $tpl, $head);
        self::_set_Style_Body($objPHPExcel, $tpl);

        // Заполнение документа данными
        $worksheet = $objPHPExcel->getActiveSheet();
        $r = 2;
        foreach ($data as $row)
        {
            $c = 0;
            foreach ($row as $d)
            {
                $worksheet->setCellValueByColumnAndRow($c, $r, $d);
                $c++;
            }
            $r++;
        }

        return self::_output($objPHPExcel, $flagFile);
    }

    /**
     * Установка заголовка и стиля заголовка
     *
     * @param PHPExcel $objPHPExcel
     * @param string $tpl имя используемого шаблона
     * @param array $head заголовок
     * @throws PHPExcel_Exception
     */
    private static function _set_Style_Head(PHPExcel $objPHPExcel, $tpl, $head)
    {
        $style = [];
        if ( isset(Helper_Excel_Style::$StyleHead[$tpl]) )
        {
            $style = Helper_Excel_Style::$StyleHead[$tpl];
        }
        //  Экспорт заголовков
        //  высота (exit)
        foreach ($head as $key => $rows)
        {
            $c = 0;
            $r = $key + 1;
            if ( isset($style['height']) )
                $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight($style['height']);
            foreach ($rows as $val => $row)
            {
                //  wrap
                if ( isset($row['wrap']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setWrapText($row['wrap']);
                else if ( isset($style['wrap']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setWrapText($style['wrap']);
                //  size
                if ( isset($row['size']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setSize($row['size']);
                else if ( isset($style['size']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setSize($style['size']);
                //  font
                if ( isset($row['font']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setName($row['font']);
                else if ( isset($style['font']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setName($style['font']);
                //  bold
                if ( isset($row['bold']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setBold($row['bold']);
                else if ( isset($style['bold']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setBold($style['bold']);
                //  italic
                if ( isset($row['italic']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setItalic($row['italic']);
                else if ( isset($style['italic']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setItalic($style['italic']);
                //  underline
                if ( isset($row['underline']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setUnderline($row['underline']);
                else if ( isset($style['underline']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->setUnderline($style['underline']);
                //  ширина
                if ( isset($row['width']) )
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($c)->setWidth($row['width']);
                else if ( isset($style['width']) )
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($c)->setWidth($style['width']);
                else
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($c)->setAutoSize(true);
                //  выравнивание
                if ( isset($row['align']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setHorizontal($row['align']);
                else if ( isset($style['align']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setHorizontal($style['align']);
                if ( isset($row['valign']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setVertical($row['valign']);
                else if ( isset($style['valign']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getAlignment()->setVertical($style['valign']);
                // color
                if ( isset($row['color']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->getColor()->setRGB($row['color']);
                else if ( isset($style['color']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFont()->getColor()->setRGB($style['color']);
                // bgcolor
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                if ( isset($row['bgcolor']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFill()->getStartColor()->setRGB($row['bgcolor']);
                else if ( isset($style['bgcolor']) )
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $r)->getFill()->getStartColor()->setRGB($style['bgcolor']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $r, ' ' . $val . ' ');
                $c++;
            }
        }
    }

    /**
     * Установка стиля контента
     *
     * @param PHPExcel $objPHPExcel
     * @param string $tpl имя используемого шаблона
     * @throws PHPExcel_Exception
     */
    private static function _set_Style_Body(PHPExcel $objPHPExcel, $tpl)
    {
        $style = [];
        if ( isset(Helper_Excel_Style::$StyleBody[$tpl]) )
        {
            $style = Helper_Excel_Style::$StyleBody[$tpl];
        }
        //  wrap
        if ( isset($style['wrap']) )
            $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText($style['wrap']);
        //  size
        if ( isset($style['size']) )
            $objPHPExcel->getDefaultStyle()->getFont()->setSize($style['size']);
        //  font
        if ( isset($style['font']) )
            $objPHPExcel->getDefaultStyle()->getFont()->setName($style['font']);
        //  bold
        if ( isset($style['bold']) )
            $objPHPExcel->getDefaultStyle()->getFont()->setBold($style['bold']);
        //  italic
        if ( isset($style['italic']) )
            $objPHPExcel->getDefaultStyle()->getFont()->setItalic($style['italic']);
        //  underline
        if ( isset($style['underline']) )
            $objPHPExcel->getDefaultStyle()->getFont()->setUnderline($style['underline']);
        //  выравнивание
        if ( isset($style['align']) )
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal($style['align']);
        if ( isset($style['valign']) )
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical($style['valign']);
        // color
        if ( isset($style['color']) )
            $objPHPExcel->getDefaultStyle()->getFont()->getColor()->setRGB($style['color']);
    }

    /**
     * Вывод в браузер либо в файл
     *
     * @param PHPExcel $objPHPExcel
     * @param bool $flagFile сохранить в файл по умолчанию false отдается в stdout
     * @return array
     * @throws PHPExcel_Reader_Exception
     */
    private static function _output(PHPExcel $objPHPExcel, $flagFile)
    {
        if ( $flagFile )
        { // сохранение в файл
            $filePath = tempnam(sys_get_temp_dir(), 'excelExport');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($filePath);
            return [$filePath, 0];
        }
        else
        { // выводим в браузер
            $filePath = 'ExcelDocument_' . date('Y.m.d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . basename($filePath) . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            Zero_App::ResponseConsole();
            exit;
        }
    }
}
