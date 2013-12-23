<?php
/**
 * RExceptionHandler class file. It's the default exception handler.
 *
 * @author: Raysmond
 */

class RExceptionHandler
{
    /**
     * Exception handler. Will call provided action. If none provided, will simply print out the exception object.
     * @param Exception $e Exception object to be handled
     */
    public static function handleException(Exception $e)
    {
        if ($action = Rays::app()->getExceptionAction()) {
            Rays::app()->runControllerAction($action, $e);
            return;
        }
        print "Exception: <br />";
        print $e;
    }
}