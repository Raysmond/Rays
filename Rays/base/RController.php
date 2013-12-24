<?php
/**
 * RController class file.
 * This is the base controller for all controllers in Rays framework.
 *
 * @author: Raysmond
 */
class RController
{
    /**
     * The layout used in the controller
     * @var string the layout view file in 'view/layout' directory
     */
    public $layout;

    /**
     * default action is provided if there's no action requested from the URL
     * @var string
     */
    public $defaultAction = "index";

    /**
     * current action id
     * @var string
     */
    private $_action;

    /**
     * Parameters passed to the action method
     * @var array
     */
    private $_params = array();

    /**
     * Header title within the <title> tag in HTML
     * @var string
     */
    private $_headerTitle;

    /**
     * Define accessibility for actions, like restrict some actions only accessible
     * by administrators
     * @var array access definition array, for example:
     * array(
     *     "administrator"=>array("admin","edit"),
     *     "authenticated"=>array("edit")
     * )
     */
    public $access = array();

    /**
     * the unique ID of the controller
     * @var string
     */
    private $_id = '';

    /**
     * Construction method
     * @param string $id the unique id of the controller
     */
    public function __construct($id = '')
    {
        if ($id)
            $this->_id = $id;

        if (!isset($this->layout)) {
            $layout = Rays::app()->getConfig("layout");
            $this->layout = $layout;
        }
    }

    /**
     * Set the unique ID of the controller
     * @param string $id
     */
    public function setId($id = '')
    {
        $this->_id = $id;
    }

    /**
     * Get the unique ID of the controller
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Render a view
     * @param string $view the name of the view used to render the data
     * @param array /null $data data in array which need to be rendered
     * @param bool $return whether to return the rendered content or not
     * @return mixed the rendered content if $return=true or null
     */
    public function render($view, $data = null, $return = false)
    {
        $output = RView::renderData($this, $this->getViewFile($view), $data, true);
        $layout = $this->getLayoutFile($this->layout);
        return RView::renderFile($this, $layout, array('content' => $output), $return);
    }

    /**
     * Render content directly
     * @param string $content
     * @param bool $return
     * @return string
     */
    public function renderContent($content = '', $return = false)
    {
        $layout = $this->getLayoutFile($this->layout);
        return RView::renderFile($this, $layout, array('content' => $content), $return);
    }

    /**
     * Render partial content without the layout
     * @param $view the name of the view file used to render the data
     * @param $data data in array which need to be rendered
     * @param bool $return whether to return the rendered content or not
     * @return string the rendered content if $return=true or null
     */
    public function renderPartial($view, $data, $return = false)
    {
        return RView::renderData($this, $this->getViewFile($view), $data, $return);
    }

    /**
     * Get layout file.
     * @param $layoutName
     * @return the layout file name or false if the file not exists
     */
    public function getLayoutFile($layoutName)
    {
        return Rays::app()->getLayoutPath() . "/$layoutName.php";
    }

    /**
     * Get view file
     * @param $viewName if no '.' is in the the $viewName string, then the method will get view file under
     *  the directory of current controller Id within the base view directory, otherwise, the method will
     *  get the view file from the base view directory
     * @return string the file name of the view or false if the file not exists
     */
    public function getViewFile($viewName)
    {
        $viewFile = Rays::app()->getViewPath() . "/";
        if (strpos($viewName, ".") > 0)
            $viewFile .= str_replace(".", "/", $viewName) . ".php";
        else
            $viewFile .= $this->getId() . "/{$viewName}.php";

        return $viewFile;
    }

    /**
     * Method called before the runAction method
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {
        return true;
    }

    /**
     * Method called after the runAction method
     */
    public function afterAction()
    {

    }

    /**
     * Whether the current user has the right to view the page
     * @return bool
     * todo remove from controller
     */
    public function userCanAccessAction()
    {
        return true;
    }

    /**
     * Check whether the user has the authority to access the action
     */
    public function isAuthorizedUser()
    {
        $authority = null;
        $action = $this->getCurrentAction();
        if (!empty($this->access)) {
            foreach ($this->access as $_authority => $actions) {
                $actions = (!is_array($actions)) ? array($actions) : $actions;
                if (in_array($action, $actions))
                    $authority = $_authority;
            }
        }
        return Rays::app()->getAuth()->hasAuthority($authority);
    }

    /**
     * Run an action, handle the parameters(usually GET or POST method), interact with the data models
     * and decide whether to rendering a page in HTML or just return some content
     * @param $action string action ID
     * @param $params array parameters
     */
    public function runAction($action = '', $params = array())
    {
        if ($action == '')
            $action = $this->defaultAction;

        $this->setCurrentAction($action);
        $this->setActionParams($params);

        if ($this->beforeAction($action) == false) {
            return false;
        }

        // TODO remove userCanAccessAction()
        if (!$this->isAuthorizedUser() || !$this->userCanAccessAction()) {
            throw new RPageNotFoundException("Sorry, you're not authorized to view the requested page.");
        }

        $method = $this->actionMethod();

        if (method_exists($this, $method))
            call_user_func_array(array($this, $method), $this->_params);
        else
            throw new RPageNotFoundException("No actions match the HTTP request!");
        $this->afterAction();
    }

