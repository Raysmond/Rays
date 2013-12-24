<?php
/**
 * PostController class
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class PostController extends RController
{
    /**
     * @var array access array for actions
     */
    public $access = array(
        User::AUTHENTICATED => array("index", "new", "edit", "delete")
    );

    public function actionIndex()
    {
        $page = Rays::getParam("page", 1);
        $size = Rays::getParam("pagesize", 5);

        $count = Post::find("uid", Rays::user()->id)->count();
        $posts = Post::find("uid", Rays::user()->id)->order_desc("id")->range(($page - 1) * $size, $size);

        $pager = null;
        if ($count > $size) {
            $pager = new RPager("page", $count, $size, RHtml::siteUrl("post/index"), $page, array('class' => "pagin"));
            $pager = $pager->showPager();
        }

        $this->setHeaderTitle("My posts");
        $this->render("index", array("posts" => $posts, 'count' => $count, 'pager' => $pager));
    }

    public function actionView($pid)
    {
        $post = null;
        if (!is_numeric($pid) || ($post = Post::find("id", $pid)->join("user")->first()) === null) {
            Rays::app()->page404("Post not found!");
        }

        $this->render("view", array('post' => $post));
    }

    public function actionEdit($pid)
    {
        $post = Post::get($pid);
        RAssert::not_null($post);

        $user = Rays::user();
        if (!$user->id == $post->id || !$user->role == User::ADMIN) {
            $this->flash("error", "Permission denied! You don't have the permission to edit the post!");
            $this->redirectAction("post", "view", $post->id);
        }

        $data = array('post' => $post, 'form' => $_POST);
        if (Rays::isPost()) {
            $post->set($_POST);
            if ($post->validate_save("edit") !== false) {
                $this->flash("message", "Post edit successfully.");
                $this->redirectAction("post", "view", $post->id);
            }

            $data['errors'] = $post->getErrors();
        }

        $this->setHeaderTitle("Edit " . $post->title);
        $this->render("edit", $data);
    }

    public function actionNew()
    {
        if (Rays::isPost()) {
            $post = new Post($_POST);
            $post->uid = Rays::user()->id;
            $post->createdTime = date("Y-m-d H:i:s");
            if ($post->validate_save("new") === false) {
                $this->render("edit", array("isNew" => true, "form" => $_POST, "errors" => $post->getErrors()));
                return;
            }
            $this->redirectAction("post", "view", $post->id);
        }

        $this->setHeaderTitle("New post");
        $this->render("edit", array('isNew' => true));
    }

    public function actionDelete($postId)
    {
        if (($post = Post::get($postId)) !== null) {
            if ((Rays::user()->id == $postId || Rays::user()->role == User::ADMIN)) {
                $post->delete();
                $this->flash("message", "Post " . $post->title . " was deleted successfully!");
                $this->redirectAction("post", "index");
            } else {
                $this->flash("warning", "Permission denied! You cannot delete the post!");
                $this->redirectAction("post", "view", $post->id);
            }
        }
    }
} 