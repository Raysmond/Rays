<?php
/**
 * Application configuration
 *
 * @author: Raysmond
 * @created: 2013-12-28
 */

return array(
    "name" => "HelloWorld",
    "baseDir" => dirname(__FILE__),
    "basePath" => "/Rays/demos/helloworld",
    "isCleanUrl" => false,
    "layout" => "index",
    "defaultController" => 'site',
    'isDebug' => true,
    "exceptionAction"=>'site/exception'
);