<?php
/**
 * RPageAssert helper class
 *
 * @author: Raysmond
 */

class RAssert
{
    const CODE_404 = 404;
    const CODE_NO_PERMISSION = 1;

    /**
     * Assert whether the expression is true or not
     * @param $exp a variable or bool expression
     * @param string $message
     * @param int $code
     * @param string $class
     * @throws
     */
    public static function is_true($exp, $message = '', $code = self::CODE_404, $class='RException')
    {
        if ($exp !== true) {
            throw new $class($message != '' ? $message : "Value not true exception", $code);
        }
    }

    /**
     * Assert whether the value if null or not
     * @param $value
     * @param string $message
     * @param int $code
     * @param string $class
     * @throws RException
     */
    public static function not_null($value, $message = '', $code = self::CODE_404,  $class='RException')
    {
        if ($value === null) {
            throw new $class($message != '' ? $message : "NULL value exception", $code);
        }
    }

    /**
     * Assert whether the value is empty or not
     * @param $value
     * @param string $message
     * @param int $code
     * @param string $class
     * @throws RException
     */
    public static function not_empty($value, $message = '', $code = self::CODE_404, $class='RException')
    {
        if (!is_string($value)) {
            $value = trim($value);
            if ($value == "")
                throw new $class($message != '' ? $message : "Empty string exception", $code);
        }
        if (empty($value)) {
            throw new $class($message != '' ? $message : "Empty value exception", $code);
        }
    }
}
