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
        User::AUTHENTICATED => array("index","new","edit","delete")
    );

    public function actionIndex()
    {
        $posts = Post::find("uid", Rays::user()->id)->order_desc("id")->all();
        $this->setHeaderTitle("My posts");
        $this->render("index", array("posts" => $posts));
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
        $post = null;
        if (($post = Post::get($pid)) === null) {
            Rays::app()->page404("Post not found!");
        }
        if (!Rays::user()->id == $post->id || !Rays::user()->role == "admin") {
            $this->flash("error", "Permission denied! You don't have the permission to edit the post!");
            $this->redirectAction("post", "view", $post->id);
        }

        $data = array('post' => $post, 'form' => $_POST);
        if (Rays::isPost()) {
            $post->assign($_POST);
            if ($post->validate_save("edit") !== false) {
                $this->flash("message", "Post edit successfully.");
                $this->redirectAction("post", "view", $post->id);
            } else {
                $data['errors'] = $post->getErrors();
            }
        }

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

        $this->render("edit", array('isNew' => true));
    }

    public function actionDelete($postId)
    {
        if (($post = Post::get($postId)) !== null) {
            if ((Rays::user()->id == $postId || Rays::user()->role == "admin")) {
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