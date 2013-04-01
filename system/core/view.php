<?php

namespace Mooo\System\Core\View;

/**
 * Handling view files, passing variables.
 * 
 * @author khernik
 */
class View {
	
	/**
	 * @var string FOLDER
	 */
	const FOLDER = 'templates/';
	
	/**
	 * @var string $view_path
	 */
	protected $view_path = '';
	
	/**
	 * @var array $view_variables
	 */
	protected $view_variables = [];
	
	/**
	 * Returns View object and includes new view page.
	 * 
	 *		echo View::Factory('path');
	 * 
	 * @param string $uri
	 * @return View
	 */
	public static function factory($view_path = NULL)
	{
		if($view_path === NULL)
			$view_path = 'index';
		
		return new View($view_path);
	}
	
	/**
	 * Adds uri to the attribute.
	 * 
	 * @param string $uri
	 */
	public function __construct($view_path)
	{
		$this->view_path = $view_path;
	}
	
	/**
	 * Add new variable to the view file
	 * 
	 * 		$this->template->content = 'fdfdfdfds';
	 * 
	 * @param string $key
	 * @param all $value
	 */
	public function __set($key, $value)
	{
		$this->view_variables[$key] = $value;
	}
	
	/**
	 * Add new variable to the view file
	 * 
	 * 		View::Factory('fdsf')->bind('name', 'value);
	 *
	 * @param unknown $name
	 * @param unknown $value
	 */
	public function bind($name, $value)
	{
		$this->view_variables[$name] = $value;
		
		return $this;
	}
	
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	 * Include the view file
	 * 
	 * @return void
	 */
	private function render()
	{
		if(sizeof($this->view_variables) > 0)
		{
			extract($this->view_variables, EXTR_SKIP);
		}
		
		ob_start();

		include APATH . View::FOLDER . $this->view_path . '.php';
		
		return ob_get_clean();
	}
	
} // End \Mooo\System\Core\View
