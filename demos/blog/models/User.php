<?php
/**
 * User model
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class User extends RModel implements RAuthProvider
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

    public static $protected = array("uid","role");

    public static $rules = array(
        "name" => array("label" => "User name", "rules" => "trim|required|min_length[4]|max_length[255]"),
        "email" => array("apply" => "register", "label" => "Email", "rules" => "trim|required|is_email|max_length[255]"),
        "password" => array("label" => "Password", "rules" => "trim|required|min_length[4]|max_length[255]"),
    );

    const ADMIN = "admin";
    const AUTHENTICATED = "authenticated";
    const ANONYMOUS = "anonymous";

    public static function getRegisterRules()
    {
        $rules = User::getRules("register");
        $rules[] = array("field" => "password-confirm", "label" => "Password confirm", "rules" => "trim|required|min_length[4]|max_length[255]|equals[password]");
        return $rules;
    }

    /**
     * Implement identifier() method
     */
    public function identifier()
    {
        return $this->id;
    }

    /**
     * Implement authority() method
     */
    public function authority()
    {
        return $this->role;
    }

    /**
     * Implement isAuthorized() method
     */
    public function isAuthorized($requiredAuthority = null)
    {
        if ($requiredAuthority === null || $requiredAuthority === self::ANONYMOUS || $requiredAuthority === $this->authority())
            return true;
        if ($this->authority() === self::ADMIN)
            return true;
        return false;
    }

    /**
     * Implement getAuthorizedUser() method
     * @param $identifier
     * @return User|null
     */
    public function getAuthorizedUser($identifier)
    {
        return User::get($identifier);
    }
}
