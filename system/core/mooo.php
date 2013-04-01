<?php

namespace Mooo\System\Core;

use Mooo\System\ErrorException\ErrorException;

/**
 * Saving user-important variables, and initializing modules.
 * 
 * @author khernik
 */
class Mooo {
	
	/**
	 * Software stages
	 * 
	 * They determine whether to enable dynamic profiling. If set to development, 
	 * to each generated website there will be added jQuery profiling.
	 */
	const PRODUCTION  = 1;
	const DEVELOPMENT = 2;
	
	/**
	 * @var integer $environment
	 */
	public static $environment = Mooo::DEVELOPMENT;
	
	/**
	 * @var array $autoload_paths
	 */
	protected static $autoload_paths = [];
	
	/**
	 * @var string $charset
	 */
	public static $charset = 'utf-8';
	
	/**
	 * @var string $register_globals
	 */
	protected static $register_globals = FALSE;
	
	/**
	 * @var string $magic_quotes
	 */
	protected static $magic_quotes = FALSE;
	
	/**
	 * @var string $protocol (HTTP, FTP, ...)
	 */
	public static $protocol = '';
	
	/**
	 * @var string $base_url
	 */
	public static $base_url = '';
	
	/**
	 * @var string $index_file
	 */
	public static $index_file = 'index.php';
	
	/**
	 * @var boolean $mooo_errors
	 */
	public static $mooo_errors = TRUE;
	
	/**
	 * @var boolean $log_errors
	 */
	public static $log_errors = TRUE;
	
	/**
	 * @var boolean $caching
	 */
	public static $caching = FALSE;
	
	/**
	 * @var string $cache_dir
	 */
	public static $cache_dir;
	
	/**
	 * @var integer $cache_life
	 */
	public static $cache_life = 60;
	
	/**
	 * @var boolean $profiling
	 */
	public static $profiling = FALSE;
	
	/**
	 * @var array $modules
	 */
	public static $modules = [];
	
	/**
	 * Initializes variables given in the index file.
	 *
	 * @param array $config
	 */
	public static function init($config = [])
	{
		if(array_key_exists('charset', $config))
		{
			Mooo::$charset = $config['charset'];
		}
		if(array_key_exists('register_globals', $config))
		{
			Mooo::$register_globals = $config['register_globals'];
		}
		if(array_key_exists('magic_quotes', $config))
		{
			Mooo::$magic_quotes = $config['magic_quotes'];
		}
		if(isset($_SERVER['SERVER_PROTOCOL']))
		{
			Mooo::$protocol = $_SERVER['SERVER_PROTOCOL'];
		}
		if(array_key_exists('base_url', $config))
		{
			Mooo::$base_url = $config['base_url'];
		}
		if(array_key_exists('index_file', $config))
		{
			Mooo::$index_file = $config['index_file'];
		}
		if(array_key_exists('mooo_errors', $config))
		{
			Mooo::$mooo_errors = $config['mooo_errors'];
		}
		if(array_key_exists('log_errors', $config))
		{
			Mooo::$log_errors = $config['log_errors'];
		}
		
		// Error handling
		if(Mooo::$mooo_errors)
		{
			\set_error_handler(array('\Mooo\System\ErrorException\ErrorException', 'error_handler'));
			
			\register_shutdown_function(array('\Mooo\System\ErrorException\ErrorException', 'error_handler'));
			
			\set_exception_handler(array('\Mooo\System\ErrorException\ErrorException', 'exception_handler'));
		}
		
		// Logging errors
		if(Mooo::$log_errors)
		{
			\register_shutdown_function(array('\Mooo\System\Log\Log', 'execute'));
		}
		
		if(array_key_exists('caching', $config))
		{
			Mooo::$caching = $config['caching'];
		}
		
		// Set up bad register_globals
		ini_set('register_globals', Mooo::$register_globals);
		
		// Set up bad magic_quotes
		ini_set('magic_quotes', Mooo::$magic_quotes);
		
		// Set multibyte base encoding
		mb_internal_encoding(Mooo::$charset);
	}
	
	/**
	 * Unset all global variables instead of superglobals such as $_POST, $_GET, etc.
	 */
	public static function globals()
	{
		// Get all global variables' names
		$globals = array_keys($GLOBALS);
		
		// Exclude super global variables
		$globals = array_diff($globals, array(
			'_COOKIE',
			'_ENV',
			'_GET',
			'_FILES',
			'_POST',
			'_REQUEST',
			'_SERVER',
			'_SESSION',
			'GLOBALS',
		));
		
		// Unset all global variables instead of exluded above
		foreach ($globals as $name)
		{
			unset($GLOBALS[$name]);
		}
	}
	
