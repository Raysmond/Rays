<?php
/**
 * Post model
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class Post extends Model
{
    public $user;
    public $id, $uid, $title, $content, $createdTime;

    public static $table = "post";
    public static $primary_key = "id";

    public static $mapping = array(
        'id' => 'pid',
        'uid' => 'uid',
        'title' => 'title',
        'content' => 'content',
        'createdTime' => 'created_time'
    );

    public static $relation = array(
        'user' => array('User', "[uid] = [User.id]")
    );

    public static $rules = array(
        'uid' => array("label" => "Author ID", "rules" => "trim|required|number"),
        "title" => array("label" => "Title", "rules" => "trim|required|min_length[5]|max_length[255]"),
        "content" => array("label" => "Content", "rules" => "trim|required|max_length[65535]")
    );

    public function save()
    {
        if (!isset($this->createdTime) && !isset($this->id)) {
            $this->createdTime = date('Y-m-d H:i:s');
        }
        return parent::save();
    }
} 