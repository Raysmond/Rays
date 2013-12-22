<?php
/**
 * RCacheInterface interface
 *
 * @author: Raysmond
 */

interface RCacheInterface {

    public function get( $cacheId, $factor, $time );

    public function set( $cacheId, $factor, $content );
}