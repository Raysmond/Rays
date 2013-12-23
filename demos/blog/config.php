<?php
/**
 * Config file
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

return array(
    'baseDir' => dirname(__FILE__),

    // the title of the web application
    'name' => 'Rays blog',

    // database configuration
    'db' => array(
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'db_name' => 'rays_blog',
        'table_prefix' => '',
        'charset' => 'utf8',
    ),

    /*
     * The / at the beginning of the base Path stands for the base path of
     * the web host address like: localhost
     */
    'basePath' => "/Rays/demos/blog",

    'layout' => 'index',

    /**
     * Whether is clean URL or not.
     * default URL form: "http://localhost/Rays/demos/blog/index.php?q=site/index"
     * clean URL form: "http://localhost/Rays/demos/blog/site/index"
     */
    'isCleanUri' => false,

    /**
     * Default controller if no controller resolved from URL
     */
    'defaultController' => 'site',

    /**
     * Default action in a controller if no action resolved from URL
     */
    'defaultAction' => 'index',

    /**
     * Exception handler
     */
    'exceptionAction' => 'site/exception',

    /**
     * Whether is in DEBUG mode or not
     */
    'debug' =>true,

    'authProvider' => 'User'

    /*
    'cache' => array(
        'cache_dir' => '/cache',
        'cache_prefix' => "cache_",
        'cache_time' => 1800, //seconds
    )
    */
);
