<?php

namespace Mooo\System\ErrorException\Container\Types;

/**
 * Handle errors which didn't stop the script execution.
 * 
 * @author khernik
 */
class Normal extends \Mooo\System\ErrorException\Container\Container {
	
	/**
	 * @var array Backtrace of the error. Contains:
	 *
	 * 		- PATH
	 * 		- LINE NUMBER
	 * 		- CLASS
	 * 		- METHOD'S TYPE
	 * 		- METHOD
	 */
	public $_backtrace = [];
	
	/**
	 * Adds information about the error/exception.
	 *
	 * @param integer $code
	 * @param string $message
	 * @param string $path
	 * @param integer $line
	 */
	public function __construct($code, $message, $path, $line)
	{
		parent::__construct($code, $message, $path, $line);
		
		$debug = \Mooo\System\Debug\Debug::get_backtrace();
		
		unset($debug[0], $debug[1]); // no handler/error classes
		$debug = array_values($debug);
		
		for($i = 0; $i < sizeof($debug) - 1; $i++)
		{
			$this->_backtrace[$i]['path']     = (isset($debug[$i]['file'])) ? $debug[$i]['file'] : '';
			$this->_backtrace[$i]['line']     = (isset($debug[$i]['line'])) ? $debug[$i]['line'] : '';
			$this->_backtrace[$i]['class']    = (isset($debug[$i]['class'])) ? $debug[$i]['class'] : '';
			$this->_backtrace[$i]['type']     = (isset($debug[$i]['type'])) ? $debug[$i]['type'] : '';
			$this->_backtrace[$i]['function'] = (isset($debug[$i]['function'])) ? $debug[$i]['function'] : '';
		}
		
		if(isset($debug[0]['line']))
		{		
			$start = $debug[0]['line'] - 3;
			$this->_context = \Mooo\System\Debug\Debug::get_source($debug[0]['file'], $start, $start + 5);
		}
		else
		{
			$this->_context = '';
		}
	}
	
} // End \Mooo\System\ErrorException\Container\Types\Normal
