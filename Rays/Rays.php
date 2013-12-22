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
        return self::app()->getLoginUser();
    }

    /**
     * Whether the user has login
     * @return mixed
     */
    public static function isLogin()
    {
        return self::app()->isLogin();
    }

    /**
     * Get router
     * @return mixed
     */
    public static function router()
    {
        return self::app()->getRouter();
    }

    /**
     * Get http request handler
     * @return mixed
     */
    public static function httpRequest()
    {
        return self::app()->getHttpRequest();
    }

    /**
     * Add js
     * @param $js javascript src like '/public/js/main.js' or 'http://example.com/example.js'
     */
    public static function js($js)
    {
        self::app()->client()->registerScript($js);
    }

    /**
     * Add css
     * @param $css css path like '/public/css/main.css' or 'http://example.com/example.css'
     */
    public static function css($css)
    {
        self::app()->client()->registerCss($css);
    }

    /**
     * Whether the current http request type is "POST"
     * @return mixed
     */
    public static function isPost()
    {
        return self::app()->getHttpRequest()->isPostRequest();
    }

    /**
     * Whether the current http request type is "Ajax"
     * @return mixed
     */
    public static function isAjax()
    {
        return self::app()->getHttpRequest()->isAjaxRequest();
    }

    /**
     * Get base url of the site
     * @return mixed
     */
    public static function baseUrl()
    {
        return self::app()->getBaseUrl();
    }

    /**
     * Get current url
     * @return mixed
     */
    public static function uri()
    {
        return self::app()->getHttpRequest()->getRequestUriInfo();
    }

    /**
     * Get URI referrer.(From which uri the request came)
     * @return mixed
     */
    public static function referrerUri()
    {
        return self::app()->getHttpRequest()->getUrlReferrer();
    }

    /**
     * Get request parameter
     * @param $name
     * @param $default
     * @return mixed
     */
    public static function getParam($name,$default)
    {
        return self::app()->getHttpRequest()->getParam($name,$default);
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