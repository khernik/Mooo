<?php

namespace Mooo\System\Route;

use \Mooo\System\Url\Url as URL;

/**
 * Very simple routing system.
 * 
 * 		www.url.com/controller/method/argument1/argument2[/...]
 * 
 * @author khernik
 */
class Route {
	
	/**
	 * @var array $_routes
	 */
	protected static $_routes = [];
	
	/**
	 * @var string $_name
	 */
	protected $_name;
	
	/**
	 * @var array $_defaults
	 */
	protected $_defaults;
	
	/**
	 * Returns all routes set inside the application
	 * 
	 * @return array
	 */
	public static function all()
	{
		return Route::$routes;
	}
	
	/**
	 * Set new route
	 * 
	 * @param string $name
	 * @param array $options
	 */
	public static function set($name, $options = [])
	{
		Route::$_routes[$name] = new Route($name, $options);
	}
	
	/**
	 * Set up basic properties of the current route
	 * 
	 * @param string $name
	 * @param array $defaults
	 */
	public function __construct($name, $defaults)
	{
		$this->_name = $_name;
		$this->_defaults = $defaults;
	}
	
	/**
	 * Checks which routes matches the given request object
	 */
	public static function matches(Request $request)
	{
		$uri = $request->uri();
		$directory = explode("/", $uri)[0];
		
		foreach(self::$routes as $name => $route)
		{
			if($name == $directory)
			{
				return $route->_options;
			}
		}
		
		return $this->routes['default']->_options;
	}
	
	/**
	 * Returns the route matching given name
	 * 
	 * @param string $name
	 */
	public static function get($name = 'default')
	{
		return Route::$routes[$name];
	}
	
	/**
	 * Detaches provided route
	 * 
	 * @param string $name
	 */
	public static function detach($name)
	{
		if(! isset(Route::$_routes[$name]))
		{
			return FALSE;
		}
		
		unset(Route::$_routes[$name]);
	}
	
} // End \Mooo\System\Route\Route
