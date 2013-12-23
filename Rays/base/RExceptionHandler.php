<?php
/**
 * RExceptionHandler class file. It's the default exception handler.
 *
 * @author: Raysmond
 */

class RExceptionHandler
{

    /**
     * Action in a controller which will be called to handle the exception
     * @var string
     */
    public static $exceptionAction = "";

    /**
     * Exception handler. Will call provided action. If none provided, will simply print out the exception object.
     * @param Exception $e Exception object to be handled
     */
    public static function handleException(Exception $e)
    {
        $action = Rays::app()->getExceptionAction();
        if ($action)
            self::$exceptionAction = $action;

        if (self::$exceptionAction != "") {
            Rays::app()->runControllerAction(self::$exceptionAction, $e);
            return;
        }
        print "Exception: <br />";
        print $e;
    }
}