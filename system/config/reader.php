<?php

namespace Mooo\System\Config\Reader;

/**
 * This class only reads config files. All config files should be placed in 
 * APPATH/config folder.
 * 
 * To store values in the config files, use array, and return it. It can
 * be later retrieved using Reader::read() method, or __get() magic function.
 * 
 * @author khernik
 */
class Reader {
	
	/**
	 * @var file $config_file
	 */
	private $config_file;
	
	/**
	 * Saves the included file into the attribute.
	 *
	 * @param file $config_file
	 */
	public function __construct($config_file)
	{
		$path = APATH . 'config' . DIRECTORY_SEPARATOR . $config_file . '.php';
		
		if(is_readable($path))
		{
			$this->config_file = include $path;
		}
	}
	
	/**
	 * Includes the provided config file.
	 */
	public function load()
	{
		include_once $this->config_file;
	}
	
	/**
	 * Returns the configs from the set file. Maximum level of nested 
	 * configurations is - 2.
	 * 
	 * 		$obj = new Config();
	 * 		echo $obj->get('value1', 'value_nested');
	 *
	 */
	public function read()
	{
		$keys = get_func_args();
		
		$array = $this->config_file;
		
		foreach($keys as $key)
		{
			$array = $array[$key];
		}
		
		return $array;
	}
	
	/**
	 * Also returns configuration values, but in slightly different
	 * interface.
	 * 
	 * 		$obj = new Config();
	 * 		echo $obj->value1;
	 * 		echo $obj->value1['value_nested'];
	 * 
	 * @param string/array $key
	 */
	public function __get($element)
	{
		return $this->config_file[$element];
	}
	
} // End \Mooo\System\Config\Reader
