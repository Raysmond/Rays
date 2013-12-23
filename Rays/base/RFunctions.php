<?php
/**
 * Some functions
 *
 * @author: Raysmond
 * @created: 2013-12-23
 */

// array_replace_recursive() requires PHP>=5.3
if (!function_exists('array_replace_recursive'))
{
    function array_replace_recursive($array, $array1)
    {
        function recurse($array, $array1)
        {
            foreach ($array1 as $key => $value)
            {
                // create new key in $array, if it is empty or not an array
                if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key])))
                {
                    $array[$key] = array();
                }

                // overwrite the value in the base array
                if (is_array($value))
                {
                    $value = recurse($array[$key], $value);
                }
                $array[$key] = $value;
            }
            return $array;
        }

        // handle the arguments, merge one by one
        $args = func_get_args();
        $array = $args[0];
        if (!is_array($array))
        {
            return $array;
        }
        for ($i = 1; $i < count($args); $i++)
        {
            if (is_array($args[$i]))
            {
                $array = recurse($array, $args[$i]);
            }
        }
        return $array;
    }
}

// get_called_class() requires PHP>=5.3
if (!function_exists('get_called_class')) {
    function get_called_class($bt = false, $l = 1)
    {
        if (!$bt) $bt = debug_backtrace();
        if (!isset($bt[$l])) throw new Exception("Cannot find called class -> stack level too deep.");
        if (!isset($bt[$l]['type'])) {
            throw new Exception ('type not set');
        } else switch ($bt[$l]['type']) {
            case '::':
                $lines = file($bt[$l]['file']);
                $i = 0;
                $callerLine = '';
                do {
                    $i++;
                    $callerLine = $lines[$bt[$l]['line'] - $i] . $callerLine;
                } while (stripos($callerLine, $bt[$l]['function']) === false);
                preg_match('/([a-zA-Z0-9\_]+)::' . $bt[$l]['function'] . '/',
                    $callerLine,
                    $matches);
                if (!isset($matches[1])) {
                    // must be an edge case.
                    throw new Exception ("Could not find caller class: originating method call is obscured.");
                }
                switch ($matches[1]) {
                    case 'self':
                    case 'parent':
                        return get_called_class($bt, $l + 1);
                    default:
                        return $matches[1];
                }
            // won't get here.
            case '->':
                switch ($bt[$l]['function']) {
                    case '__get':
                        // edge case -> get class of calling object
                        if (!is_object($bt[$l]['object'])) throw new Exception ("Edge case fail. __get called on non object.");
                        return get_class($bt[$l]['object']);
                    default:
                        return $bt[$l]['class'];
                }

            default:
                throw new Exception ("Unknown backtrace method type");
        }
    }
}