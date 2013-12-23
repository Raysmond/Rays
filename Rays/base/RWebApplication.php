<?php
/**
 * Class RWebApplication. This is the class for all application instance.
 *
 * @author: Raysmond
 */

class RWebApplication extends RBaseApplication
{
    /**
     * @var string the default controller for the application
     */
    public $defaultController = 'site';

    /**
     * Current controller object
     * @var Object
     */
    public $controller;

    /**
     * @var RRouter the URL router
     */
    public $router;

    /**
     * @var RHttpRequest HTTP request handler
     */
    public $httpRequestHandler;

    /**
     * @var RClient client manager for CSS and JavaScript
     */
    public $client;

    /**
     * @var RSession the session manager
     */
    public $session;

    /**
     * Current user who is accessing the web site
     * @var User|null
     */
    public $user;

    /**
     * @var RAuth the auth object
     */
    private $_auth;

    /**
     * @var array the flash messages array
     */
    public $flashMessage;

    /**
     * Initialization for the whole web application
     */
    public function init()
    {
        parent::init();

        $config = $this->getConfig();
        if (($c = $config->getConfig("defaultController")))
            $this->defaultController = $c;

        Rays::setApp($this);
    }

    /**
     * The first method invoked by application
     */
    public function run()
    {
        parent::run();

        $this->client = new RClient();
        $this->httpRequestHandler = new RHttpRequest();
        $this->router = new RRouter();

        $this->_auth = new RAuth();
        $config = $this->getConfig()->getConfig("authProvider");
        if (isset($config))
            $this->_auth->setAuthProviderClass($config);

        $this->httpRequestHandler->normalizeRequest();
        $this->runController($this->router->getRouteUrl());
    }


    /**
     * Create and run the requested controller
     * @param array $route array router information
     * @throws RPageNotFoundException
     */
    public function runController($route = array())
    {
        $_controller = '';
        if (isset($route['controller']) && $route['controller'] != '') {
            $_controller = $route['controller'] . "Controller";
        } else {
            $_controller = $this->defaultController . "Controller";
            $route['controller'] = $this->defaultController;
        }
        $_controller = ucfirst($_controller);

        if (class_exists($_controller)) {
            $_controller = new $_controller;
            $_controller->setId($route['controller']);
            $this->controller = $_controller;
            $action = isset($route['action']) ? $route['action'] : '';
            $params = isset($route['params']) ? $route['params'] : array();
            $_controller->runAction($action, $params);
        } else
            throw new RPageNotFoundException("No controllers found!");
    }

    /**
     * Run a controller action
     *
     * @param $controllerAction
     * for example:
     * runControllerAction('site/index',['arg1'])
     * </pre>
     *
     * @param array $params
     */
    public function runControllerAction($controllerAction, $params = array())
    {
        $route = $this->router->getRouteUrl($controllerAction);
        if (!is_array($params)) {
            $params = array($params);
        }
        if (isset($route['params'])) {
            $route['params'] = array_merge($route['params'], $params);
        } else
            $route['params'] = $params;
        self::runController($route);
    }

    /**
     * Get controllers path
     * @return string
     */
    public function getControllerPath()
    {
        return $this->getBaseDir() . "/controllers";
    }

    /**
     * Get modules path
     * @return string
     */
    public function getModulePath()
    {
        return $this->getBaseDir() . "/modules";
    }

    /**
     * Get view path
     * @return string
     */
    public function getViewPath()
    {
        return $this->getBaseDir() . "/views";
    }

    /**
     * Get layout path
     * @return string
     */
    public function getLayoutPath()
    {
        return $this->getBaseDir() . "/views/layout";
    }

    /**
     * Get models path
     * @return string
     */
    public function getModelPath()
    {
        return $this->getBaseDir() . "/models";
    }

    /**
     * Get http request handler
     * @return mixed
     */
    public function getHttpRequest()
    {
        return $this->httpRequestHandler;
    }

    /**
     * Get router
     * @return RRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Get client manager
     * @return RClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Get session manager
     * @return RSession
     */
    public function session()
    {
        if (!isset($this->session)) {
            $this->session = new RSession();
        }
        return $this->session;
    }

    public function getAuth()
    {
        return $this->_auth;
    }

    /**
     * Return current login user
     * @return bool login user or false
     */
    public function getLoginUser()
    {
        return $this->_auth->getUser();
    }

    /**
     * Whether the user has login
     * @return bool
     */
    public function isLogin()
    {
        return $this->_auth->isLogin();
    }

    /**
     * User login
     * @param RAuthProvider $user
     */
    public function login(RAuthProvider $user)
    {
        $this->_auth->login($user);
    }

    /**
     * User logout
     */
    public function logout()
    {
        $this->_auth->logout();
    }

    /**
     * Show 404 page.
     */
    public function page404($message = "")
    {
        throw new RPageNotFoundException($message);
    }

    /**
     * Whether is clean URI
     *  For example:
     *  not a clean uri: http://localhost/FDUGroup/?q=site/about
     *  clean uri: http://localhost/FDUGroup/site/about
     * @return bool
     */
    public function isCleanUri()
    {
        return $this->getConfig()->getConfig("isCleanUrl") === true;
    }

    /**
     * Get the current controller who is handling the HTTP request
     * @return Object
     */
    public function getController()
    {
        return $this->controller;
    }

}