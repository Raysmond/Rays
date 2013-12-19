<?php

require(dirname(__FILE__) . '/RaysFramework.php');

/**
 * Class Rays provide common functions for the whole web application.
 *
 * @author: Raysmond
 */
class Rays extends RaysFramework{

    /**
     * Get the current login user or null for anonymous users
     * @return mixed
     */
    public static function user()
    {
        return static::app()->getLoginUser();
    }

    /**
     * Whether the user has login
     * @return mixed
     */
    public static function isLogin()
    {
        return static::app()->isUserLogin();
    }

    /**
     * Get router
     * @return mixed
     */
    public static function router()
    {
        return static::app()->getRouter();
    }

    /**
     * Get http request handler
     * @return mixed
     */
    public static function httpRequest()
    {
        return static::app()->getHttpRequest();
    }

    /**
     * Add js
     * @param $js javascript src like '/public/js/main.js' or 'http://example.com/example.js'
     */
    public static function js($js)
    {
        static::app()->getClientManager()->registerScript($js);
    }

    /**
     * Add css
     * @param $css css path like '/public/css/main.css' or 'http://example.com/example.css'
     */
    public static function css($css)
    {
        static::app()->getClientManager()->registerCss($css);
    }

    /**
     * Whether the current http request type is "POST"
     * @return mixed
     */
    public static function isPost()
    {
        return static::app()->getHttpRequest()->isPostRequest();
    }

    /**
     * Whether the current http request type is "Ajax"
     * @return mixed
     */
    public static function isAjax()
    {
        return static::app()->getHttpRequest()->isAjaxRequest();
    }

    /**
     * Get base url of the site
     * @return mixed
     */
    public static function baseUrl()
    {
        return static::app()->getBaseUrl();
    }

    /**
     * Get current url
     * @return mixed
     */
    public static function uri()
    {
        return static::app()->getHttpRequest()->getRequestUriInfo();
    }

    /**
     * Get URI referrer.(From which uri the request came)
     * @return mixed
     */
    public static function referrerUri()
    {
        return static::app()->getHttpRequest()->getUrlReferrer();
    }

    /**
     * Get request parameter
     * @param $name
     * @param $default
     * @return mixed
     */
    public static function getParam($name,$default)
    {
        return static::app()->getHttpRequest()->getParam($name,$default);
    }

    /**
     * Get args of action
     * @param $index
     * @return string|null
     */
    public static function args($index)
    {
        $args = Rays::router()->getParams();
        return isset($args[$index]) ?$args[$index] : null;
    }

}