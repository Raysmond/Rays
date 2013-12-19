<?php
/**
 * RCacheFactory helper class
 *
 * @author: Raysmond
 */

class RCacheFactory
{

    /**
     * Create a cache helper instance
     * @param $_class the class name of the cache helper
     * @param array|null $_args parameters array for the target cache helper
     * @return mixed
     */
    public static function create($_class, $_args = NULL)
    {
        return new $_class($_args);
    }
}