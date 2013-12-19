<?php
/**
 * RClient class file. This class if mainly for registering CSS and JavaScript.
 *
 * @author: Raysmond
 */
class RClient
{

    /**
     * @var array the core css array
     */
    public $coreCss = array();

    /**
     * @var array the normal css array
     */
    public $css = array();

    /**
     * @var array the core JavaScript array
     */
    public $coreScript = array();

    /**
     * @var array the normal JavaScript array
     */
    public $script = array();

    /**
     * @var string the header title which is in the <head><title></title></head> in a HTML document
     */
    private $_headerTitle = "";

    /**
     * Register a core css file
     * @param $cssPath
     */
    public function registerCoreCss($cssPath)
    {
        if (!$this->isRegisteredCoreCss($cssPath)) {
            $this->coreCss[$cssPath] = $cssPath;
        }
    }

    /**
     * Register a normal css file
     * @param $cssPath
     */
    public function registerCss($cssPath)
    {
        if (!$this->isRegisteredCss($cssPath)) {
            $this->css[$cssPath] = $cssPath;
        }
    }

    /**
     * Register a core JavaScript file
     * @param $scriptPath
     */
    public function registerCoreScript($scriptPath)
    {
        if (!$this->isRegisteredCoreScript($scriptPath)) {
            $this->coreScript[$scriptPath] = $scriptPath;
        }
    }

    /**
     * Register a normal JavaScript file
     * @param $scriptPath
     */
    public function registerScript($scriptPath)
    {
        if (!$this->isRegisteredScript($scriptPath)) {
            $this->script[$scriptPath] = $scriptPath;
        }
    }

    public function isRegisteredCss($cssPath)
    {
        return isset($this->css[$cssPath]);
    }

    public function isRegisteredCoreCss($cssPath)
    {
        return isset($this->coreCss[$cssPath]);
    }

    public function isRegisteredScript($scriptPath)
    {
        return isset($this->script[$scriptPath]);
    }

    public function isRegisteredCoreScript($scriptPath)
    {
        return isset($this->coreScript[$scriptPath]);
    }

    public function getHeaderTitle()
    {
        if (!isset($this->_headerTitle)) {
            $this->_headerTitle = Rays::app()->getName();
        }
        return $this->_headerTitle . " | " . $this->_headerTitle = Rays::app()->getName();
    }

    public function setHeaderTitle($title)
    {
        $this->_headerTitle = $title;
    }
}