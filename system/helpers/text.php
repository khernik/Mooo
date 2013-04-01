<?php

namespace \Mooo\System\Helpers;

/**
 * This class is responsible for text based operations
 * 
 * @author khernik
 */
class Text {
	
	/**
	 * Cuts the text when the limit is reached.
	 * 
	 * @param string $str
	 * @param integer $limit
	 * @return string
	 */
	public static function word_limiter($str, $limit)
	{
		if(trim($str) === "")
		{
			return $str;
		}
		
		$words = str_word_count($str, 1);
		
		if(sizeof($words) < $limit)
		{
			return $str;
		}
		
		return array_slice($words, 0, $limit);
	}
	
	/**
	 * Cuts the text when the character limit is reached.
	 * 
	 * @param string $str
	 * @param integer $limit
	 * @return string
	 */
	public static function char_limiter($str, $limit)
	{
		if(strlen($str) < $limit)
		{
			return $str;
		}
		
		return substr($str, 0, $limit);
	}
	
} // End \Mooo\System\Helpers\Text
