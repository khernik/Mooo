<?php

namespace Mooo\System\Url;

/**
 * Class that handles URL-based operations, such as redirections, etc...
 * 
 * @author khernik
 */
class Url {
	
	/**
	 * Returns the current URL address.
	 * 
	 * @return string
	 */
	public static function url()
	{
		// HTTPs - protocol
		$protocol = Url::protocol();
		
		// :8080 - port
		$port = ($_SERVER["SERVER_PORT"] === "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		
		// Whole url HTTP:8080://something.com
		$url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
		
		return $url;
	}
	
	/**
	 * Returns the protocol of the current url
	 * 
	 * @return string
	 */
	public static function protocol()
	{
		// HTTP or HTTPS?
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		
		// HTTP => http
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		
		$protocol = substr($sp, 0 , strpos($sp, "/")) . $s;
		
		return $protocol;
	}
	
	/**
	 * Returns the base url of the application
	 * 
	 * 		e.g. Portfolio out of www.website.com/Portfolio/controller/method
	 * 
	 * @return string
	 */
	public static function base_url()
	{
		return \Mooo\System\Core\Mooo::$base_url;
	}
	
	/**
	 * Returns the base URL of the application - URI
	 * 
	 * 		e.g. /Portfolio/src
	 * 
	 * @param boolean $query
	 * @return string
	 */
	public static function uri($query = FALSE)
	{
		$url = str_replace(Url::base_url(), "", Url::url());
		
		$parse_url = parse_url($url);
		
		if(empty($parse_url['path']) && empty($parse_url['query']))
		{
			return FALSE;
		}
		
		if(! $query)
		{
			// Return just routes
			return $parse_url['path'];
		}
		
		// Return internal routes along with the standard query
		return $parse_url['path'] . '?' . $parse_url['query'];
	}
	
	/**
	 * Returns the query part of the URL if exists
	 * 
	 * 		e.g. ?id=8&login=khernik
	 * 
	 * @return string
	 */
	public static function query()
	{
		$parse_url = parse_url(Url::url());
		
		if(empty($parse_url['query']))
		{
			return FALSE;
		}
		
		return '?' . $parse_url['query'];
	}
	
	/**
	 * Checks if given string is a valid url.
	 * 
	 * @param string $string
	 * @return boolean
	 */
	public static function url_exists($string)
	{
		$token_one = explode("http://", $string);
		$token_two = explode("https://", $string);
				
		return (sizeof($token_one) > 1 || sizeof($token_two) > 1) ? TRUE : FALSE;
	}
	
	/**
	 * Redirects to another URI using header() function.
	 * 
	 * @param string $uri
	 */
	public static function redirect($uri = '', $timeout = 0)
	{		
		$link = (! $uri) ?: URL::site($uri, TRUE);
		
		header('Content-Type: text/html; charset=utf-8');
		
		die('<meta http-equiv="refresh" content="' . (($timeout) ? $timeout : '0') . ';url='.$link.'" />');
	}
	
	/**
	 * Redirects to the previous site.
	 * 
	 * @param integer $timeout
	 */
	public static function referrer($timeout = 0)
	{
		$uri = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
		
		URL::redirect($uri, $timeout);
	}
	
} // End \Mooo\System\Url\Url
