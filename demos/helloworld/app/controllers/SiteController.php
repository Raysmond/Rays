<?php
/**
 * SiteController class
 *
 * @author: Raysmond
 * @created: 2013-12-28
 */

class SiteController extends RController
{

    public function actionIndex()
    {
        $this->render("index");
    }

    public function actionAbout()
    {
        $this->render("about");
    }

    public function actionException(Exception $e)
    {
        if ($e->getCode() == 404 || $e instanceof RPageNotFoundException) {
            $this->renderContent("<h1>Page not found!</h1>{$e->getMessage()}");
        } else if (Rays::app()->isDebug()) {
            print $e;
            exit;
        } else
            $this->renderContent("Error: {$e->getMessage()}<br/>" . "code: {$e->getCode()}");
    }
}