<?php
/**
 * RAction class file.
 * The base class for an action. An action can run called by a controller.
 *
 * @author: Raysmond
 */

abstract class RAction
{

    /**
     * The reference to the controller
     * @var object
     */
    private $controller;

    /**
     * The unique id of the action
     * @var string
     */
    private $id;

    /**
     * The parameters passed to the action
     * @var array
     */
    private $params = array();

    /**
     * Constructor
     * @param $controller
     * @param string $id the unique ID of the action in the controller context
     * @param null $params the parameters passed to the action method
     */
    public function __construct($controller, $id='', $params = null)
    {
        $this->id = $id;
        $this->params = $params;
        $this->controller = $controller;
    }

    /**
     * Run the action. An actual action class must implement the method
     * @return mixed
     */
    abstract function run();

    /**
     * Get the controller of the action
     * @return object
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get the unique action id in the controller context
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get parameters passed to the action method
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

} 