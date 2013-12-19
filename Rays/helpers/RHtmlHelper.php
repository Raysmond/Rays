<?php
/**
 * RHtmlHelper class file.
 *
 * @author: Raysmond
 */

class RHtmlHelper
{

    public static function encode($content)
    {
        if ( get_magic_quotes_gpc() )
            return htmlspecialchars(stripslashes($content), ENT_QUOTES, (Rays::app()->charset));
        else return htmlspecialchars(($content), ENT_QUOTES, (Rays::app()->charset));
    }

    public static function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    public static function getPlainText($html){
        return strip_tags($html);
    }

    public static function tryCleanLink($link)
    {
        if (Rays::app()->isCleanUri())
            return str_replace("?q=", "", $link);
        else return $link;
    }

    /**
     * Return site url
     * @param $url like "site/about"
     * @return string like: /FDUGroup/site/about
     * or /FDUGroup/?q=site/about
     */
    public static function siteUrl($url)
    {
        return self::tryCleanLink(Rays::app()->getBasePath().'/'. $url);
    }

    public static function internalUrl($url){
        if(strpos($url,"//")>0){
            return str_replace(Rays::baseUrl().'/',"",$url);
        }
        return $url;
    }

    public static function linkAction($controller, $name, $action = null, $params = null,$attributes=array())
    {
        $link = "?q=" . $controller;
        if (isset($action) && $action != '')
            $link .= "/" . $action;
        if (isset($params)) {
            if (!is_array($params)) {
                $link .= "/" . $params;
            } else {
                foreach ($params as $param) {
                    $link .= "/" . $param;
                }
            }
        }
        return self::link($name, $name, Rays::app()->getBasePath() . "/" . $link,$attributes);
    }

    public static function link($title, $content, $href, $attributes=array())
    {
        if(isset($attributes['_encode'])&&$attributes['_encode']!=false){
            $content = self::encode($content);
        }
        return '<a '.self::parseAttributes($attributes).' title="' . $title . '" href="' . self::tryCleanLink($href) . '" >' . $content . '</a>';
    }

    public static function linkWithTarget($title, $content, $href, $target)
    {
        return '<a title="' . $title . '" href="' . self::tryCleanLink($href) . '" target="' . $target . '" >' . self::encode($content) . '</a>';
    }

    public static function linkCssArray($cssArray)
    {
        if (!is_array($cssArray))
            return "";
        else {
            $html = "";
            foreach ($cssArray as $css) {
                $html .= self::css($css) . "\n";
            }
            return $html;
        }
    }

    public static function linkScriptArray($scriptArray)
    {
        if (!is_array($scriptArray))
            return "";
        else {
            $html = "";
            foreach ($scriptArray as $script) {
                $html .= self::script($script) . "\n";
            }
            return $html;
        }
    }

    public static function css($cssPath)
    {
        if(!(strpos($cssPath,'//')>0)){
            $cssPath = Rays::app()->getBaseUrl().$cssPath;
        }
        return '<link rel="stylesheet" type="text/css" href="' . $cssPath . '" />';
    }

    public static function script($scriptPath)
    {
        if(!(strpos($scriptPath,'//')>0)){
            $scriptPath = Rays::app()->getBaseUrl().$scriptPath;
        }
        return '<script type="text/javascript" src="'. $scriptPath . '"></script>';
    }

    public static function showFlashMessages(){
        $session = Rays::app()->getHttpSession();
        $messages = '';
        if(($message = $session->getFlash("message"))!=false){
            //print_r($message);
            foreach($message as $m)
                $messages.='<div class="alert alert-info">' .$m. '</div>';
        }
        if(($warnings = $session->getFlash("warning"))!=false){
            foreach($warnings as $warning)
                $messages.='<div class="alert alert-warning">' .$warning. '</div>';
        }
        if(($errors = $session->getFlash("error"))!=false){
            foreach($errors as $error)
                $messages.='<div class="alert alert-danger">' .$error. '</div>';
        }
        return $messages;
    }

    public static function parseAttributes($attributes, $defaults = array())
    {
        if (is_array($attributes)) {
            foreach ($defaults as $key => $val) {
                if (isset($attributes[$key]))
                    $defaults[$key] = $attributes[$key];
                unset($attributes[$key]);
            }
            if (count($attributes) > 0)
                $defaults = array_merge($defaults, $attributes);
        }
        $html = '';
        foreach ($defaults as $key => $val) {
            if(isset($defaults['_encode'])&&$defaults['_encode']!=false)
                $html .= $key . '="' . RHtmlHelper::encode($val) . '" ';
            else $html .= $key . '="' . ($val) . '" ';
        }
        return $html;
    }

    public static function showValidationErrors($validation_errors){
        if(isset($validation_errors)&&$validation_errors!=''&&!empty($validation_errors)){
            echo '<div class="alert alert-danger">';
            foreach($validation_errors as $error){
                foreach($error as $e)
                    foreach($e as $one){
                        echo $one."<br/>";
                    }
            }
            echo '</div>';
        }
    }

    public static function showImage($src,$title='',$attributes = array()){
        $defaults = array();
        if(strpos($src,'://')==false){
            $src = Rays::app()->getBasePath()."/".$src;
        }
        $defaults['src'] = $src;
        $defaults['title'] = $title;
        return '<img '.self::parseAttributes($attributes,$defaults).' />';
    }

    public static function table($tableName='',$tableAttributes=array(),$header=array(),$data=array())
    {
        $html = '<div class="panel panel-default">';
        if($tableName!='')
        {
            $html.='<div class="panel-heading">'.$tableName.'</div>';
        }
        $html.='<table '.self::parseAttributes($tableAttributes).'>';

        if(!empty($header)){
            $html.='<tr>';
            foreach($header as $th){
                $html.='<th>'.$th.'</th>';
            }
            $html.='</tr>';
        }
        if(!empty($data)){
            foreach($data as $tr){
                $html.='<tr>';
                foreach($tr as $td){
                    $html.='<td>'.$td.'</td>';
                }
                $html.='</tr>';
            }
        }

        $html.='</table></div>';

        return $html;
    }
}