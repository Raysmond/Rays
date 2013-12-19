<?php
/**
 * User model
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class User extends RModel
{

    public $id, $roleId, $name, $mail, $password;

    public static $primary_key = "id";

    public static $table = "users";

    public static $mapping = array(
        "id" => "u_id",
        'roleId' => 'u_role_id',
        "name" => "u_name",
        "mail" => "u_mail",
        "password" => "u_password",
        "region" => "u_region",
        "mobile" => "u_mobile",
    );

}
