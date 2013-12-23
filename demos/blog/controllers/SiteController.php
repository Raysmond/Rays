<?php
/**
 * SiteController
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class SiteController extends RController
{
    // public $defaultAction = "index";
    // public $layout = "index";

    public function actionIndex()
    {
        $page = Rays::getParam("page", 1);
        $pageSize = Rays::getParam("pagesize", 5);

        // get posts count and posts list in a page
        $count = Post::find()->count();
        $posts = Post::find()->join("user")->order_desc("id")->range(($page - 1) * $pageSize, $pageSize);

        // user pager helper to generate pager HTML
        $pager = new RPager("page", $count, $pageSize, RHtml::siteUrl("site/index"), $page, array('class' => 'pagin'));

        $data = array(
            'title' => Rays::app()->getName(),
            'posts' => $posts,
            'pager' => $pager->showPager()
        );
        $this->setHeaderTitle(Rays::app()->getName());
        $this->render("index", $data);
    }

    public function actionAbout()
    {
        $this->setHeaderTitle("About");
        $this->render("about");
    }

    public function actionContact()
    {
        if (Rays::isPost()) {
            // do some thing

            $this->flash("message", "Thanks for your contact!");
        }
        $this->setHeaderTitle("Contact");
        $this->render("contact");
    }

    /**
     * Exception handling
     * @param Exception $e
     */
    public function actionException(Exception $e)
    {
        if ($e instanceof RPageNotFoundException || $e->getCode() === 404) {
            $this->setHeaderTitle("404");
            $this->renderContent("<h1>404, page not found!</h1>");
            return;
        }

        if (Rays::app()->isDebug())
            print $e;
        else
            $this->renderContent($e->getCode() . '<br/>' . $e->getMessage());
    }
}