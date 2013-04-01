<?php

namespace Mooo\System\Core\Controller;

/**
 * This class is responsible for built-in layout system.
 * 
 * @author khernik
 */
class Template extends Controller {

	/**
	 * @var object $template
	 */
	public $template = 'template';
	
	/**
	 * @var boolean $auto_render
	 **/
	public $auto_render = TRUE;
	
	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = \Mooo\System\Core\View::Factory($this->template);
		}

		return parent::before();
	}

	/**
	 * Assigns the template [View] as the request response (!!!).
	 */
	public function after()
	{
		if ($this->auto_render === TRUE)
		{ 
			$this->request->response = $this->template;
		}

		return parent::after();
	}

} // End \Mooo\System\Core\Controller\Template
