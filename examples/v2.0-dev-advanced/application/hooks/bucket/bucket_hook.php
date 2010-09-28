<?php class Bucket_Hook
{
	private $_ci;
	
	public function __construct()
	{
		$this->_ci =& get_instance();
	}
	
	public function init()
	{
		// Load up Bucket
		$this->_ci->load->library('bucket');
		
		// Set the appropriate layout
		
		$this->_ci->load->library('user_agent');
		
		if($this->_ci->agent->is_mobile())
		{
			$this->_ci->bucket->set_layout_id('mobile/main');
		}
		elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST')
		{
			$this->_ci->bucket->set_layout_id('ajax/json');
		}
		else
		{
			$this->_ci->bucket->set_layout_id('desktop/main');
		}
		
		// Set the content 
		
		$directory = $this->_ci->router->fetch_directory();
		$class = $this->_ci->router->fetch_class();
		$method = $this->_ci->router->fetch_method();
		
		$this->_ci->bucket->set_content_id($directory . $class . '/' . $method);
	}
	
	public function dinit()
	{
		// Render the layout
		$this->_ci->bucket->render_layout();
	}
}