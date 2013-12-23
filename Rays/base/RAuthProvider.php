<?php
/**
 * Interface IAuthProvider
 * Classes implemented the interface can provide authorization service for the application.
 * For example:
 * <pre>
 *  class User extends RModel implements RAuthProvider
 *  {
 *  
 *     public $id, $role, $name, $email, $password;
 *     
 *     public static $primary_key = "id";
 *     public static $table = "user";
 *     public static $mapping = [];//Object columns to database columns mapping
 *     
 *     const ADMIN = "admin";
 *     const AUTHENTICATED = "authenticated";
 *     const ANONYMOUS = "anonymous";
 *     
 *     public function identifier()
 *     {
 *         return $this->id;
 *     }
 *     
 *     public function authority()
 *     {
 *          return $this->role;
 *     }
 *     
 *     public function isAuthorized($requiredAuthority = null)
 *     {
 *         if ($requiredAuthority == null || $this->authority() == self::ANONYMOUS || $requiredAuthority == $this->authority())
 *             return true;
 *         if ($this->authority() == self::ADMIN)
 *             return true;
 *         return false;
 *     }
 *     
 *     public function getAuthorizedUser($identifier)
 *     {
 *         return User::get($identifier);
 *     }
 *  }
 * </pre>
 *
 * @author: Raysmond
 * @created: 2013-12-21
 */
interface RAuthProvider
{
    /**
     * Get the unique identifier of user
     * @return mixed
     */
    public function identifier();

    /**
     * Get the authority of user
     * @return mixed
     */
    public function authority();

    /**
     * Whether the user has the required authority.
     * @param null $requiredAuthority
     * @return mixed
     */
    public function isAuthorized($requiredAuthority=null);

    /**
     * Get the authorized user object
     * @param $identifier
     * @return mixed
     */
    public function getAuthorizedUser($identifier);
}