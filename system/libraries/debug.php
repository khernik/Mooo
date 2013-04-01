<?php

namespace Mooo\System\Libraries;

/**
 * Debugging the backtrace, getting code lines.
 * 
 * @author khernik
 */
class Debug {
	
	/**
	 * Returns the backtrace.
	 */
	public static function get_backtrace()
	{
		// Get PHP default backtrace
		$debug_backtrace = debug_backtrace();
		
		foreach($debug_backtrace as &$backtrace_block)
		{
			// If it's not something like shutdown() callback
			if(array_key_exists('file', $backtrace_block))
			{
				$file = $backtrace_block['file'];
				$line = $backtrace_block['line'];

				// Add additional parameter to the backtrace
				$backtrace_block['context'] = Debug::get_source_part($file, $line, 4, TRUE);
			}
		}
		
		return $debug_backtrace;
	}
	
	/**
	 * Get given code lines from the given file.
	 * 
	 * @param string $path
	 * @param integer $start
	 * @param integer $number
	 * @param boolean $both_sides
	 * @return array
	 */
	public static function get_source_part($path, $start, $number = 1, $both_sides = FALSE)
	{
		// Open file with the code
		$lines = file($path);
		
		// Prepare the outcomes
		$outcomes = [];
		
		// Save the content of the given lines
		if(! $both_sides)
		{
			for($i = $start; $i <= ($start + $number); $i++)
			{
				$outcomes[] = $lines[$i];
			}
		}
		else
		{
			$st = ($number % 2 === 0) ? $number/2 : ceil($number/2);
			
			for($i = $start - $st; $i <= ($start + $st); $i++)
			{
				$outcomes[] = $lines[$i];
			}
		}
		
		// Add PHP tags to the lines array
		$outcomes = array_merge(array('&lt;?php'), $outcomes, array('?&gt;'));
		
		return $outcomes;
	}
	
} // End \Mooo\System\Debug\Debug
