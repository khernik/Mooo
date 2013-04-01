<?php

namespace \Mooo\System\Core;

/**
 * This class is responsible for hooks system.
 * Hooks provide the possibility to extend core functionalities 
 * without hacking its code.
 * 
 * These functionalities can be injected into various points of
 * request flow.
 * 
 * @author khernik
 */
class Hook {
	
	/**
	 * @var array $hooks
	 */
	protected static $hooks = [];
	
	/**
	 * Possible states of hooks, determining when exactly which group of
	 * hooks should be called
	 * 
	 * -----------------------------------------------------------------
	 * 
	 * PRE_SYSTEM      - just after system constants and autoloading are set
	 * PRE_CONTROLLER  - just before the before() method
	 * POST_CONTROLLER - just after the after() method
	 * PRE_VIEW		   - just before the view is being rendered
	 * POST_VIEW	   - just after the view is being rendered
	 * POST_SYSTEM     - after the request is finished
	 * 
	 * -----------------------------------------------------------------
	 */
	const PRE_SYSTEM 	  = 1;
	const PRE_CONTROLLER  = 2;
	const POST_CONTROLLER = 3;
	const PRE_VIEW		  = 4;
	const POST_VIEW		  = 5;
	const POST_SYSTEM 	  = 6;
	
	/**
	 * Initializes hook system
	 */
	public static function _initialize()
	{
		\Mooo\System\Config\Reader::load('hook');
	}
	
	/**
	 * Returns all set hooks
	 * 
	 * @return array
	 */
	public static function all()
	{
		return Hook::$hooks;
	}
	
	/**
	 * Returns the numeric index of a given hook
	 * 
	 * @param string $state
	 * @param array $hook
	 * @return integer
	 */
	protected static function get_index($state, $hook)
	{
		$i = 0;
		$j = 0;
		
		// Determine if hook was found
		$flag = FALSE;
		
		foreach(Hook::$hooks[$state] as $value)
		{
			if($value === $hook)
			{
				$j = $i;
				// Hook was found
				$flag = TRUE;
			}
			
			$i++;
		}
		
		// IF no hook was found
		if($i === 0)
		{
			return FALSE;
		}
		
		return $i;
	}
	
	/**
	 * Add new hook. The hook's structure:
	 * 
	 * 		1) Class
	 * 		2) Method
	 * 		3) Params <optional>
	 * 		4) File path <optional>
	 * 
	 * @param string $state
	 * @param array $hook
	 */
	public static function set($state, array $hook)
	{
		// Ensure the array contains class and method
		if(!isset($hook['class']) || !isset($hook['method']))
		{
			return FALSE;
		}
		
		// Ensure the array consists a proper state
		if(! constant($state))
		{
			return FALSE;
		}
		
		// Save new hook
		Hook::$hooks[$state][] = $hook;
	}
	
	/**
	 * Changes previously set hook
	 * 
	 * 		Hook::change('PRE_CONTROLLER', array(
	 * 			'controller' => array('oldone' => 'newone'),
	 * 			'method'	 => array('foo' => 'bar')
	 * 		));
	 * 
	 * @param string $state
	 * @param array $hook
	 */
	public static function change($state, array $hook)
	{
		$index = Hook::get_index($state, $hook);
		
		if($index === FALSE)
		{
			return FALSE;
		}
		
		foreach($hook as $name => $value)
		{
			if(is_array($value))
			{
				// Change current hook's part here
				Hook::$hooks[$state][$index][$name] = $value[1];
			}
		}
	}
	
	/**
	 * Unsets previously set hook from anywhere in the system
	 * 
	 * @param string $state
	 * @param array $hook
	 * @return boolean
	 */
	public static function detach($state, array $hook)
	{
		$index = Hook::get_index($state, $hook);
		
		if($index === FALSE)
		{
			return FALSE;
		}
		
		unset(Hook::$hooks[$state][$i]);
	}
	
	/**
	 * Calls specified group of hooks
	 * 
	 * @param string $group
	 */
	public static function call($which)
	{
		if(\Mooo\System\Core\Mooo::$hooks = FALSE)
		{
			// Clear hook array
			Hook::$hooks = [];
			
			return FALSE;
		}
		
		foreach(Hook::$hooks as $group => $hook)
		{
			if($group === $which)
				Hook::run($group, $hook);
		}
	}
	
	/**
	 * Runs the current hook
	 * 
	 * @param string $state
	 * @param array $hook
	 */
	private static function run($state, $hook)
	{
		if(isset($hook['filepath']) && !empty($hook['filepath']))
		{
			if(! is_file($hook['filepath']))
			{
				return FALSE;
			}
		
			// Include file with the given class
			include $hook['filepath'];
		}
		
		if(! class_exists($hook['class']))
		{
			return FALSE;
		}
		
		// Create new instance of the class
		$obj = new $hook['class']();
		
		if(! method_exists($obj, $hook['method']))
		{
			return FALSE;
		}
		
		// Call the hook
		$hook['class']->$hook['method'];
		
		return TRUE;
	}
	
} // End \Mooo\System\Core\Hook
