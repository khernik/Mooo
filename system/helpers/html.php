<?php

namespace Mooo\System\Html;

use \Mooo\System\Url\Url as URL;

/**
 * Preparing and displaying HTML tags.
 * 
 * @author khernik
 */
class Html {
	
	/**
	 * @var array $attributes
	 */
	public static $attributes = [];
	
	/**
	 * Includes one css file through css external HTML link.
	 * 
	 * 		<link rel="stylesheet" href="media/css/file.css">
	 * 
	 * @param string $path
	 * @param string $rel
	 * @return string
	 */
	public static function style($path = '', $rel = 'stylesheet')
	{
		HTML::$attributes['rel']  = $rel;
		HTML::$attributes['href'] = (\Mooo\System\Url\Url::is_url($path) === FALSE) 
										? URL::base(TRUE) . $path
										: $path;
		
        return '<link ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
	 * Including one or more than one css external links.
	 * 
	 * @param array $paths
	 * @return string
     */
    public static function styles($paths = [], $rel = 'stylesheet')
    {
    	$styles = '';
    	
    	foreach($paths as $path)
    	{
    		$styles .= Html::style($path, $rel) . EOL;
    	}
    	
    	return $styles;
    }
    
    /**
     * Includes one javascript file through javascript external HTML link.
     *
     * 		<script src="file.js"></script>
     *
     * @param string $path
     * @return string
     */
    public static function script($path = '')
    {
    	HTML::$attributes['type'] = 'text/javascript';
    	HTML::$attributes['src'] = (\Mooo\System\Url\Url::is_url($path) === FALSE)
    							   		? URL::base(TRUE) . $path
    							   		: $path;
    
    	return '<script ' . HTML::compile() . '></script>'.PHP_EOL;
    }
    
    /**
	 * Including one or more than one javascript external links.
	 * 
	 * @param array $paths
	 * @return string
     */
    public static function scripts($paths = [])
    {
    	$styles = '';
    	
    	foreach($paths as $path)
    	{
    		$styles .= Html::script($path) . EOL;	
    	}
    	
    	return $styles;
    }
    
    /**
	 * Adds meta tags to the website.
	 * 
	 * @param string $name
	 * @param string $content
	 * @param string $http_equiv
	 * @return string
     */
    public static function meta($name, $content, $http_equiv = NULL)
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['content'] = $content;
    	
    	if($http_equiv !== NULL)
    	{
    		HTML::$attributes['http-equiv'] = $http_equiv;
    	}
    	
    	return '<meta ' . HTML::compile() . '>';
    }
    
    /**
	 * Adds charset meta tag. The charset is specified in the mooo core
	 * attributes, and it's loded from there.
	 * 
	 * @return string
     */
    public static function charset()
    {
    	HTML::$attributes['charset'] = \Mooo\System\Core\Mooo::get_charset();
    	
    	return '<meta ' . Html::compile() . '>';
    }
    
    /**
     * Writes an anchor
     * 
     * 		<a href="#">link</a>
     * 
     * @param string $href
     * @param string $text
     * @param array $parameters
     */
    public static function anchor($uri = '', $text = 'link', $parameters = [])
    {
    	if(\Mooo\System\Url\Url::is_url($uri) === TRUE)
    	{
			$href = $uri;
    	}
    	else
    	{
    		$href = (! $uri) ? URL::base(TRUE) : URL::base(TRUE) . \Mooo\System\Core\Mooo::$index_file . $uri;
    	}
    	
    	HTML::$attributes['href'] = $href;
    	
    	// Additional attributes
    	foreach($parameters as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	
    	return '<a ' . HTML::compile() . '>' . $text . '</a>';
    }
    
    /**
     * Writes an IMG tag
     * 
     * 		<img src="www.url.com">
     * 
     * @param string $src
     * @param string $alt
     * @param array $attributes
     */
    public static function image($uri = '', $alt = '', $attributes = [])
    {
    	HTML::$attributes['src'] = URL::base(TRUE) . $uri;
    	HTML::$attributes['alt'] = $alt;
    	
 		// Additional attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    
    	return '<img ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
	 * Displays ul/ol html list
	 * 
	 * @param string $type
	 * @param array $content
	 * @return string
     */
    public static function _list($type, $content, $depth = 0)
    {
    	
    	$list .= nbs($depth*4) . '<' . $type . '>' . EOL;
    	
    	foreach($content as $li)
    	{
    		if(! is_array($li))
    		{
    			$list .= nbs($depth*4) . '<li>' . $li . '</li>' . EOL;
    		}
    		else
    		{
    			Html::_list($type, $li);
    		}
    	}
    	
    	$list .= nbs($depth*4) . '</' . $type . '>' . EOL;
    	
    	return $list;
    }
    
    /**
	 * Returns multiple <br> tags
	 * 
	 * @param integer $repeat
	 * @return string
     */
    public static function br($repeat = 1)
    {
    	return str_repeat('<br>', $repeat);
    }
    
    /**
	 * Returns non-breaking space
	 * 
	 * @param integer $repeat
	 * @return string
     */
    public static function nbs($repeat = 1)
    {
    	return str_repeat('&nbsp', $repeat);
    }
    
    /**
     * Compiling all given attributes and creating the tag's body out of this
     */
    public static function compile()
    {
    	$body = '';    	 
    	foreach(HTML::$attributes as $name => $value)
    	{
    		$body .= \Mooo\System\Libraries\Security::xss_clean($name . '="' . $value . '" ');
    	}
    	 
    	// Cleaning array so that the new one can be created instead
    	HTML::$attributes = [];
    	
    	return $body;
    }
	
} // End \Mooo\System\Helpers\Html
