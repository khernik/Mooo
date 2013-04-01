<?php

namespace Mooo\System\Exception;

/**
 * Handles errors/exceptions. It also keeps information about all errors/exceptions that have 
 * been present during the request flow.
 *
 * @author khernik
 */
class Exception {
	
	/**
	 * @var array $_exceptions
	 */
	public static $_errors_and_exceptions = [];
	
	/**
	 * Execute the custom message and save the object for later log.
	 * 
	 * @param object $object
	 */
	private static function execute($container)
	{
		Parse\Parse::execute($container);
		Exception::$_errors_and_exceptions[] = $container;
	}

} // End \Mooo\System\Exception\Exception
