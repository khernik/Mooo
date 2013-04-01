<?php

namespace Mooo\System\Core;

/**
 * Getting/setting request-connected variables, starting the request,
 * and displaying request response.
 * 
 * User_agent and IP are set only in the initial request.
 * 
 * This classed can be called fron controller level with various ways:
 * 
 * 		$this->request->referrer();
 * 		Request::current()->referrer();
 * 		Request::$current->referrer();
 * 
 * @author khernik
 */
class Request {
	
	/**
	 * @var object $initial
	 */
	public static $initial;
	
	/**
	 * @var object $current
	 */
	public static $current;
	
	/**
	 * @var boolean $is_ajax
	 */
	protected static $is_ajax = FALSE;
	
	/**
	 * @var string $response
	 */
	public $response = '';
	
	/**
	 * Get the current request's instance
	 */
	public static function current()
	{
		return Request::$current;
	}
	
	/**
	 * Set attributes, return the class's object - singleton pattern to keep
	 * access to the attributes.
	 * 
	 * @param string Constructor/Method/Argument1/Argument2(...)
	 * @return object Current request object
	 */
	public static function factory($uri = NULL)
	{
		// If it's the initial request
		if(! Request::$initial)
		{
			// Only this will be set for initial request
			Request::setUserAgent();					
			Request::setIP();
			
			if(isset($_SERVER['REQUEST_METHOD']))
			{
				$type = $_SERVER['REQUEST_METHOD'];
			}
			if(isset($_SERVER['SERVER_PROTOCOL']))
			{
				$protocol = $_SERVER['SERVER_PROTOCOL'];
			}
			if(isset($_SERVER['HTTP_REFERER']))
			{
				$referrer = $_SERVER['HTTP_REFERER'];
			}
			
			Request::$initial = Request::$current = $request = new Request();
		}
		else
		{
			Request::$current = $request = new Request();
		}
		
		// Check if it's ajax request
		if(Request::is_ajax())
		{
			Request::$is_ajax = TRUE;
		}
		
		// Set some basic request options
		if(isset($type))
		{
			$request->setType($type);
		}
		if(isset($protocol))
		{
			$request->setProtocol($protocol);
		}
		if(isset($referrer))
		{
			$request->setReferrer($referrer);
		}
		
		$this->_uri = \Mooo\System\Helpers\Url\Url::uri();
		
		return Request::$current;
	}
	
	/**
	 * Checks if it's the ajax request.
	 *
	 * @return boolean
	 */
	public static function is_ajax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Sets up the proper route along with the current controller, method, parameters, and 
	 * the directory
	 */
	public function process()
	{
		// Find route maching the current URI schema
		$route = \Mooo\System\Route\Route::matches($this);
		
		// Exlode URI into "route" parts
		$parse_uri = explode("/", $this->uri());
		
		if($route->_name == 'default')
		{
			if(isset($parse_uri[0]))
			{
				$controller = $parse_uri[0];
			}
			else
			{
				$controller = $route->_options['defaults']['controller'];
			}
			
			if(isset($parse_uri[1]))
			{
				$method = $parse_uri[1];	
			}
			else
			{
				$method = $route->_options['defaults']['method'];
			}
			
			if(isset($parse_uri[2]))
			{
				$parameters = $parse_uri[2];
			}
		}
		else
		{
			$directory = $route->_name;
			
			if(isset($parse_uri[1])) 
			{
				$controller = $parse_uri[1];		
			}
			else
			{
				$controller = $route->_options['defaults']['controller'];
			}
			
			if(isset($parse_uri[2]))
			{
				$method = $parse_uri[2];		
			}
			else
			{
				$method = $route->_options['defaults']['method'];
			}
			
			if(isset($parse_uri[3]))
			{
				$parameters = $parse_uri[3];
			}
		}
		
		// Set up controller
		$this->controller($controller, $directory);
		
		// Set up action method
		$this->method($method);
		
		// Pass action method's parameters
		$this->parameters($parameters);
	}
	
	/**
	 * @var string Type of request
	 */
	protected $_type = 'POST';
	
	/**
	 * @var string Protocol like "HTTP" or "FTP"
	 */
	protected $_protocol;
	
	/**
	 * @var string $user_agent
	 */
	protected static $user_agent = '';
	
	/**
	 * @var string $ip
	 */
	protected static $ip = '0.0.0.0';
	
	/**
	 * @var object Current route
	 */
	protected $_route;
	
	/**
	 * @var string Current URI
	 */
	protected $_uri = '';
	
	/**
	 * @var string Referring url
	 */
	protected $_referrer;
	
	/**
	 * @var string Controller's directory
	 */
	protected $_directory = '';
	
	/**
	 * @var string $_controller
	 */
	protected $_controller = 'Welcome';
	
	/**
	 * @var string $_method
	 */
	protected $_method = 'index';
	
	/**
	 * @var string $_parameters
	 */
	protected $_parameters = [];
	
	/**
	 * Returns or sets the request type such as POST, GET, FILE, ...
	 *
	 * @param string $type
	 */
	public function type($type = NULL)
	{
		if($type === NULL)
		{
			return $this->type;
		}
		$this->type = $type;
	}
	
