<?php
/**
 * AppConfig
 * This is the default application configuration file.
 *
 * @author: Raysmond
 * @created: 2013-12-23
 */

return array(

    // The name of the application
    'name' => 'Rays Application',

    // The base directory of the application
    // Required for real application
    'baseDir' => dirname(__FILE__),

    // The base URI path. If the server host is localhost, then the full base URI path will be "http://localhost/Rays"
    // Required for real application
    'basePath' => "/Rays",

    // Database configuration
    'db' => array(
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'db_name' => '',
        'table_prefix' => '',
        'charset' => 'utf8',
    ),

    'timeZone' => 'PRC',

    // Default layout
    'layout' => 'index',

    /**
     * Whether is clean URL or not.
     *
     * For example:
     * default URL form: "http://localhost/Rays/demos/blog/index.php?q=site/index"
     * clean URL form: "http://localhost/Rays/demos/blog/site/index"
     */
    'isCleanUri' => false,

    // Default controller if no controller resolved from URL
    'defaultController' => 'site',

    // Default action in a controller if no action resolved from URL
    'defaultAction' => 'index',

    // Whether is in DEBUG mode or not
    'debug' => true,

    // Exception handler
    // 'exceptionAction' => '',
    // 'exceptionAction' => 'site/exception' // the action exception of SiteController will be invoked to handle the exception

    // Auth provider class
    // 'authProvider' => 'User'

    // Cache configuration
    /*
    'cache' => array(
        'cache_dir' => '/cache',
        'cache_prefix' => "cache_",
        'cache_time' => 1800, //seconds
    )
    */
);