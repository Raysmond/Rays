<?php
/**
 * RExceptionHandler class file. It's the default exception handler.
 *
 * @author: Raysmond
 */

class RExceptionHandler {

    /**
     * Action in a controller which will be called to handle the exception
     * @var string
     */
    public static $exceptionAction = "";

    /**
     * Set the action to do when the handler is called
     * @param RAction $action Action object
     */
    public static function setExceptionAction($action=''){
        self::$exceptionAction = $action;
    }

    /**
     * Exception handler. Will call provided action. If none provided, will simply print out the exception object.
     * @param Exception $e Exception object to be handled
     */
    public static function handleException(Exception $e)
    {
        if(self::$exceptionAction!=""){
            Rays::app()->runControllerAction(self::$exceptionAction,$e);
            return;
        }
        print "Exception: <br />";
        print $e;
    }
}