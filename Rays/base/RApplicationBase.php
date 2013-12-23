<?php
/**
 * RApplicationBase class file
 *
 * @author: Raysmond
 */
class RApplicationBase
{
    /**
     * @var RConfig
     */
    private $config;

    public function __construct($defaultConfig = null, $config = null)
    {
        $_config = new RConfig();
        $_config->setDefault($defaultConfig);
        $_config->load($config);
        $this->config = $_config;

        $this->init();
    }

    /**
     * Initialize the application with configurations
     */
    public function init()
    {
        date_default_timezone_set($this->getTimeZone());
        Rays::import("system.base.RException");
    }

    /**
     * Run the application
     */
    public function run()
    {

    }

    /**
     * End the application
     * @param int $status
     */
    public function end($status = 0)
    {
        exit($status);
    }

    /**
     * Get the base URL of the application site
     * @return string
     */
    public function getBaseUrl()
    {
        $baseUrl = $this->config->getConfig("baseUrl");
        if (!isset($baseUrl)) {
            $path = $this->getBasePath();
            $baseUrl = 'http://' . $_SERVER['SERVER_NAME'] . (isset($path) ? $path : "");
        }
        return $baseUrl;
    }

    /**
     * Get base path of the application. For example: /FUDGroup
     * @return string
     */
    public function getBasePath()
    {
        return $this->config->getConfig("basePath");
    }

    /**
     * Get the base directory of the application
     * @return string
     */
    public function getBaseDir()
    {
        return $this->config->getConfig("baseDir");
    }

    /**
     * Get the name of the application
     * @return string
     */
    public function getName()
    {
        return $this->config->getConfig("name");
    }

    /**
     * Get the database configuration array.
     * @return array
     * For example:
     * <code>
     * array(
     *   'host' => '127.0.0.1',
     *   'user' => 'fdugroup',
     *   'password' => 'fdugroup',
     *   'db_name' => 'fdugroup',
     *   'table_prefix' => '',
     *   'charset' => 'utf8',
     *   ),
     * </code>
     */
    public function getDbConfig()
    {
        return $this->config->getConfig("db");
    }

    /**
     * Get the whole configuration array of the application
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the time zone of the application
     * @return string
     */
    public function getTimeZone()
    {
        return $this->config->getConfig("timeZone");
    }

    /**
     * Get charset
     * @return string
     */
    public function getCharset()
    {
        return $this->config->getConfig("charset");
    }


    /**
     * Get the database table prefix from database configuration
     * @return string
     */
    public function getDBPrefix()
    {
        return $this->config->getConfig("db", "table_prefix");
    }

    /**
     * Get cache configuration array
     * @return array
     */
    public function getCacheConfig()
    {
        return $this->config->getConfig("cache");
    }

    /**
     * Get Exception action
     * like: "site/exception", the "site" means the controller ID and the "exception" means the action ID in SiteController
     * @return string
     */
    public function getExceptionAction()
    {
        return $this->config->getConfig("exceptionAction");
    }

    /**
     * Whether the application is in debug mode.
     * @return bool
     */
    public function isDebug()
    {
        return $this->config->getConfig("debug") === true;
    }
}