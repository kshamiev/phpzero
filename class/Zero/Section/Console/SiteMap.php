<?php
/**
 * Формирование карты сайта в формате xml.
 *
 * @package Zero.Console.Section
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Section_Console_SiteMap extends Zero_Controller
{
    /**
     * Формирование карты сайта в формате xml.
     */
    public function Action_Default()
    {
        $str = '<' . '?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // разделы
        $Section = Zero_Model::Makes('Zero_Section');
        $Section->AR->Sql_Where('IsAuthorized', '=', 'no');
        $Section->AR->Sql_Where('IsIndex', '=', 'yes');
        $Section->AR->Sql_Where('IsEnable', '=', 'yes');
        $section_list = $Section->AR->Select_Tree('ID, Name, Url');
        foreach ($section_list as $row)
        {
            $str .= '
            <url>
                <loc>' . HTTP . substr($row['Url'], strpos($row['Url'], '/')) . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
            </url>';
        }

        $str .= "\n</urlset>";
        Helper_File::File_Save(ZERO_PATH_SITE . '/sitemap.xml', $str);
        return 0;
    }
}