    /**
     * Dispatch an action
     * @param string $actionPath the path of the action class
     * @param string $actionId the unique id of the target action
     * @return mixed
     * @throws RException
     */
    public function dispatchAction($actionId, $actionPath = '')
    {
        if ($actionPath !== '') {
            $className = end(explode(".", $actionPath));
            Rays::import($actionPath);
            if (class_exists($className)) {
                $action = new $className($this, $actionId, $this->getActionParams());
                if (method_exists($action, "run")) {
                    return $action->run();
                } else {
                    throw new RException("An action class must implements \'run\' method!");
                }
            } else {
                $file = Rays::app()->getBaseDir() . str_replace(".", "/", $actionPath) . ".php";
                throw new RException("Class ($className) not exists. Class file: $file");
            }
        }
    }

    /**
     * Add a css asset file
     * @param $cssPath
     */
    public function addCss($cssPath)
    {
        Rays::app()->client()->registerCss($cssPath);
    }

    /**
     * Add a javascript asset file
     * @param $jsPath
     */
    public function addJs($jsPath)
    {
        Rays::app()->client()->registerScript($jsPath);
    }

    /**
     * Set header title for the page in <title></title>
     * @param $title
     */
    public function setHeaderTitle($title)
    {
        $this->_headerTitle = $title;
        Rays::app()->client()->setHeaderTitle($title);
    }

    public function getHeaderTitle()
    {
        return $this->_headerTitle;
    }

    /**
     * Get current action
     * @return mixed
     */
    public function getCurrentAction()
    {
        return $this->_action;
    }

    /**
     * Set current action
     * @param $action
     */
    public function setCurrentAction($action)
    {
        $this->_action = $action;
        if (!isset($this->_action)) {
            $this->_action = $this->defaultAction;
        }
    }

    /**
     * Get action params array
     * @return params array
     */
    public function getActionParams()
    {
        return $this->_params;
    }

    /**
     * Set parameters for the current action
     * @param $params
     */
    public function setActionParams($params)
    {
        $this->_params = $params;
        if ($this->_params == null)
            $this->_params = array();
    }

    /**
     * Generate action method name through action ID
     * For example:
     * action ID = 'view', generated method name = 'actionView'
     * @return string
     */
    public function actionMethod()
    {
        return "action" . ucfirst($this->_action);
    }

    /**
     * Redirect to a new URL
     * @param $url
     */
    public function redirect($url)
    {
        header('location: ' . $url);
        exit;
    }

    /**
     * Redirect to a new action within a specific controller
     * @param string $controller the controller id
     * @param string $action the action id
     * @param $params parameters passed to the action
     */
    public function redirectAction($controller = '', $action = '', $params = array())
    {
        if ($controller == '') $controller = $this->getId();
        header('location: ' . RHtml::siteUrl($this->generateActionLink($controller, $action, $params)));
        exit;
    }


    /**
     * Generate an action link
     * @param $controller
     * @param $action
     * @param $params action parameters
     * @return string action link
     */
    public function generateActionLink($controller = '', $action = '', $params = '')
    {
        $link = "?q=" . ($controller ? $controller : $this->getId());
        if ($action)
            $link .= "/{$action}";
        else {
            $id = $controller ? $controller : $this->getId();
            $class = ucfirst($id) . "Controller";
            if (class_exists($class)) {
                $instance = new $class($id);
                $action = isset($instance->defaultAction) ? $instance->defaultAction : Rays::app()->getConfig("defaultAction");
                $link .= "/{$action}";
            } else
                throw new RException("Controller class not found!");
        }
        if(!empty($params)){
            $params = is_array($params) ? $params : array($params);
            $link .= "/" . implode("/", $params);
        }
        return RHtml::tryCleanLink($link);
    }

    /**
     * Get session manager
     * @return mixed
     */
    public function getSession()
    {
        return Rays::app()->session();
    }

    /**
     * Set flash messages
     * @param $key "message","warinng","error"
     * @param $message
     */
    public function flash($key, $message)
    {
        $this->getSession()->flash($key, $message);
    }

    /**
     * @param $moduleId
     * @param array $params
     */
    public function createModule($moduleId, $params = array())
    {
        Rays::importModule($moduleId);
        $moduleClass = $moduleId . "_module";
        $module = new $moduleClass($params);
        $module->setId($moduleId);
        $module->init();
        return $module;
    }

    /**
     * Creates a module and run it
     * @param $moduleId the unique name of the module
     * @param array $params module properties array
     * @param bool $return whether or not return the output content
     * @return mixed
     */
    public function module($moduleId, $params = array(), $return = false)
    {
        $module = $this->createModule($moduleId, $params);
        if ($return) {
            ob_start();
            ob_implicit_flush(false);
            $module->run();
            return ob_get_clean();
        } else {
            $module->run();
        }
    }
}