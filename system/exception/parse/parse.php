<?php

namespace Mooo\System\ErrorException\Parse;

/**
 * Displaying error/exception message.
 * 
 * @author khernik
 */
class Parse {
	
	/**
	 * Displays error/exception message.
	 * 
	 * @param object $container
	 */
	public static function execute($container)
	{
		extract((array) $container);
		$backtrace = Parse::getBacktrace($container);
		
		include SPATH . 'errorException/html/body.php';
	}
	
	/**
	 * Gets the backtrace property if exists.
	 * 
	 * @param object $container
	 * @return string
	 */
	private static function getBacktrace($container)
	{
		if(isset($container->_backtrace))
		{
			$backtrace = [];			
			for($i = 0; $i < sizeof($container->_backtrace); $i++)
			{
				$backtrace[] = ($i + 1) . 
					'. ' . $container->_backtrace[$i]['path'] . 
					" [" . $container->_backtrace[$i]['line'] . "] <i>" . 
					$container->_backtrace[$i]['class'] .
					$container->_backtrace[$i]['type'] . 
					$container->_backtrace[$i]['function'] . "</i><br>";
			}
			
			$value = implode("\n", $backtrace);
		}
		
		return (isset($value)) ? $value : '';
	}
	
} // End \Mooo\System\ErrorException\Parse\Parse
