<?php
/**
 * RICacheHelper interface
 *
 * @author: Raysmond
 */

interface RICacheHelper {

    public function get( $cacheId, $factor, $time );

    public function set( $cacheId, $factor, $content );
}