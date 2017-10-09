<?php
/**
 * Controller. <Comment>
 *
 * @package Helper.Excel
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @date 2015.01.01
 */
class Helper_Excel_Style
{
    /**
     * Шаблоны стилевого оформления заголовка
     *
     * @var array
     */
    public static $StyleHead = [
        'default' => [
            'height' => 40,
            'align' => 'center',
            'valign' => 'center',
            'color' => '000000',
            'bgcolor' => 'C5D9F1',
            'font' => 'Arial',
            'size' => 9,
            'bold' => false,
            'italic' => false,
            'underline' => false,
            'wrap' => true
        ],
    ];

    /**
     * Шаблоны стилевого оформления контента
     *
     * @var array
     */
    public static $StyleBody = [
        'default' => [
            'align' => 'center',
            'valign' => 'center',
            'color' => '000000',
            'font' => 'Arial',
            'size' => 8,
            'bold' => false,
            'italic' => false,
            'underline' => false,
            'wrap' => true
        ],
    ];
}
