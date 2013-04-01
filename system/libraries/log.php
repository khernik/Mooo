<?php

namespace Mooo\System\Libraries;

/**
 * Creates new log after the request flow is finished/stopped.
 * 
 * @author khernik
 */
class Log {
	
	/**
	 * Creates new log messages based on current errors/exceptions
	 */
	public static function execute()
	{
		$errors = \Mooo\System\Exceptions\Exceptions::$_errors;
		
		// Prepare the associative array for all logs
		$logs = [];
		
		// Iteration helper
		$i = 0;
		
		foreach($errors as $error)
		{
			// Error title string
			$logs[$i]['type'] = 'ErrorException ' . '[ ' . $error->_type . ' ] : ' . $error->_message;
			
			// Error date
			$logs[$i]['date'] = $error->_date;
			
			// File path to error occurance
			$logs[$i]['path'] = $error->_file;
			
			// Error's line number
			$logs[$i]['line_number'] = $error->_line_number;
				
			if($error instanceof \Mooo\System\Error\Container\Normal)
			{
				$logs[$i]['class'] = $error->_backtrace[0]['class'] . $error->_backtrace[0]['type'] . $error->_backtrace[0]['function'];
			}
			
			$i++;
		}
	
		Log::write($logs);
	}
	
	/**
	 * Creates new log.
	 * 
	 * @param array $errors
	 */
	private static function write($errors)
	{
		// Write the log for every error from this request flow
		foreach($errors as $error)
		{
			// Get the date like YYYY-MM-DD
			$date = explode(" ", $error['date'])[0];
			
			// Create filename out of the date
			$file = str_replace("-", "_", $date);
			
			// Create path
			$path = APATH . 'logs/errors/' . $file . '.php';
			
			// Prepare log data
			$content = "";
			
			// Create log file if doesn't exist
			if(! file_exists($path))
			{
				// Prepare the log content
				$content .= "<?php defined('SPATH') or die('No direct script access.'); ?>";
			}
			
			$content .= "\n\n-- SINGLE RUNTIME ERROR LOG -- \n\n";
			
			// Create single log's message
			foreach($error as $key => $value)
			{
				$content .= $key . ' : ' . $value . "\n";
			}
			
			// Save the log
			\Mooo\System\Helpers\File::write($path, $content, 'w+');
		}
	}
	
} // End \Mooo\System\Log\Log
