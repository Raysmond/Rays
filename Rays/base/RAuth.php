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
    const AUTH_KEY = "Rays_User";

    /**
     * Get the unique identifier of the User
     * @return string|integer|mixed
     */
    public function getIdentifier()
    {
        if (!isset($this->_identifier)) {
            if (($id = Rays::app()->session()->get(self::AUTH_KEY)) !== false)
                $this->_identifier = $id;
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
            if (!$this->isSetAuthProvider()){
                throw new RException("Auth provider is not set! ");
            }

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
     * @return bool
     */
    public function hasAuthority($authority = null)
    {
        // if no auth provide then it'll return true for all authority requirements
        if (!$this->isSetAuthProvider())
            return true;

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
            Rays::app()->session()->set(self::AUTH_KEY, $user->identifier());
            $this->_identifier = $identifier;
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        if ($this->isLogin()) {
            Rays::app()->session()->deleteSession(self::AUTH_KEY);
        }
    }

    /**
     * Whether the user has login
     * @return bool
     */
    public function isLogin()
    {
        return Rays::app()->session()->get(self::AUTH_KEY) !== false;
    }

    /**
     * Check whether the auth provider class is set
     */
    private function isSetAuthProvider()
    {
        return isset($this->_authProvider) && !empty($this->_authProvider);
    }

}