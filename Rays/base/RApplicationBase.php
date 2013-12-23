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

    /**
     * Application constructor.
     * Load default and custom configuration, then initialize the application.
     * @param string|null $defaultConfig default configuration path
     * @param string|null $config custom configuration path
     */
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
     * Get the name of the application
     * @return string
     */
    public function getName()
    {
        return $this->getConfig("name");
    }

    /**
     * Get the base URL of the application site
     * @return string
     */
    public function getBaseUrl()
    {
        $url = $this->getConfig("baseUrl");
        if (!isset($url)) {
            $path = $this->getBasePath();
            $url = 'http://' . $_SERVER['SERVER_NAME'] . (isset($path) ? $path : "");
        }
        return $url;
    }

    /**
     * Get base path of the application. For example: /FUDGroup
     * @return string
     */
    public function getBasePath()
    {
        return $this->getConfig("basePath");
    }

    /**
     * Get the base directory of the application
     * @return string
     */
    public function getBaseDir()
    {
        return $this->getConfig("baseDir");
    }

    /**
     * Get the database configuration array.
     * @return array
     * For example:
     * <pre>
     * array(
     *   'host' => '127.0.0.1',
     *   'user' => 'fdugroup',
     *   'password' => 'fdugroup',
     *   'db_name' => 'fdugroup',
     *   'table_prefix' => '',
     *   'charset' => 'utf8',
     *   ),
     * </pre>
     */
    public function getDbConfig()
    {
        return $this->getConfig("db");
    }

    /**
     * Get the whole configuration array of the application
     * @param string|null $key
     * @return RConfig|mixed the config object if $key=null, otherwise return the configuration value
     */
    public function getConfig($key=null)
    {
        return $key===null ? $this->config : $this->config->getConfig($key);
    }

    /**
     * Get the time zone of the application
     * @return string
     */
    public function getTimeZone()
    {
        return $this->getConfig("timeZone");
    }

    /**
     * Get charset
     * @return string
     */
    public function getCharset()
    {
        return $this->getConfig("charset");
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
        return $this->getConfig("cache");
    }

    /**
     * Get Exception action
     * like: "site/exception", the "site" means the controller ID and the "exception" means the action ID in SiteController
     * @return string
     */
    public function getExceptionAction()
    {
        return $this->getConfig("exceptionAction");
    }

    /**
     * Whether the application is in debug mode.
     * @return bool
     */
    public function isDebug()
    {
        return $this->getConfig("debug") === true;
    }
}