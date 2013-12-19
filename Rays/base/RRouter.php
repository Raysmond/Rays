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
     * @var array normalized route uri array
     */
    private $_routeUrl = array();

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
    private $_params;

    /**
     * Get router information from URI
     * @param string $uri URI string (like: 'site/index', the corresponding HTTP URL may be 'http://www.example.com/site/index')
     * @return array
     */
    public function getRouteUrl($uri = '')
    {
        $uri = ($uri === '') ? Rays::app()->getHttpRequest()->getRequestUriInfo() : $uri;

        $this->processUrl($uri);
        return $this->_routeUrl;
    }

    /**
     * Process the URI string
     * @param $uri
     */
    public function processUrl($uri)
    {
        $route = $this->processQueryUrl($uri);

        $this->_routeUrl = $route;
        $this->_controller = isset($route['controller']) ? $route['controller'] : null;
        $this->_action = isset($route['action']) ? $route['action'] : null;
        $this->_params = isset($route['params']) ? $route['params'] : null;
    }

    /**
     * Processes the query uri and transforms the params into route
     * @param string $query like 'user/view/1'
     * @return array $route
     */
    public function processQueryUrl($query)
    {
        if (($pos = strpos($query, "?")))
            $query = substr($query, 0, $pos);

        $query = explode("/", $query);
        $route = array();
        $len = count($query);

        if ($len > 0)
            $route['controller'] = $query[0];

        if ($len > 1)
            $route['action'] = $query[1];

        if ($len > 2) {
            $route['params'] = array();
            for ($i = 2; $i < $len; $i++) {
                if ($query[$i])
                    $route['params'][] = $query[$i];
            }
        }
        return $route;
    }

    /**
     * Get controller from route
     * @return controller ID or null if no controller is provided from the query uri
     */
    public function getControllerId()
    {
        return isset($this->_controller) ? $this->_controller : null;
    }

    /**
     * Get action from route
     * @return string action ID or null if no action is provided from the query uri
     */
    public function getActionId()
    {
        return isset($this->_action) ? $this->_action : null;
    }

    /**
     * Get parameters array from route
     * @return array params
     */
    public function getParams()
    {
        return $this->_params;
    }
}