<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->library('Bucket');
		
		$this->bucket->set_layout_id('index');
		
		$this->bucket->set_content_id('welcome_message');
		
		$this->bucket->add_css('style');
		
		$this->bucket->render_layout();
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */