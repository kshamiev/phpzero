<?php

/**
 * Controller. User authentication.
 *
 * @package Zero.Users.Controller
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Console_SiteMap extends Zero_Controller
{
    /** TODO Тут есть проектные части
     * Initialize the online status is not active users.
     *
     * @return boolean flag stop execute of the next chunk
     */
    public function Action_SiteMap()
    {
        $str = '<' . '?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // разделы
        $Section = Zero_Model::Make('Zero_Section');
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

        // товары
        foreach (Zero_DB::Select_Array("SELECT ID, `Name` FROM Shop_Wares") as $row)
        {
            $str .= '
            <url>
                <loc>' . HTTP . '/product-id:' . $row['ID'] . '-' . Zero_Lib_String::Transliteration_Url($row['Name']) . '.html</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>';
        }

        $str .= "\n</urlset>";
        Zero_Lib_FileSystem::File_Save(ZERO_PATH_SITE . '/sitemap.xml', $str);
        return $this->View;
    }
}