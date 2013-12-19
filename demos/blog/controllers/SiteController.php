<?php
/**
 * SiteController
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class SiteController extends RController
{
    public $defaultAction = "index";
    public $layout = "index";

    public function actionIndex()
    {
        $this->setHeaderTitle(Rays::app()->getName());
        $this->render("index", ["title" => Rays::app()->getName()]);
    }

    public function actionAbout()
    {
        $this->setHeaderTitle("About");
        $this->render("about");
    }

    public function actionContact()
    {
        $this->setHeaderTitle("Contact");
        $this->render("contact");
    }
}