	/**
	 * Returns or sets the reqest protocol's name such as HTTP/1.0
	 * 
	 * @param string $protocol
	 */
	public function protocol($protocol = NULL)
	{
		if($protocol === NULL)
		{
			return $this->protocol;
		}		
		$this->protocol = $protocol;
	}
	
	/**
	 * Returns IP number, or sets it, according to what parameter was given.
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function ip($ip = NULL)
	{
		if($ip === NULL)
		{
			return Request::$_ip;
		}
	
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
			
		Request::$_ip = $ip;
	}
	
	/**
	 * Returns user agent string, or sets it, according to what parameter
	 * was given.
	 *
	 * @param string $useragent	 *
	 * @return string
	 */
	public static function useragent($useragent = NULL)
	{
		if($useragent === NULL)
		{
			return Request::$_user_agent;
		}
	
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		}
	
		Request::$_user_agent = $user_agent;
	}
	
	/**
	 * Returns or sets current URI
	 *
	 * @param string $uri
	 */
	public function uri($uri = NULL)
	{
		if($uri === NULL)
		{
			return ($this->uri != '' && $this->uri != '/') ? $this->uri : NULL;
		}
		$this->uri = $uri;
	}
	
	/**
	 * Save current controller
	 * 
	 * @param string $name
	 * @param string $directory
	 */
	public function controller($name = NULL, $directory = '')
	{
		if($name === NULL)
		{
			return $this->_directory . DIRECTORY_SEPARATOR . $this->_controller;
		}
		
		$this->_controller = $name;
		
		$this->_directory = $directory;
	}
	
	/**
	 * Save current method
	 * 
	 * @param string $name
	 */
	public function method($name = NULL)
	{
		if($name === NULL)
		{
			return $this->_method;
		}
		
		$this->_method = $name;
	}
	
	/**
	 * Save current action method's parameters
	 * 
	 * @param array $parameters
	 */
	public function parameters($parameters = NULL)
	{
		if($parameters === NULL)
		{
			return $this->_parameters;
		}
		
		$this->_parameters = $parameters;
	}
	
	/**
	 * Returns or sets the request referrer
	 * 
	 * @param string $referrer
	 */
	public function referrer($referrer = NULL)
	{
		if($referrer === NULL)
		{
			return ($this->referrer != '' && $this->referrer != '/') ? $this->referrer : NULL;
		}
		
		$this->referrer = $referrer;
	}
	
	/**
	 * Shortcut to redirect to the URI specified in the parameter
	 *
	 * @param string $uri
	 */
	public static function redirect($uri = NULL)
	{
		\Mooo\System\Helpers\Url\Url::redirect($url);
	}
	
	/**
	 * Setting up current routes - controller's, method's, and arguments, based on 
	 * the given URI parameter.
	 * 
	 * Sets URI to default if none given (in the factory())
	 */
	private function __construct()
	{
		$this->process();
	}
	
	/**
	 * Execute the request - invoke methods, pass given parameters.
	 */
	public function execute()
	{
		$class = new \ReflectionClass('\Mooo\Application\Classes\Controller\\' . $this->controller());
		$controller = $class->newInstance(Request::$current);
		
		// Hooks to run before before() method
		\Mooo\System\Core\Hook::call('PRE_CONTROLLER');
		
		// Invoke before() method
		$class->getMethod('before')->invoke($controller);
		
		// Invoke normal method, and pass given parameters
		$class->getMethod('action_'.$this->method())->invoke($controller, $this->parameters());
		
		// Invoke after() method
		$class->getMethod('after')->invoke($controller);
		
		// Hooks to run after after() method
		\Mooo\System\Core\Hook::call('POST_CONTROLLER');
		
		return $this;
	}
	
	/**
	 * Returns the escaped POST variable.
	 *
	 * @param string $name
	 * @return $_POST[$name]
	 */
	public function post($name)
	{
		return (isset($_POST[$name])) ? Security::escape($_POST[$name]) : FALSE;
	}
	
	/**
	 * Returns the escaped GET variable.
	 *
	 * @param string $name
	 * @return $_GET[$name]
	 */
	public function get($name)
	{
		return (isset($_GET[$name])) ? Security::escape($_GET[$name]) : FALSE;
	}
	
	/**
	 * Returns the escaped FILES variable.
	 *
	 * @param string $name
	 * @param string $option
	 * @return $_FILES[$name][$option]
	 */
	public function file($name, $option = NULL)
	{
		if($option === NULL)
		{
			return (isset($_FILES[$name])) ? Security::escape($_FILES[$name]) : FALSE;
		}
		
		return (isset($_FILES[$name][$option])) ? Security::escape($_FILES[$name][$option]) : FALSE;
	}
	
	/**
	 * Executed after: echo Request::factory() - displays all templates
	 * 
	 * @return Response body
	 */
	public function __toString()
	{
		return (string) $this->response;
	}
	
} // End \Mooo\System\Core\Request
