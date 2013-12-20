<?php
/**
 * UserController class
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class UserController extends RController
{
    public function actionLogin()
    {
        if (Rays::isLogin()) {
            $this->redirect(Rays::baseUrl());
        }

        if (Rays::isPost()) {
            $user = new User($_POST);
            if ($user->validate("login")) {
                $loginUser = User::find("name", $user->name)->first();
                if ($loginUser != null && $loginUser->password == $_POST["password"]) {
                    Rays::app()->getHttpSession()->set("user", $loginUser->id);
                    $this->redirect(Rays::baseUrl());
                } else {
                    $this->flash("error", "User name and password aren't matched.");
                }
            }
            $this->render("login", ["errors" => $user->getErrors(), "form" => $_POST]);
            return;
        }
        $this->render("login");
    }

    public function actionLogout()
    {
        if (Rays::isLogin()) {
            Rays::app()->getHttpSession()->deleteSession("user");
        }
        $this->redirect(Rays::baseUrl());
    }
} 