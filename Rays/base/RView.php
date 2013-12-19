<?php
/**
 * Class RView
 *
 * @author: Raysmond
 * @created: 2013-12-19
 */

class RView
{
    /**
     * Render data with a view file
     * @param $file the name of the view file
     * @param $data data to be rendered
     * @param bool $return whether to return the rendered content or just print it
     * @return string rendered content if $return=true or null
     */
    public static function renderFile($file, $data, $return = false)
    {
        self::checkFile($file);

        if (is_array($data))
            extract($data);

        if ($return) {
            ob_start();
            ob_implicit_flush(false);
            require($file);
            return ob_get_clean();
        } else require($file);
    }

    /**
     * Render the data with a given view file
     * @param $file view file name
     * @param $data data to be rendered
     * @param bool $return whether to return the rendered content or just print it
     * @return string rendered content if param $return is true
     */
    public static function renderData($file, $data, $return = false)
    {
        self::checkFile($file);
        $content = self::renderFile($file, $data, true);
        if ($return) {
            return $content;
        } else {
            echo $content;
        }
    }

    /**
     * Check whether the view file really exists.
     * @param $file file name
     * @throws RException
     */
    private static function checkFile($file)
    {
        if (!$file) {
            throw new RException("View file name cannot be empty!");
        }
        if (!is_file($file) || !file_exists($file)) {
            throw new RException("View file $file not exists!");
        }
    }
} 