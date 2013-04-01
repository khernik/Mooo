<?php

namespace Mooo\System\Core\Controller;

/**
 * Saving the request object.
 * 
 * @author khernik
 */
class Controller {
	
	/**
	 * @var object This is needed for displaying layouts in Controller_Template
	 */
	public $request;
	
	/**
	 * Saves the request object through the reflection api.
	 * 
	 * @param \Mooo\System\Core\Request $request
	 */
	public function __construct(\Mooo\System\Core\Request $request)
	{
		$this->request = $request;
	}
	
	/**
	 * Method called before controller action methods
	 */
	public function before()
	{
		// If no before() method given in childs
	}
	
	/**
	 * Method called after controller action methods
	 */
	public function after()
	{
		// If no after() method given in childs
	}
	
} // End \Mooo\System\Core\Controller\Controller
