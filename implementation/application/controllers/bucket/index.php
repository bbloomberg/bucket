<?php

class Index extends CI_Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->load->library('bucket');
		
		$this->bucket->set_layout_id('main/desktop');
		$this->bucket->set_content_id('bucket/index/index');
		
		$this->bucket->add_css('global');
		
		$this->bucket->set_data('title', "Bucket!");
		
		$this->bucket->set_data('message', "Hello");
		
		$this->bucket->render_layout();
	}
}

/* End of file index.php */
/* Location: ./application/controllers/assets/index.php */
