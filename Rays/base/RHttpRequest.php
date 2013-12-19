<?php
/**
 * RHttpRequest class file
 *
 * @author: Raysmond
 */
class RHttpRequest
{
    public function normalizeRequest()
    {
        // normalize request
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            if (isset($_GET))
                $_GET = $this->stripSlashes($_GET);
            if (isset($_POST))
                $_POST = $this->stripSlashes($_POST);
            if (isset($_REQUEST))
                $_REQUEST = $this->stripSlashes($_REQUEST);
            if (isset($_COOKIE))
                $_COOKIE = $this->stripSlashes($_COOKIE);
        }
    }

    public function stripSlashes(&$data)
    {
        if (is_array($data)) {
            if (count($data) == 0)
                return $data;
            $keys = array_map('stripslashes', array_keys($data));
            $data = array_combine($keys, array_values($data));
            return array_map(array($this, 'stripSlashes'), $data);
        } else
            return stripslashes($data);
    }

    /**
     * Whether the current HTTP request type is 'POST' or not
     * @return bool
     */
    public function isPostRequest()
    {
        return $this->getRequestType() == "POST";
    }

    /**
     * Get parameter in POST or GET request. GET parameters are returned first.
     * @param $name parameter name
     * @param null $defaultValue default value
     * @return string|null
     */
    public function getParam($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
    }

    /**
     * Get parameter in GET request
     * @param $name parameter name
     * @param null $defaultValue default value
     * @return string|null
     */
    public function getQuery($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
    }

    /**
     * Get parameter in POST request
     * @param $name parameter name
     * @param null $defaultValue default value
     * @return string|null
     */
    public function getPost($name, $defaultValue = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
    }

    /**
     * Get query string in GET request
     * @return string query string
     */
    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * Get current HTTP request type
     * @return string HTTP request type
     */
    public function getRequestType()
    {
        if (isset($_POST['_method']))
            return strtoupper($_POST['_method']);

        return strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
    }

    /**
     * Whether the current HTTP request is Ajax request
     * @return bool
     */
    public function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }


    /**
     * Get server name
     * @return string
     */
    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Get server port
     * @return string
     */
    public function getServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }


    /**
     * Get referrer uri (the HTTP request came from which uri)
     * @return string|null referrer uri
     */
    public function getUrlReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }


    /**
     * Get user agent of HTTP request
     * @return null
     */
    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * Get user host address
     * @return string
     */
    public function getUserHostAddress()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    /**
     * Get user host
     * @return null
     */
    public function getUserHost()
    {
        return isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
    }

    /**
     * Get current request uri
     * @return string
     */
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get useful request uri information form request uri
     * For example:
     * http://localhost/FDUGroup/?q=
     * @return string
     */
    public function getRequestUriInfo()
    {
        $uri = $this->getRequestUri();
        if (($pos = strpos($uri, "?q=")) > 0)
            return substr($uri, $pos + 3);
        else {
            $uri = substr($uri, strlen(Rays::app()->getBaseUrl()) - strlen("http://" . $this->getServerName()) + 1);
            return str_replace("index.php", "", $uri);
        }
    }

    /**
     * Whether the current url matches the rules
     * @param array $urlRules like array('site/about','user/*')
     * @param string $url default the front page
     * @return bool
     */
    public function urlMatch($urlRules = array(), $url = '')
    {
        if (!is_array($urlRules)) {
            $urlRules = array($urlRules);
        }
        if(empty($urlRules)){
            return true;
        }
        // like : user/view/1
        $currentUrl = $url != '' ? $url : $this->getRequestUriInfo();

        // The front page
        if ($currentUrl == '')
            $currentUrl = '<front>';
        foreach ($urlRules as $url)
        {
            if ($url == $currentUrl)
                return true;
            else
            {
                if (($pos = strpos($url, '*')) > 0)
                {
                    $arr = explode('*', $url);
                    $match = true;
                    foreach ($arr as $part)
                    {
                        if ($part == '') continue;
                        if (($apartPos = strpos($currentUrl, $part)) == false)
                        {
                            $sub = substr($currentUrl, 0, strlen($part));
                            if ($sub != $part) { // current pattern not matched
                                $match = false;
                                break;
                            } else {
                                $currentUrl = substr($currentUrl, strlen($part));
                            }
                        } else {
                            $currentUrl = substr($currentUrl, $apartPos + strlen($part));
                        }
                    }
                    // one pattern matched
                    if ($match) return true;
                } else {
                    // do some thing
                }
            }
        }
        return false;
    }

}