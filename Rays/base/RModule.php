<?php
/**
 * RModule class
 * A module is a widget to provide some additional functions or do partial content rendering.
 * To declare a module like the following way:
 * Files:
 * app/modules/new_users/new_users.module
 * app/modules/new_users/new_users.view.php
 * <pre>
 * class new_users_module{
 *     //public $access = array();
 *     //public $denyAccess = array();
 *
 *     public function __construct($params=array()){
 *          // do initialization things
 *     }
 *
 *     public function module_content(){
 *         // render() method will use the "new_users.view.php" under the module directory to render the content
 *         return $this->render("new_users", array("users"=>User::find()->order_des("id")->range(0, 10)));
 *     }
 * }
 * </pre>
 *
 * @author: Raysmond
 */
class RModule
{
    /**
     * @var string The name of the module
     */
    public $name;

    /**
     * @var array  The module shall appear in what pages
     * For example:
     * <pre>array('site/about','user/*') </pre>
     * <front> for the front page
     */
    public $access = array();

    /**
     * @var array  Unlike $access, those pages that match the URI declared here will not see the module
     */
    public $denyAccess = array();

    /**
     * @var string The unique ID of the module
     */
    private $_id;

    /**
     * @var string The path of the module directory
     */
    private $_path;

    /**
     * @var array the initial parameters/configuration for the module
     */
    private $_params = array();

    /**
     * @var string|null the base uri of the modules path. For example: "http://localhost/FDUGroup/app/modules"
     */
    public static $moduleBaseUri = null;

    const MODULE_FILE_EXTENSION = ".module";

    /**
     * Module constructor
     * @param array $params parameters used to initialize the module
     */
    public function __construct($params = array())
    {
        if (isset($params['id']))
            $this->setId($params['id']);

        if (isset($params['name']))
            $this->setName($params['name']);

        $this->init($params);
    }

    /**
     * Get module base URI path
     * @return null|string
     */
    public static function getModuleBasePath()
    {
        if (self::$moduleBaseUri === null) {
            $basePath = substr(Rays::app()->getBasePath(), 1);
            $pos = strpos(Rays::app()->getModulePath(), $basePath) + strlen($basePath) + 1;
            self::$moduleBaseUri = Rays::app()->getBaseUrl() . '/' . substr(Rays::app()->getModulePath(), $pos);
        }
        return self::$moduleBaseUri;
    }

    /**
     * Initial function
     * You should override the method if you wanna do some initial work before
     * running the module
     */
    public function init($params = array())
    {

    }

    /**
     * Run the module
     * This is the place where the module output it's content
     */
    public function run()
    {
        if (!$this->denyAccess() && $this->canAccess()) {
            $content = $this->module_content();
            echo $content;
        } else
            return false;
    }

    /**
     * Module content method
     * This is the right place where you return the output content of the module
     * @return string
     */
    public function module_content()
    {
        return '';
    }

    /**
     * Get the module directory
     * @return string
     */
    public function getModuleDir()
    {
        if (!isset($this->_path)) {
            $this->_path = Rays::app()->getModulePath() . "/" . $this->getId();
        }
        return $this->_path;
    }

    /**
     * Get the module URL path
     * @return string
     */
    public function getModulePath()
    {
        return self::getModuleBasePath() . '/' . $this->getId();
    }

    /**
     * Render a module view and get render content
     * @param string $viewFileName
     * @param string $data
     * @return string
     */
    public function render($viewFileName = '', $data = '')
    {
        $viewFile = $this->getModuleDir() . "/" . $viewFileName . ".view.php";
        RView::renderFile($this, $viewFile, $data, false);
    }

    /**
     * Add css
     * @param $cssPath
     */
    public function addCss($cssPath)
    {
        Rays::app()->client()->registerCss($cssPath);
    }

    /**
     * Add js
     * @param $jsPath
     */
    public function addJs($jsPath)
    {
        Rays::app()->client()->registerScript($jsPath);
    }

    /**
     * Whether the current page can access the module
     */
    public function canAccess()
    {
        return Rays::app()->getHttpRequest()->urlMatch($this->access);
    }

    /**
     * Whether the module cannot be viewed in the current page or not
     * @return bool
     */
    public function denyAccess()
    {
        return empty($this->denyAccess)? false : Rays::app()->getHttpRequest()->urlMatch($this->denyAccess);
    }

    /**
     * Set the unique ID of the module
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Get the unique ID of the module
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the name of the module
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the module
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public static function moduleClass($id)
    {
        return $id."_module";
    }

}