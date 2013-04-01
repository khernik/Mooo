<?php

namespace Mooo\System\ErrorException\Container\Types;

/**
 * Handle errors/exceptions which have stopped script execution.
 * 
 * @author khernik
 */
class Fatal extends \Mooo\System\ErrorException\Container\Container {
	
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
		
		// Set the starting line to something before the error
		$start = $line - 3;
		
		$this->_context = \Mooo\System\Debug\Debug::get_source($path, $start, $start + 5);
	}
	
} // End \Mooo\System\ErrorException\Container\Types\Fatal
