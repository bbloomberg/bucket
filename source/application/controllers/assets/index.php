<?php

class Index extends MY_Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
	function _index_init()
	{
		$this->bucket->set_layout_id('assets/index');
	}
	
	function _index()
	{
		$segments = $this->uri->rsegment_array();
		
		$assets_type = $segments[3];
		$assets_extension = $segments[4];
		
		$assets_names = implode("/", array_splice($segments, 4));

		$this->bucket->set_data('asset', $this->bucket->render_asset_set($assets_type, $assets_extension, $assets_names));
	}
}

/* End of file index.php */
/* Location: ./application/controllers/assets/index.php */