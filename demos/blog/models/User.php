<?php
/**
 * User model
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class User extends Model
{

    public $id, $role, $name, $email, $password;

    public static $primary_key = "id";
    public static $table = "user";
    public static $mapping = array(
        "id" => "uid",
        'role' => 'role',
        "name" => "name",
        "email" => "email",
        "password" => "password",
    );

    public static $rules = array(
        "name" => array("label" => "User name", "rules" => "trim|required|min_length[4]|max_length[255]"),
        "email" => array("apply" => "register", "label" => "Email", "rules" => "trim|required|is_email|max_length[255]"),
        "password" => array("label" => "Password", "rules" => "trim|required|min_length[4]|max_length[255]"),
    );

    const ADMIN = "admin";
    const AUTHENTICATED = "authenticated";
    const ANONYMOUS = "anonymous";

    /**
     * Override save() method
     * @return Id
     */
    public function save()
    {
        if (!isset($this->role)) {
            $this->role = self::AUTHENTICATED;
            return parent::save();
        }
    }

    public static function getRegisterRules()
    {
        $rules = User::getRules("register");
        $rules[] = ["field" => "password-confirm", "label" => "Password confirm", "rules" => "trim|required|min_length[4]|max_length[255]|equals[password]"];
        return $rules;
    }

}
