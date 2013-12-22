<?php
/**
 * UserController class
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class UserController extends RController
{
    /**
     * @var array access array for actions
     */
    public $access = array(
        User::AUTHENTICATED => array("logout")
    );

    public function actionLogin()
    {
        if (Rays::isLogin()) {
            $this->redirect(Rays::baseUrl());
        }

        if (Rays::isPost()) {
            $user = new User($_POST);
            if ($user->validate("login")) {
                $login = User::find("name", $user->name)->first();
                if ($login != null && $login->password == md5($_POST["password"])) {
                    Rays::app()->login($login);
                    $this->redirect(Rays::baseUrl());
                } else {
                    $this->flash("error", "User name and password aren't matched.");
                }
            }
            $this->render("login", array("errors" => $user->getErrors(), "form" => $_POST));
            return;
        }
        $this->render("login");
    }

    public function actionRegister()
    {
        if (Rays::isLogin()) {
            $this->redirect(Rays::baseUrl());
        }

        $data = array();
        if (Rays::isPost()) {
            $data["form"] = $_POST;
            $validation = new RValidation(User::getRegisterRules());
            if ($validation->run($_POST)) {
                $user = new User($_POST);
                $user->assign(array("id" => null, "password" => md5($user->password), "role" => User::AUTHENTICATED));
                if ($user->save()) {
                    $this->flash("message", "Register successfully. Your username is " . $user->name . ".");
                    $this->redirectAction("user", "login");
                }
            }
            $data["errors"] = $validation->getErrors();
        }
        $this->render("register", $data);
    }

    public function actionLogout()
    {
        Rays::app()->logout();
        $this->redirect(Rays::baseUrl());
    }

    public function actionView($uid)
    {
        $user = User::get($uid);
        if ($user === null) {
            Rays::app()->page404();
        }

        $this->render("view", array("user" => $user, "posts" => Post::find("uid", $user->id)->order_desc("id")->range(0, 10)));
    }
} 