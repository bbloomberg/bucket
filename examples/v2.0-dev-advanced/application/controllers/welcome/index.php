<?php

class Index extends Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->bucket->set_data('title', "Welcome To Bucket!");
		
		$this->bucket->set_data('meta', array('keywords' => 'bucket, codeigniter, welcome'));
	
		$this->bucket->add_css('global', array('group' => 'global'));
	}
}

/* End of file index.php */
/* Location: ./application/controllers/welcome/index.php */