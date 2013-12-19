<?php
/**
 * Class RSessionManager
 *
 * @author: Raysmond
 */
class RSessionManager
{

    public $prefix = "RAYS_SESSION";
    public $cookieDuration = 86400; // 3600*24

    public function __construct()
    {
        session_start();
    }

    /**
     * Get a session property
     * @param $id the ID of the session
     * @return Object|bool the session property or false for not existed session
     */
    function get($id)
    {
        if (isset($_SESSION[$this->prefix][$id]))
            return $_SESSION[$this->prefix][$id];
        else return false;
    }

    /**
     * Set a session property
     * @param $id the ID of the session
     * @param $value
     */
    function set($id, $value)
    {
        $_SESSION[$this->prefix][$id] = $value;
    }

    /**
     * Delete a session property
     * @param $id the ID of the session
     */
    function deleteSession($id)
    {
        if (isset($_SESSION[$this->prefix][$id])) {
            unset($_SESSION[$this->prefix][$id]);
        }
    }

    /**
     * Get a cookie
     * @param $name the name of the cookie
     * @return Object|bool the cookie property or false for not existed cookie
     */
    function getCookie($name)
    {
        return ((isset($_COOKIE[$name])) ? $_COOKIE[$name] : false);
    }

    /**
     * Set a cookie
     * @param string $name the name of the cookie
     * @param $value
     */
    function setCookie($name, $value)
    {
        setcookie($name, $value, time() + $this->cookieDuration, '/');
    }

    /**
     * Delete all cookie
     */
    function deleteAllCookie()
    {
        $cookies = func_get_args();
        foreach ($cookies as $cookie) {
            if ($this->get_cookie($cookie)) {
                setcookie($cookie, 'del', time() - 3600, '/');
            }
        }
    }

    /**
     * Delete all session
     */
    function deleteAllSession()
    {
        $ids = func_get_args();
        foreach ($ids as $id) {
            if ($this->get($id) || is_array($this->get($id))) {
                unset($_SESSION[$this->prefix][$id]);
            }
        }
    }

    /**
     * Set a flash session message. Flash message is valid in the exactly next HTTP request.
     * @param $id the ID of the session message
     * @param $value
     */
    function flash($id, $value)
    {
        if (!isset($_SESSION[$this->prefix]['flash'][$id]))
            $_SESSION[$this->prefix]['flash'][$id] = array();
        array_push($_SESSION[$this->prefix]['flash'][$id], $value);
    }

    /**
     * Get a flash session message
     * @param $id the ID of the session message
     * @return string|bool the message content or false for a not existed message
     */
    function getFlash($id)
    {
        if (isset($_SESSION[$this->prefix]['flash'][$id])) {
            $val = $_SESSION[$this->prefix]['flash'][$id];
            unset($_SESSION[$this->prefix]['flash'][$id]);
            return $val;
        } else return false;
    }
}