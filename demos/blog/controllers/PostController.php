<?php
/**
 * PostController class
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class PostController extends RController
{
    public function actionIndex()
    {
        if (!Rays::isLogin()) {
            $this->flash("message", "Please login first!");
            $this->redirect(Rays::baseUrl());
        }
        $posts = Post::find("uid", Rays::user()->id)->all();
        $this->setHeaderTitle("My posts");
        $this->render("index", ["posts" => $posts]);
    }

    public function actionView($pid)
    {
        $post = null;
        if (!is_numeric($pid) || ($post = Post::find("id", $pid)->join("user")->first()) === null) {
            Rays::app()->page404("Post not found!");
        }

        $this->render("view", ['post' => $post]);
    }

    public function actionEdit($pid)
    {
        $post = null;
        if (!is_numeric($pid) || ($post = Post::get($pid)) === null) {
            Rays::app()->page404("Post not found!");
        }
        if (!Rays::isLogin() || !Rays::user()->id == $post->id || !Rays::user()->role == "admin") {
            $this->flash("error", "Permission denied! You don't have the permission to edit the post!");
            $this->redirectAction("post", "view", $post->id);
        }

        $data = ['post' => $post, 'form' => $_POST];
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
        if (!Rays::isLogin()) {
            $this->flash("message", "Please login first!");
            $this->redirect(Rays::baseUrl());
        }
        if (Rays::isPost()) {
            $post = new Post($_POST);
            $post->uid = Rays::user()->id;
            $post->createdTime = date("Y-m-d H:i:s");
            if ($post->validate_save("new") === false) {
                $this->render("edit", ["isNew" => true, "form" => $_POST, "errors" => $post->getErrors()]);
                return;
            }
            $this->redirectAction("post", "view", $post->id);
        }

        $this->render("edit", ['isNew' => true]);
    }

    public function actionDelete($postId)
    {
        if (($post = Post::get($postId)) !== null) {
            if (Rays::isLogin() && (Rays::user()->id == $postId || Rays::user()->role == "admin")) {
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