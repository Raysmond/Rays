# Rays
Rays is a light-weight MVC framework. Easy and fast! <br/>
Please see the simple demos [Rays Blog](http://rays.raysmond.com/demos/blog) and [HelloWorld](http://rays.raysmond.com/demos/helloworld).

## Installation
```bash
git clone https://github.com/Raysmond/Rays.git
```
* Latest version: 0.0.2 (2013-12-24)
* [Change log](https://github.com/Raysmond/Rays/blob/master/CHANGELOG.md)
* PHP version requirement: >=5.2

## Documentation
* Classes API documentation: [http://rays.raysmond.com/docs/api](http://rays.raysmond.com/docs/api)
* **A typical MVC request workflow in Rays, very similar to [Yii](http://www.yiiframework.com/)**.

![MVC](https://github-camo.global.ssl.fastly.net/381e26b0594a4891f1c549f21fdbb1abae867662/687474703a2f2f726179736d6f6e642e636f6d2f7075626c69632f612532307479706963616c2532304d564325323072657175657374253230776f726b666c6f77253230636f70792e504e47)

***

1. A client user type the URL `http://localhost/FDUGroup/site/welcome/Raysmond`(`http://localhost/FDUGroup` is the base path of the application) in the browser. Then, index.php will be the first bootstrap file to handle the request.
2. An application instance will be created and initialize the web application.
3. The application will invoke the request handler to normalize the process the HTTP request based on the URL.
4. The application will invoke the router to resolve the URI information. In this example, the result should  be: controller ID = `“site”`, action ID = `“welcome”` and the parameters array = `[“Raysmond”]`.
5. The `SiteController` will be created to handle the current request. An extract action named `“actionWelcome”` will be invoked automatically and “Raysmond” will the first arg passed to the method.
6. The controller may obtain data from database via a `model(RModel)`. 
7. The action renders the data via view file named “welcome.php” as main content
8. Some `modules(widgets)` may be rendered in the layout file(HTML template)
9. The main content will be inserted into the `layout`.
10. Finally, the rendered HTML will be printed, so the user can view the result page

***

### Examples
Rays framework is very easy to use and it follows the simple MVC coding style much like [Yii](http://www.yiiframework.com/) framework. Compared with Yii, Rays is much lighter, and simpler, but it's not that powerful of course.The following codes are extracted from the [demos/blog](https://github.com/Raysmond/Rays/tree/master/demos/blog). application


* **Create an application**

```php
// demos/blog/index.php
$rays = dirname(__FILE__).'/../../Rays/Rays.php';
$config = dirname(__FILE__).'/config.php';

require_once($rays);

Rays::newApp($config)->run();
```
* **Create a post model**

```php
// demos/blog/app/models/Post.php
class Post extends RModel
{
    public $user; // post author
    public $id, $uid, $title, $content, $createdTime; // post attributes

    public static $table = "post";     
    public static $primary_key = "id"; 

    // Protect attributes from massive data assignment like: $post = new Post($_POST)
    public static $protected = array("id", "uid", "createdTime");

    public static $mapping = array(
        'id' => 'pid',
        'uid' => 'uid',
        'title' => 'title',
        'content' => 'content',
        'createdTime' => 'created_time'
    );

    // Belongs-to relation
    public static $relation = array(
        'user' => array('User', "[uid] = [User.id]")
    );

    // Validation rules
    public static $rules = array(
        'uid' => array("label" => "Author ID", "rules" => "trim|required|number"),
        "title" => array("label" => "Title", "rules" => "trim|required|min_length[5]|max_length[255]"),
        "content" => array("label" => "Content", "rules" => "trim|required|max_length[65535]")
    );
}
```
* **Create a controller**

```php
// demos/blog/app/controllers/PostController.php
// Basic CRUD functions for post
class PostController extends RController
{
    // Access rules
    public $access = array( User::AUTHENTICATED => array("index", "new", "edit", "delete") );

    // My posts
    public function actionIndex()
    {
        $this->render("index", array("posts" => $Post::find("uid", Rays::user()->id)->order_desc("id")->all()));
    }

    // Read
    public function actionView($pid)
    {
        $post = Post::find("id", $pid)->join("user")->first();
        RAsset::not_null($post);

        $this->render("view", array('post' => $post));
    }

    // Create
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

    // Update
    public function actionEdit($pid)
    {
        $post = Post::get($pid);
        RAssert::not_null($post);

        $user = Rays::user(); // Current login user
        if (!$user->id === $post->id || !$user->role === User::ADMIN) {
            $this->flash("error", "Permission denied!");
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

        $this->render("edit", $data);
    }

    // Delete
    public function actionDelete($postId)
    {
        if (($post = Post::get($postId)) !== null) {
            if ((Rays::user()->id == $post->uid || Rays::user()->role === User::ADMIN)) {
                $post->delete();
                $this->flash("message", "Post " . $post->title . " was deleted successfully!");
                $this->redirectAction("post", "index");
            } else {
                $this->flash("warning", "Permission denied!");
                $this->redirectAction("post", "view", $post->id);
            }
        }
    }
} 
```

* **Create a view file**

```php
<!-- demos/blog/app/views/post/view.php -->
<?php $self->setHeaderTitle($post->title); ?>
<h1><?= $post->title ?></h1>
<div>
    <?= RHtml::linkAction("user", $post->user->name, "view", $post->user->id) ?>
    posts at <?= $post->createdTime ?>
</div>
<div><?= $post->content ?></div>
```
## Acknowledgements
Thanks for the work of [Xiangyan Sun](https://github.com/wishstudio) and [Renchu Song](https://github.com/RenchuSong).

## License
Copyright © 2013 Jiankun Lei (Raysmond) <br/>
Under BSD license, read the [LICENSE](https://github.com/Raysmond/Rays/blob/master/LICENSE) file.
