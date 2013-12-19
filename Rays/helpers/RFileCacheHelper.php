<?php
/**
 * RFileCacheHelper helper class
 *
 * @author: Raysmond
 */

class RFileCacheHelper implements RICacheHelper
{
    /**
     * The cache directory
     * @var null|string
     */
    public $cacheDir = null;

    /**
     * Max cache time
     * @var int
     */
    public $cacheTime = 3600;

    /**
     * The prefix for cache file name
     * @var string
     */
    public $cachePrefix = 'cache_';

    /**
     * Constructor method. The args array should like the following:
     * <code>
     * $_args = array(
     *     "cache_dir" = "/cache", // the '/' at the beginning means the base directory of the application
     *     "cache_time" = 3600, // seconds
     *     "cache_prefix" = "cache_"
     * );
     * </code>
     * @param array $_args
     */
    public function __construct($_args = array())
    {
        if ($_args != null) {
            if (isset($_args['cache_dir']))
                $this->cacheDir = Rays::app()->getBaseDir().'/..'.$_args['cache_dir'].'/';
            if(isset($_args['cache_time']))
                $this->cacheTime = $_args['cache_time'];
            if(isset($_args['cache_prefix']))
                $this->cachePrefix = $_args['cache_prefix'];
        }
    }

    /**
     * Get a cached HTML file
     * @param $cacheId string the ID of the cache
     * @param $name string the name of cache name
     * @return string
     */
    private function getCacheFile($cacheId, $name)
    {
        $path = $this->cacheDir . str_replace('.', '/', $cacheId);
        return $path . '/' . ($name !== null ? $this->cachePrefix . $name . '.html' : $this->cachePrefix.'untitled.html');
    }

    /**
     * Get the cached content
     * @param $cacheId string the ID of the cache
     * @param $name string the name of the cache file
     * @param null $expireTime max cache time for the content
     * @return bool|string the cache content or false for not existed or expired cache
     */
    public function get($cacheId, $name, $expireTime=null)
    {
        $cachedFile = $this->getCacheFile($cacheId, $name);
        $time = $this->cacheTime;
        if($expireTime!=null){
            $time = $expireTime;
        }

        if (!file_exists($cachedFile)) return FALSE;
        if ($time < 0) return file_get_contents($cachedFile);
        if (filemtime($cachedFile) + $time < time()) return FALSE;
        return file_get_contents($cachedFile);
    }

    /**
     * Set cache content
     * @param $cacheId the ID of the cache. The ID is used for locating the cache directory
     * @param null $name the name of the cache
     * @param $_content the cache content
     * @return int
     */
    public function set($cacheId, $name = null, $_content)
    {
        $cachedFile = $this->getCacheFile($cacheId, $name);
        $path = dirname($cachedFile);

        //check and make the $path dir
        if (!file_exists($path)) {
            $dir = dirname($path);
            $names = array();
            do {
                if (file_exists($dir)) break;
                $names[] = basename($dir);
                $dir = dirname($dir);
            } while (true);

            for ($i = count($names) - 1; $i >= 0; $i--) {
                $dir = $dir . '/' . $names[$i];
                //mkdir($_dir, 0x777);
                mkdir($dir);
            }
            //mkdir($path, 0x777);
            mkdir($path);
        }

        return file_put_contents($cachedFile, $_content);
    }
} 