<?php 
	
namespace Mooo\Application\Classes\Controller;
	
class Index extends \Mooo\System\Core\Controller\Template {
	
	public function action_index()
	{
		$this->template->content = 'Hello, world!'
	}
		
} // End \Mooo\Application\Classes\Controller\Index
