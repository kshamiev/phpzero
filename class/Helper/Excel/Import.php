<?php
require ZERO_PATH_LIBRARY . '/PHPExcel/Classes/PHPExcel.php';
/**
 * Controller. <Comment>
 *
 * @package Helper.Excel
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */
class Helper_Excel_Import
{
    /**
     * Импорт табличных данных из файла Excel
     *
     * @param string $pathFile Абсолютный путь до файла
     * @param string $controller Контроллер обработчик построчно (ControllerName-MethodName)
     * @param int $colbeg начальная колонка
     * @param int $rowbeg начальная строка
     * @param int $colend конечная колонка (-1 до конца данных)
     * @param int $rowend конечная строка (-1 до конца данных)
     * @return array 0 - массив импортированных данных, 1 - код ошибки
     * @throws Exception
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public static function FileTable($pathFile, $controller = '', $colbeg = 0, $rowbeg = 1, $colend = -1, $rowend = -1)
    {
//        require ZERO_PATH_LIBRARY . '/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
        //  Здесь инициализция обработчика если он указан
        $method = '';
        if ( '' != $controller )
        {
            $arr = explode('-', $controller);
            if ( 2 != count($arr) )
                return [[], 1];
            $controller = Zero_Controller::Makes($arr[0]);
            if ( method_exists($controller, $method) )
                $method = $arr[1];
        }

        //  определяем тип и загружаем файл
        $arr = explode('.', $pathFile);
        $ext = array_pop($arr);

        if ( 'xlsx' == $ext )
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        else if ( 'xls' == $ext )
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        else
            return [[], 1];

        $objPHPExcel = $objReader->load($pathFile);
        /* @var $objPHPExcel PHPExcel */

        //  выбираем первый лист файла (первая вкладка)
        $WorkSheet = $objPHPExcel->setActiveSheetIndex(0);

        //  читаем данные листа по указанному диапазону
        $result = array();
        for ($r = $rowbeg; $r < 1000000000; $r++)
        {
            $flagrow = false;
            for ($c = $colbeg; $c < 100; $c++)
            {
                $result[$r][$c] = trim($WorkSheet->getCellByColumnAndRow($c, $r)->getValue());
                //  print $r . ' - ' . $c . ' = ' . $result[$r][$c] . '<br>';
                if ( $result[$r][$c] )
                    $flagrow = true;
                if ( $c == $colend )
                    break;
                if ( !$result[$r][$c] && $colend < 0 )
                {
                    unset($result[$r][$c]);
                    break;
                }
            }
            if ( !$flagrow && $rowend < 0 )
            {
                unset($result[$r]);
                break;
            }
            //  Здесь выполнение обработчика если он указан
            if ( '' != $method )
            {
                $controller->$method($result[$r], $r);
                unset($result[$r]);
            }
            //
            if ( $r == $rowend )
                break;
        }
        return [$result, 0];
    }
}
