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
     * @var string the default layout file name for controllers
     */
    public $layout = 'main';

    const MODULE_FILE_EXTENSION = ".module";

    /**
     * Whether or not user clean uri
     * For example:
     *  not a clean uri: http://localhost/FDUGroup/?q=site/about
     *  clean uri: http://localhost/FDUGroup/site/about
     * @var bool
     */
    public $isCleanUri = false;

    /**
     * @var string the controllers directory path for the application
     */
    public $controllerPath;

    /**
     * @var string the models directory path for the application
     */
    public $modelPath;

    /**
     * @var string the modules directory path for the application
     */
    public $modulePath;

    /**
     * @var string the view directory path for the application
     */
    public $viewPath;

    /**
     * @var string the layout directory path for the application
     */
    public $layoutPath;

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
    public $clientManager;

    /**
     * @var RSessionManager the session manager
     */
    public $httpSession;

    /**
     * Current user who is accessing the web site
     * @var User|null
     */
    public $user;

    /**
     * @var array the flash messages array
     */
    public $flashMessage;

    /**
     * Initialization for the whole web application
     *
     * @param null $config
     */
    public function init($config = null)
    {
        parent::init($config);

        $config = $this->getConfig();

        // Initialize app directories
        $dir = $this->getBaseDir();
        $this->modelPath = $dir.'/models';
        $this->controllerPath =$dir.'/controllers';
        $this->viewPath = $dir.'/views';
        $this->layoutPath = $dir.'/views/layout';
        $this->modulePath = $dir.'/modules';

        if (isset($config['defaultController']))
            $this->defaultController = $config['defaultController'];

        if (isset($config['layout']))
            $this->layout = $config['layout'];

        if (isset($config['isCleanUri']))
            $this->isCleanUri = $config['isCleanUri'];

        Rays::setApp($this);
    }

    /**
     * The first method invoked by application
     */
    public function run()
    {
        parent::run();

        $this->clientManager = new RClient();
        $this->httpRequestHandler = new RHttpRequest();
        $this->router = new RRouter();

        $this->httpRequestHandler->normalizeRequest();
        $this->runController($this->router->getRouteUrl());
    }


    /**
     * Create and run the requested controller
     * @param array $route array router information
     * @throws RPageNotFoundException
     */
    public function runController($route=array())
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
    public function runControllerAction($controllerAction,$params = array()){
        $route = $this->router->getRouteUrl($controllerAction);
        if(!is_array($params)){
            $params = array($params);
        }
        if(isset($route['params'])){
            $route['params'] = array_merge($route['params'], $params);
        }
        else
            $route['params'] = $params;
        self::runController($route);
    }

    /**
     * Show 404 page.
     */
    public function page404()
    {
        throw new RPageNotFoundException();
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
    public function getClientManager()
    {
        return $this->clientManager;
    }

    /**
     * Get session manager
     * @return RSessionManager
     */
    public function getHttpSession()
    {
        if (!isset($this->httpSession)) {
            $this->httpSession = new RSessionManager();
        }
        return $this->httpSession;
    }

    /**
     * Return current login user
     * @return bool login user or false
     */
    public function getLoginUser()
    {
        if ($this->isUserLogin() && !isset($this->user)) {
            $id = $this->getHttpSession()->get("user");
            $this->user = User::find($id)->join("role")->first();
            return $this->user;
        }
        else if (isset($this->user)) {
            return $this->user;
        }
        else {
            return null;
        }
    }

    /**
     * Whether the user has login
     * @return bool
     */
    public function isUserLogin()
    {
        return $this->getHttpSession()->get("user") != false;
    }

    /**
     * Whether is clean URI
     * @return bool
     */
    public function isCleanUri()
    {
        return $this->isCleanUri != false;
    }

    /**
     * Get the current controller who is handling the HTTP request
     * @return Object
     */
    public function getController(){
        return $this->controller;
    }

}