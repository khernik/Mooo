<?php

namespace Mooo\System\Validate;

/**
 * This class is responsible for form validation.
 * 
 * @author khernik
 */
class Validate {
	
	/**
	 * @var array $boolean_outcomes
	 */
	protected static $boolean_outcomes = [];
	
	/**
	 * @var array $validation_errors
	 */
	protected static $validation_errors = [];
	
	/**
	 * Gathering the outcomes of the validation and managing what to validate.
	 * 
	 * @param array $requirements
	 * @return boolean
	 */
	public static function factory($requirements)
	{
		foreach($requirements as $field => $reqs)
		{
			foreach($reqs as $req_name => $req_value)
			{
				Validate::$boolean_outcomes[$field][$req_name] = Validate::$req_name($req_value, $_POST[$field]);
			}
		}
			
		foreach(Validate::$boolean_outcomes as $field)
		{
			foreach($field as $req => $value)
			{
				if(! $value) 
					return FALSE;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Checks whether the field is not empty.
	 * 
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function not_empty($value, $field)
	{
		return (!empty($field) && isset($field)) ? TRUE : FALSE;
	}
	
	/**
	 * Checks whether the string contains enough char number.
	 * 
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function min($value, $field)
	{
		return (strlen($field) < $value) ? FALSE : TRUE;
	}
	
	/**
	 * Checks whether the string doesn't contain too much characters number.
	 *
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function max($value, $field)
	{
		return (strlen($field) > $value) ? FALSE : TRUE;
	}
	
	/**
	 * Checks whether the variable matches another one given.
	 *
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function match($value, $field)
	{
		return ($_POST[$value] == $field) ? TRUE : FALSE;
	}
	
	/**
	 * Checks whether the variable is a valid email.
	 *
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function email($value, $field)
	{
		return (filter_var($field, FILTER_VALIDATE_EMAIL)) ? TRUE : FALSE;
	}
	
	/**
	 * Adds a requirement using given custom regex.
	 *
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function regex($value, $field)
	{
		return (preg_match($value, $field)) ? TRUE : FALSE;
	}	
	
	/**
	 * Checks whether the variable is the integer.
	 *
	 * @param string $value
	 * @param string $field
	 * @return boolean
	 */
	protected static function integer($value, $field)
	{
		return (is_numeric($field)) ? TRUE : FALSE;
	}
	
} // End \Mooo\System\Validate\Validate
