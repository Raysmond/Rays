<?php
/**
 * RRouter class file
 * @author: Raysmond
 *
 * In order to resolve the query uri, first of all we have to normalize the query uri.
 * In this framework, I have made an agreement on the form of the query uri.
 * For example:
 * http://localhost/FDUGroup/?q=site/view/12
 * This is a common form. Based on this kind of form, the router analyse the uri and
 * set controller = "site", action = "view", parameters = 12. so the router url array is
 * array(
 *  'controller' => "site",
 *  'action' => "view",
 *  'params' => array(
 *      [0] => 12,
 *  )
 * )
 * Of course, if there are more than one parameter, than the other parameters will be added
 * to the array of 'params'.
 */
class RRouter
{
    /**
     * @var string controller ID
     */
    private $_controller;

    /**
     * @var string action ID
     */
    private $_action;

    /**
     * @var array parameters array for the action
     */
    private $_params = array();

    /**
     * @var string default controller
     */
    private $_defaultController = 'site';

    public function __construct()
    {
        if ($c = Rays::app()->getConfig("defaultController"))
            $this->_defaultController = $c;
    }

    /**
     * Get router information from URI
     * @param string $uri URI string (like: 'site/index', the corresponding HTTP URL may be 'http://www.example.com/site/index')
     * @return array
     */
    public function getRouteUrl($uri = '')
    {
        $uri = ($uri === '') ? Rays::app()->request()->getRequestUriInfo() : $uri;
        $this->proccessUri($uri);
        return $this;
    }

    /**
     * Processes the query uri and transforms the params into route
     * @param string $uri like 'user/view/1'
     * @return array $route
     */
    public function proccessUri($uri)
    {
        if (($pos = strpos($uri, "?")))
            $uri = substr($uri, 0, $pos);

        if (($pos = strpos($uri, "&&")))
            $uri = substr($uri, 0, $pos);

        $uri = explode("/", $uri);
        $this->_controller = isset($uri[0]) ? $uri[0] : null;
        $this->_action = isset($uri[1]) ? $uri[1] : null;
        $this->_params = array_slice($uri, 2);
    }

    /**
     * Get controller from route
     * @return controller ID or null if no controller is provided from the query uri
     */
    public function getControllerId()
    {
        return $this->_controller ? $this->_controller : $this->_defaultController;
    }

    /**
     * Get action from route
     * @return string action ID or null if no action is provided from the query uri
     */
    public function getActionId()
    {
        return $this->_action;
    }

    /**
     * Get parameters array from route
     * @return array params
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Add additional params
     * @param $params
     */
    public function addParams($params)
    {
        $this->_params = array_merge($this->_params, is_array($params) ? $params : array($params));
    }
}