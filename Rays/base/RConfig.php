<?php
/**
 * RConfig class
 *
 * @author: Raysmond
 * @created: 2013-12-23
 */

class RConfig
{

    protected $config = array();

    protected $defaultConfigFile;

    public function setDefault($file)
    {
        if (!file_exists($file) or !is_readable($file)) {
            throw new RException('Default configuration file is not exists');
        }
        $this->defaultConfigFile = $file;
    }

    /**
     * Load configuration
     * @param null $customConfig
     * @throws RException
     */
    public function load($customConfig = null)
    {
        $default = $this->defaultConfigFile;
        if (!$default) {
            throw new RException('Default configuration file is not found');
        }

        $this->config = require $default;

        if (null !== $customConfig) {
            if (is_file($customConfig) && is_readable($customConfig)) {
                $customConfig = require $customConfig;
                $this->config = array_replace_recursive($this->config, $customConfig);
            }
        }
    }

    /**
     * __get
     *
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        return (isset($this->config[$key])) ? $this->config[$key] : null;
    }

    /**
     * __set
     *
     * @param $key
     * @param $value
     * @throws RException
     */
    public function __set($key, $value)
    {
        throw new RException('Configuration is read only');
    }

    /**
     * __isset
     *
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * Get config detail
     * @param null $key
     * @param null $subKey
     * @return array|null
     * @throws RException
     */
    public function getConfig($key = null, $subKey = null)
    {
        if (!$this->config) {
            throw new RException('Application configuration is missing');
        }

        if (null !== $key && isset($this->config[$key])) {
            if ($subKey && isset($this->config[$key][$subKey]))
                return $this->config[$key][$subKey];
            else
                return $this->config[$key];
        } else
            return (null !== $key) ? null : $this->config;
    }
} 