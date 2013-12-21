<?php
/**
 * RAuth class
 *
 * @author: Raysmond
 * @created: 2013-12-21
 */

class RAuth
{
    /**
     * @var Object the authorized user object or null for anonymous
     */
    private $_user = null;

    /**
     * @var string the auth service provider class name
     */
    private $_authProvider;

    /**
     * @var mixed the unique identifier of the user
     */
    private $_identifier = null;

    /**
     * The SESSION key for user
     */
    const AUTH_KEY = "rays_user";

    /**
     * Get the unique identifier of the User
     * @return string|integer|mixed
     */
    public function getIdentifier()
    {
        if (!isset($this->_identifier)) {
            $_id = Rays::app()->getHttpSession()->get(self::AUTH_KEY);
            if ($_id != false)
                $this->_identifier = $_id;
        }
        return $this->_identifier;
    }

    /**
     * Get the authorized user
     * @return Object the user object or null
     * @throws RException
     */
    public function getUser()
    {
        if (!isset($this->_user) && $this->isLogin()) {
            $provider = $this->_authProvider;
            $provider = new $provider();
            if ($provider instanceof RAuthProvider) {
                $this->_user = $provider->getAuthorizedUser($this->getIdentifier());
            } else {
                throw new RException("Class {$this->_authProvider} doesn't implement the IAuth interface!");
            }
        }
        return $this->_user;
    }

    /**
     * Set the auth provider class name
     * @param $providerClass
     */
    public function setAuthProviderClass($providerClass)
    {
        $this->_authProvider = $providerClass;
    }

    /**
     * Check whether the current user has the authority
     * @param $authority
     */
    public function hasAuthority($authority = null)
    {
        $user = $this->getUser();
        if ($user === null) {
            $provider = $this->_authProvider;
            $user = new $provider();
        }
        return $user->isAuthorized($authority);
    }

    /**
     * Login method
     * @param RAuthProvider $user
     */
    public function login(RAuthProvider $user)
    {
        $identifier = $user->identifier();
        if (isset($identifier) && !empty($identifier)) {
            Rays::app()->getHttpSession()->set(self::AUTH_KEY, $user->identifier());
            $this->_identifier = $identifier;
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        if ($this->isLogin()) {
            Rays::app()->getHttpSession()->deleteSession(self::AUTH_KEY);
        }
    }

    /**
     * Whether the user has login
     * @return bool
     */
    public function isLogin()
    {
        return Rays::app()->getHttpSession()->get(self::AUTH_KEY) !== false;
    }

}