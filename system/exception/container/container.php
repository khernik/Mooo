<?php

namespace Mooo\System\ErrorException\Container;

/**
 * This class contains all exceptions/errors that have occured during the request flow. It
 * behaves like an array.
 * 
 * @author khernik
 */
abstract class Container {
	
	/**
	 *  PHP error levels that can be handled by this class.
	 */
	const E_NOTICE            = 8;
	const E_WARNING           = 2;
	const E_ERROR             = 1;
	const E_PARSE             = 4;
	const E_CORE_ERROR        = 16;
	const E_CORE_WARNING      = 32;
	const E_COMPILE_ERROR     = 64;
	const E_COMPILE_WARNING   = 128;
	const E_USER_ERROR        = 256;
	const E_USER_WARNING      = 512;
	const E_USER_NOTICE       = 1024;
	const E_STRICT            = 2048;
	const E_RECOVERABLE_ERROR = 4096;
	
	/**
	 * @var string $_type
	 */
	public $_type = '';
	
	/**
	 * @var string $_message
	 */
	public $_message = '';
	
	/**
	 * @var string $_file
	 */
	public $_file = '';
	
	/**
	 * @var integer $_line_number
	 */
	public $_line_number = 0;
	
	/**
	 * @var string $_date
	 */
	public $_date = '0000-00-00';
	
	/**
	 * @var array $_context_lines
	 */
	public $_context_lines = [];
	
	/**
	 * Adds information about the current error/exception.
	 *
	 * @param integer $code 
	 * @param string $message
	 * @param string $path
	 * @param integer $line
	 */
	public function __construct($code, $message, $path, $line)
	{
		$this->_type = array_search($code, get_defined_constants());		
		$this->_message = $message;		
		$this->_file = $path;
		$this->_line_number = $line;
		
		$this->_date = date("Y-m-d H:i:s (T)");
	}
	
} // End \Mooo\System\ErrorException\Container\Container
