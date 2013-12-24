# Rays
Rays is a light-weight MVC framework. Easy and fast! <br/>
Please see the simple blog demo [http://raysmond.com/project/Rays/demos/blog](http://raysmond.com/project/Rays/demos/blog)

## Installation
```bash
git clone https://github.com/Raysmond/Rays.git
```
* Latest version: 0.0.2 (2013-12-24)
* [Change log](https://github.com/Raysmond/Rays/blob/master/CHANGELOG.md)
* PHP version requirement: >=5.2

## Documentation
* Classes API documentation: [http://raysmond.com/project/Rays/docs/api](http://raysmond.com/project/Rays/docs/api)

### Examples
Rays framework is very easy to use and it follows the simple MVC coding style much like [Yii](http://www.yiiframework.com/) framework. Compared with Yii, Rays is much lighter, and easier, but it's not that powerful of course.The following codes are extracted from the [demos/blog](https://github.com/Raysmond/Rays/tree/master/demos/blog). application


* Create an application

```php
// demos/blog/index.php
$rays = dirname(__FILE__).'/../../Rays/Rays.php';
$config = dirname(__FILE__).'/config.php';

require_once($rays);

Rays::newApp($config)->run();
```
* Create a post model

```php
// demos/blog/models/Post.php
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
* Create a controller

```php
// demos/blog/controllers/PostController.php
class PostController extends RController
{
    // Access rules
    public $access = array( User::AUTHENTICATED => array("index", "new", "edit", "delete") );

    // My posts
    public function actionIndex()
    {
        $this->render("index", array("posts" => $Post::find("uid", Rays::user()->id)->order_desc("id")->all()));
    }

    public function actionView($pid)
    {
        $post = Post::find("id", $pid)->join("user")->first();
        RAsset::not_null($post);

        $this->render("view", array('post' => $post));
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

* Create a view file

```php
<!-- demos/blog/views/site/index.php -->
<h1><?=$title?></h1>
<?php
// $self references to the Object who's calling the rendering functions. 
// It may be a controller or a module(widget actually)
$self->setHeaderTitle($title); 
foreach ($newPosts as $post) {
    echo RHtml::linkAction('post', $post->title, 'view', $post->id);
    echo 'by '. RHtml::linkAction('user', $post->user->name, 'view', $post->user->id);
}
```
## Acknowledgements
Thanks for the work of [Xiangyan Sun](https://github.com/wishstudio) and [Renchu Song](https://github.com/RenchuSong).

## License
Copyright © 2013 Jiankun Lei (Raysmond) <br/>
Under BSD license, read the [LICENSE](https://github.com/Raysmond/Rays/blob/master/LICENSE) file