	/**
	 * This method is invoked automatically by spl_register_autoload() function.
	 *
	 *		"\Mooo\System\Config\Config" =>
	 *		"C://xampp/htdocs/project/system/config/config.php"
	 *
	 * It shouldn't be used to autoload 3rd party modules, unless it has 
 	 * Mooo file structure.
     * 
     * It loads only GLOBAL classes.
	 *
	 * @param string $class
	 */
	public static function autoload($class)
	{
		// Add backslash if it's not in the namespace
		if(substr($class, 0, 1) !== '\\')
		{
			$path = '\\' . $class;
		}
		
		// Delete "\Mooo\" part
		$path = substr($class, 6);
		$path = str_replace("\\", DIRECTORY_SEPARATOR, $path);
		
		// Lowercases to match the path
		$path_tokens = explode(DIRECTORY_SEPARATOR, $path);
		foreach($path_tokens as &$token)
		{
			$token = lcfirst($token);
		}
		
		$path = implode(DIRECTORY_SEPARATOR, $path_tokens);
		
		if(is_readable($path))
		{
			require_once($path);
		}
	}
	
	/**
	 * Old autoloading method - autoload classes places in the PSR-0 way,
	 * without namespaces.
	 * 
	 * It is used for modules written in kohana way.
	 * 
	 * @param string $class
	 */
	public static function autoload_psr($class)
	{
		$parts = explode("_", $class);
		
		// Make path out of class name
		$class = implode(DIRECTORY_SEPARATOR, $parts);
		
		foreach(Mooo::$autoload_paths as $path)
		{
			if(is_file($path . 'classes/' . $class))
			{
				require_once $path . 'classes/' . $class;
			}
		}
	}
	
	/**
	 * Search for a given directory/files pattern and includes all files
	 * within. Adding second parameter allows to include single file.
	 * 
	 * @param string $directory
	 * @param string $file
	 */
	public static function find_files($directory, $file = '')
	{
		if(! is_dir($directory))
		{
			return FALSE;
		}
		
		$path = realpath($dorectory);
		
		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path),
				RecursiveIteratorIterator::SELF_FIRST);
		
		foreach($objects as $name => $object)
		{
			$parse_ext = explode(".", $name);
			
			if($parse_ext[sizeof($parse_ext) - 1] !== 'php')
			{
				break;
			}
			
			// Include current file
			require_once($path . $name);
		}
	}
	
	/**
	 * Load chosen in the index file modules.
	 * 
	 * @param array $modules
	 */
	public static function modules($modules = [])
	{
		if(\Mooo\System\Core\Request::$initial)
		{
			return FALSE;
		}
		
		foreach($modules as $value)
		{
			if(! is_dir(MPATH . $value))
			{
				return FALSE;
			}
			
			// Look for the init.php file
			if(is_readable(MPATH . $value . '/init.php')) 
			{
				require_once(MPATH . $value . '/init.php');
			}
			
			// Add path for psr autoloading
			Mooo::$autoload_paths[] = MODPATH . $value;
		}
		
		Mooo::$modules = $modules;
	}
	
	/**
	 * Handles errors.
	 *
	 * @param integer $code
	 * @param string $message
	 * @param string $path
	 * @param integer $line
	 */
	public static function error_handler($code = '', $message = '', $path = '', $line = '')
	{
		// If it's called from set_error_handler()
		if($code)
		{
			$container = new \Mooo\System\Exception\Container\Types\Normal($code, $message, $path, $line);
		}
	
		$error = error_get_last();
		
		// If it's called from register_shutdown_function()
		if($error)
		{
			$container = new \Mooo\System\Exception\Container\Types\Fatal($error['type'], $error['message'], $error['file'], $error['line']);
		}
	
		if(isset($container))
		{
			\Mooo\System\Exception\Exception::execute($container);
		}
	}
	
	/**
	 * Handling uncaught exceptions
	 *
	 * @param object $e
	 */
	public static function exception_handler($e)
	{
		$container = new \Mooo\System\Exception\Container\Types\Fatal($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
		
		\Mooo\System\Exception\Exception::execute($container);
	}
	
	/**
	 * Deinitialize Mooo application - stops autoloading, deletes global contants, etc.
	 */
	public static function deinit()
	{
		// Stop autoloading
		spl_autoload_unregister(array('Mooo\System\Autoloader\Autoloader', 'init'));
	
		// Stop error handling
		if(Mooo::$mooo_errors)
		{
			restore_error_handler();
	
			restore_exception_handler();
		}
	
		// Stop error logging
		Mooo::$log_errors = Mooo::$errors = FALSE;
	
		// Delete modules
		Mooo::$modules = NULL;
	}
	
	/**
	 * Displays framework title
	 * 
	 * @return string
	 */
	public static function version()
	{
		return 'Mooo Framework, copyright 2013.';
	}
	
} // End \Mooo\System\Core\Mooo
