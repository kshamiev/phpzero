<?php

/**
 * Component. Caching subsystem.
 *
 * Implemented time level 2 cache
 * - 0 persistent cache (can only be removed by an explicit request)
 * - 1 < cache lifetime in seconds
 *
 * Implemented associated cache
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@phpzero.com>
 * @version $Id$
 * @link http://www.phpzero.com/
 * @copyright <PHP_ZERO_COPYRIGHT>
 * @license http://www.phpzero.com/license/
 */
class Zero_Cache
{
    /**
     * Cache lifetime 1 minute
     */
    const TIME_M1 = 60;
    /**
     * Cache lifetime 3 minute
     */
    const TIME_M3 = 180;
    /**
     * Cache lifetime 6 minute
     */
    const TIME_M6 = 360;
    /**
     * Cache lifetime 12 minute
     */
    const TIME_M12 = 720;
    /**
     * Cache lifetime 24 minute
     */
    const TIME_M24 = 1440;
    /**
     * Cache lifetime 1 hour
     */
    const TIME_H1 = 3600;
    /**
     * Cache lifetime 3 hour
     */
    const TIME_H3 = 10800;
    /**
     * Cache lifetime 6 hours
     */
    const TIME_H6 = 21600;
    /**
     * Cache lifetime 12 hours
     */
    const TIME_H12 = 43200;
    /**
     * Cache lifetime 24 hour
     */
    const TIME_H24 = 86400;

    /**
     * Source Memcache.
     *
     * @var Memcache
     */
    private static $_Memcache = null;

    /**
     * An array of connections cache.
     *
     * @var array
     */
    private static $_Link = [];

    /**
     * The object with which we work
     *
     * @var Zero_Model
     */
    protected $Model = null;

    /**
     * The class constructor
     *
     * @param Zero_Model $Model
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    /**
     * Initialize cache system (Memcache or Files).
     *
     * @param $config array конфигурация серверов
     */
    public static function InitMemcache($config)
    {
        $counter = 0;
        self::$_Memcache = new Memcache;
        foreach ($config as $server)
        {
            $arr = explode(':', $server);
            if ( self::$_Memcache->addServer($arr[0], $arr[1], true, 1, 1000, 15, true) )
                $counter++;
        }
        if ( 0 < $counter )
            return;
        self::$_Memcache = null;
    }

    /**
     * Low-level (direct) method of obtaining the cache.
     *
     * @param string $index index cache
     * @param integer $time 0 - persistent cache, 0 < cache lifetime in seconds
     * @return mixed полученый кеш, либо false
     */
    public static function Get_Data($index, $time = 0)
    {
        if ( false == Zero_App::$Config->Site_IsCache )
            $time = 1;
        //  file cache
        if ( null == self::$_Memcache )
        {
            $index = ZERO_PATH_CACHE . '/' . $index . '.data';
            if ( file_exists($index) && (0 == $time || (time() - filemtime($index)) < $time) )
                return unserialize(file_get_contents($index));
            return false;
        }
        //  Memcache
        else
        {
            $index = Zero_App::$Config->Site_DomainSub . $index;
            $index = str_replace('/', '.', $index);
            return self::$_Memcache->get($index);
        }
    }

    /**
     * Low-level (direct) method of maintaining cache.
     *
     * @param string $index index cache
     * @param string $value cached data
     * @param integer $time 0 - persistent cache, 0 < cache lifetime in seconds
     * @return boolean
     */
    public static function Set_Data($index, $value, $time = 0)
    {
        if ( false == Zero_App::$Config->Site_IsCache )
            $time = 1;
        //  file cache
        if ( null == self::$_Memcache )
        {
            $index = ZERO_PATH_CACHE . '/' . $index . '.data';
            Zero_Lib_FileSystem::File_Save($index, serialize($value));
        }
        //  Memcache
        else
        {
            $index = Zero_App::$Config->Site_DomainSub . $index;
            $index = str_replace('/', '.', $index);
            self::$_Memcache->set($index, $value, 0, $time);
        }
        //  dependent binding cache
        foreach (self::$_Link as $arr)
        {
            $path = ZERO_PATH_CACHE . '/' . $arr[0] . '/' . Zero_Lib_FileSystem::Get_Path_Cache($arr[1]) . '/' . $arr[1] . '/cache.cache';
            Zero_Lib_FileSystem::File_Save_After($path, $index);
        }
        self::$_Link = [];
        return true;
    }

    /**
     * Get cache data from object.
     *
     * @param string $index index cache
     * @param integer $time 0 - persistent cache, 0 < cache lifetime in seconds
     * @return mixed data or false
     */
    public function Get($index, $time = 0)
    {
        return self::Get_Data(self::_Get_Index_Path(get_class($this->Model), $this->Model->ID, $index), $time);
    }

    /**
     * Save cache data to object.
     *
     * @param string $index index cache
     * @param mixed $value data
     * @param integer $time 0 - persistent cache, 0 < cache lifetime in seconds
     * @return boolean
     */
    public function Set($index, $value, $time = 0)
    {
        self::$_Link[] = [get_class($this->Model), $this->Model->ID];
        return self::Set_Data(self::_Get_Index_Path(get_class($this->Model), $this->Model->ID, $index), $value, $time);
    }

    /**
     * The binding cache.
     *
     * According to the parameters you pass identifies specific objects to be associated with the saved cache.
     * If you change these objects will be reset to associate cache.
     *
     * @param string $source_name the data source (table) is bound to cache data
     * @param integer $id object identifier is associated with the data cache
     */
    public static function Set_Link($source_name, $id)
    {
        self::$_Link[] = [$source_name, $id];
    }

    /**
     * Cleaning (reset) cache data associated with the object
     *
     * Cleared his personal cache and the associated
     */
    public function Reset()
    {
        $path_file = ZERO_PATH_CACHE . '/' . $this->Model->Source . '/' . Zero_Lib_FileSystem::Get_Path_Cache($this->Model->ID) . '/' . $this->Model->ID . '/cache.cache';
        if ( !file_exists($path_file) )
            return true;
        rename($path_file, $path_file = $path_file . '.log');
        $fp = fopen($path_file, 'r');
        //  Memcache
        if ( null != self::$_Memcache )
        {
            while ( $index = trim(fgets($fp)) )
            {
                self::$_Memcache->delete($index, 0);
            }
        }
        //  file cache
        else
        {
            while ( $index = trim(fgets($fp)) )
            {
                if ( file_exists($index) )
                    unlink($index);
            }
        }
        fclose($fp);
        unlink($path_file);
    }

    /**
     * Cleaning (reset) all cache
     *
     */
    public static function Reset_All()
    {
        //  Memcache
        if ( null != self::$_Memcache )
        {
            self::$_Memcache->flush();
            // self::$_Memcache->close();
            // self::$_Memcache->delete($key, 0);
        }
        //  file cache
        Zero_Lib_FileSystem::File_Remove(ZERO_PATH_CACHE);
    }

    /**
     * Formation of the way to the source of the cache.
     *
     * Powered by model
     * The path is formed by the name of the source, idetifikatoru and index.
     *
     * @param string $source data source (table)
     * @param int $id object identifier
     * @param string $index индекс кеша (arbitrary name)
     * @return string (source/[int/...]/id/index)
     */
    private static function _Get_Index_Path($source, $id, $index)
    {
        $path = $source;
        if ( null == self::$_Memcache )
            $path .= '/' . Zero_Lib_FileSystem::Get_Path_Cache($id);
        return $path . '/' . $id . '/' . ZERO_LANG . '/' . $index;
    }
}
