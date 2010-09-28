<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bucket
 * 
 * A layout and asset library for CodeIgniter
 * 
 * @package		Bucket
 * @version		0.1.0
 * @author		Backstack Development <http://backstack.ca>
 * @link		http://backstack.ca/projects/bucket
 * @copyright	Copyright (c) 2010, Backstack Development
 * @license		http://opensource.org/licenses/mit-license.php MIT Licensed
 * 
 */

class Bucket
{
	private $_ci;
	
	private $_behaviour_id;
	
	private $_layout_id = FALSE;
	private $_content_id = FALSE;
	
	private $_assets = array('css' => array(), 'js' => array());
	
	private $_data = array();
	
	private $_gzipped = FALSE;
	private $_is_asset_layout = FALSE;
	
	public function __construct($config_array)
	{
		$this->_ci =& get_instance();
		
		$this->_configure($config_array);
		
		$this->_ci->load->helper('url_helper');
		
		$this->_ci->load->helper('bucket_helper');
		
		log_message('debug', 'Layout library loaded');
	}
	
	/**
	 * Sets and validates configuration
	 * 
	 * @access public
	 * @return void
	 * @param array $config
	 **/
	private function _configure($config)
	{	
		$this->_ci->load->config('bucket');
		
		$defaults = array(
		
			'bucket_layouts_path' => 'layouts/',
			'bucket_content_path' => 'content/',
			'bucket_partials_path' => 'partials/',
			'bucket_assets_path' => 'assets/',
			
			'bucket_assets_cache_path' => 'assets/',

			'bucket_assets_css_path' => 'css/',
			'bucket_assets_js_path' => 'js/',
			'bucket_assets_img_path' => 'img/',

			'bucket_assets_css_extension' => '.css',
			'bucket_assets_js_extension' => '.js',

			'bucket_assets_url_path' => 'assets/',
			'bucket_asset_name_separator' => '--',
			'bucket_obfuscate_assets_timestamp' => FALSE,
			
			'bucket_default_behaviour' => 'development',
			'bucket_behaviours' => array(
				
				'development' => array(
				
					'minify_layout' => FALSE,
					'minify_css' => FALSE,
					'minify_js' => FALSE,
					'combine_css' => FALSE,
					'combine_js' => FALSE,
					'browser_cache_css' => FALSE,
					'browser_cache_js' => FALSE,
					'browser_cache_img' => FALSE,
					'application_cache_css' => FALSE,
					'application_cache_js' => FALSE,
					'application_cache_img' => FALSE,
					'gzip_layout' => FALSE,
					'gzip_css' => FALSE,
					'gzip_js' => FALSE,
					'gzip_img' => FALSE
					
				),
				
				'production' => array(

					'minify_layout' => TRUE,
					'minify_css' => TRUE,
					'minify_js' => TRUE,
					'combine_css' => TRUE,
					'combine_js' => TRUE,
					'browser_cache_css' => TRUE,
					'browser_cache_js' => TRUE,
					'browser_cache_img' => TRUE,
					'application_cache_css' => TRUE,
					'application_cache_js' => TRUE,
					'application_cache_img' => TRUE,
					'gzip_layout' => TRUE,
					'gzip_css' => TRUE,
					'gzip_js' => TRUE,
					'gzip_img' => TRUE
				
				)
				
			)
			
		);
		
		foreach($defaults as $key => $value)
		{
			if ($this->_ci->config->item($key) == '')
			{
				$this->_ci->config->set_item($key, $value);
			}
		}
		
 		foreach(array('bucket_layouts_path', 'bucket_content_path', 'bucket_partials_path', 'bucket_assets_path', 'bucket_assets_css_path', 'bucket_assets_js_path', 'bucket_assets_img_path', 'bucket_assets_cache_path', 'bucket_assets_url_path') as $item)
		{
			$this->_ci->config->set_item($item, $this->_reduce_double_slashes($this->_ci->config->item($item) . '/'));
		}
		
		if (!preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->_ci->config->item('permitted_uri_chars'), '-'))."]+$|i", $this->_ci->config->item('bucket_asset_name_separator')))
		{
			show_error("The asset name separator is not in the pattern of valid URL characters");
		}
		
		if(count($config) === 1 && isset($config['behaviour_id']))
		{
			$this->set_behaviour_id($config['behaviour_id']);
		}
		else
		{
			$this->set_behaviour_id($this->_ci->config->item('bucket_default_behaviour'));
		}
	}
	
	/**
	 * Gets the current behaviour ID
	 *
	 * @access public
	 * @return string The current behaviour ID
	 **/
	public function get_behaviour_id()
	{
		return $this->_behaviour_id;
	}
	
	/**
	 * Sets the current behaviour ID
	 *
	 * @access public
	 * @param string $behaviour_id The behaviour ID
	 **/
	public function set_behaviour_id($behaviour_id)
	{
		$this->_behaviour_id = $behaviour_id;
	}
	
	/**
	 * Gets a behaviour's configuration item
	 *
	 * @access public
	 * @return mixed $behaviour_item_id The behaviour item ID
	 * @param string $item The behaviour item
	 **/
	public function get_behaviour_item($behaviour_item_id)
	{
		$behaviours = $this->_ci->config->item('bucket_behaviours');
		
		$behaviour = $behaviours[$this->_behaviour_id];
		
		return $behaviour[$behaviour_item_id];
	}
	
	/**
	 * Sets the layout ID
	 *
	 * @access public
	 * @param string $layout_id The layout ID
	 **/
	public function set_layout_id($layout_id)
	{
		if(!is_string($layout_id))
		{
			show_error("Layout ID must be a string");
		}
		
		$this->_layout_id = $layout_id;
	}
	
	/**
	 * Gets the current layout ID
	 *
	 * @access public
	 * @return string The current layout ID
	 **/
	public function get_layout_id()
	{
		return $this->_layout_id;
	}
	
	/**
	 * Sets the content ID
	 *
	 * @access public
	 * @return void
	 * @param string $content_id The content ID
	 **/
	public function set_content_id($content_id)
	{
		if(!is_string($content_id))
		{
			show_error("Content ID must be a string");
		}
		
		$this->_content_id = $content_id;
	}

	/**
	 * Gets the current content ID
	 * 
	 * @access public
	 * @return string The current content ID
	 **/
	public function get_content_id()
	{
		return $this->_content_id;
	}
	
	/**
	 * Renders the layout
	 * 
	 * @access public
	 * @return string The layout or void if echoed
	 * @param boolean $return Return or echo the layout
	 **/
	public function render_layout($return = FALSE)
	{
		if($this->get_behaviour_item('gzip_layout'))
		{
			$this->_set_gzip('layout');
		}
		
		if($this->get_content_id() == FALSE)
		{
			show_error('A content has not been set.');
		}
		
		if(($layout = $this->get_layout_id()) == FALSE)
		{
			show_error('A layout has not been set.');
		}
		
		$layout_view_file = '';
		$layout_view_file .= $this->_ci->config->item('bucket_layouts_path');
		$layout_view_file .= $layout;

		$data = $this->get_all_data();
		
		$layout = $this->_ci->load->view($layout_view_file, $data, TRUE);
		
		$layout = $this->_minify(!$this->_is_asset_layout ? 'layout' : 'asset', $layout);
		
		if($return)
		{
			return $layout;
		}
		else
		{
			echo $layout;
		}
	}
	
	/**
	 * Renders the content
	 * 
	 * @access public
	 * @return string The content or void if echoed
	 * @param boolean $return Return or echo the content
	 **/
	public function render_content($return = TRUE)
	{
		$content_view_file = '';
		$content_view_file .= $this->_ci->config->item('bucket_content_path');
		$content_view_file .= $this->get_content_id();

		$content = $this->_ci->load->view($content_view_file, $this->get_all_data(), TRUE);
	
		if($return)
		{
			return $content;
		}
		else
		{
			echo $content;
		}
	}

	/**
	 * Renders a partial
	 * 
	 * @access public
	 * @return string The partial or void if echoed
	 * @param string $partial_id The partial ID
	 * @param array $data Data for use in the partial
	 * @param boolean $return Return or echo the partial
	 **/
	public function render_partial($partial_id, $data = array(), $return = TRUE)
	{
		$partial_view_file = '';
		$partial_view_file .= $this->_ci->config->item('bucket_partials_path');
		$partial_view_file .= $partial_id;
		
		$partial = $this->_ci->load->view($partial_view_file, $data, TRUE);
	
		if($return)
		{
			return $partial;
		}
		else
		{
			echo $partial;
		}
	}
	
	/**
	 * Sets data for use in the layout or content
	 *
	 * @access public
	 * @param string|array $key The name of the data to set
	 * @param mixed $data The data
	 **/
	public function set_data($key, $data)
	{
		$this->_data[$key] = $data;
	}
	
	/**
	 * Sets all data for use in the layout or content
	 *
	 * @access public
	 * @param array $key The data
	 **/
	public function set_all_data($data)
	{
		$this->_data = $data;
	}

	/**
	 * Removes existing data to be used in the layout or content
	 *
	 * @access public
	 * @param string $name The name of the data to removed
	 **/
	public function remove_data($name)
	{
		unset($this->_data[$name]);
	}
	
	/**
	 * Removes all existing data for use in the layout or content
	 *
	 * @access public
	 **/
	public function remove_all_data()
	{
		$this->_data = array();
	}
	
	/**
	 * Gets a piece of data for use in the layout or content
	 * 
	 * @access public
	 * @return mixed|array The data or the data if no key parameter was specified
	 * @param string $name The name of the data to get
	 **/
	public function get_data($key = '')
	{
		if($name)
		{
			return $this->_data[$key];
		}
		else
		{
			return $this->get_all_data();
		}
	}
	
	/**
	 * Gets all data for use in the layout or content
	 * 
	 * @access public
	 * @return array The data
	 **/
	public function get_all_data()
	{
		return $this->_data;
	}

	/**
	 * Adds an asset for use in the layout or content
	 *
	 * @access public
	 * @param string $type The type of asset
	 * @param string $name The name of the asset
	 * @param array $data The configuration of the asset
	 **/
	public function add_asset($type, $name, $data = array())
	{
		if(!in_array(strtolower(trim($type)), array('css', 'js')))
		{
			show_error('Only CSS and JS can be added');
		}
		
		$validated_data = $this->_validate_asset_data($type, $name, $data);
		
		$this->_assets[$type][$name] = $validated_data;
	}
	
	/**
	 * Adds a CSS asset for use in the layout or content
	 *
	 * @access public
	 * @param string $name The name of the asset
	 * @param array $data The configuration of the asset
	 **/
	public function add_css($name, $data = array())
	{
		$this->add_asset('css', $name, $data);
	}
	
	/**
	 * Adds a JS asset for use in the layout or content
	 *
	 * @access public
	 * @param string $name The type of asset
	 * @param array $data The configuration of the asset
	 **/
	public function add_js($name, $data = array())
	{
		$this->add_asset('js', $name, $data);
	}
	
	/**
	 * Removes an asset for use in the layout or content
	 *
	 * @access public
	 * @param string $type The type of asset
	 * @param string $name The name of the asset or an array of asset names 
	 **/
	public function remove_asset($type, $name = '')
	{
		if(!in_array(strtolower(trim($type)), array('css', 'js')))
		{
			show_error('Only CSS and JS can be removed');
		}
		
		if($name)
		{
			if(is_array($name))
			{
				foreach($name as $names)
				{
					$this->remove_asset($type, $names);
				}
			}
		}
		else
		{
			$this->_assets[$type] = array();
		}
	}
	
	/**
	 * Removes a CSS asset for use in the layout or content
	 *
	 * @access public
	 * @param string $name The name of the asset or an array of asset names
	 **/
	public function remove_css($name = '')
	{
		$this->remove_asset('css', $name);
	}
	
	/**
	 * Removes a JS asset for use in the layout or content
	 *
	 * @access public
	 * @param string $name The name of the asset or an array of asset names
	 **/
	public function remove_js($name = '')
	{
		$this->remove_asset('js', $name);
	}
	
	/**
	 * Renders CSS and/or JS asset tags
	 * 
	 * @access public
	 * @return string The asset tags
	 * @param string $type The type of asset
	 * @param string $group The group that the assets belong to
	 **/
	public function render_assets_tags($type = '', $group = '')
	{
		$assets_tags = '';
		
		foreach(array('css', 'js') as $try_type)
		{
			if(!$type || $type == $try_type)
			{
				$assets_tags .= $this->_build_assets_tags($try_type, $this->_combine_assets($try_type, $this->_assets[$try_type]), $group);
			}
		}
		
		return $assets_tags;
	}
	
	/**
	 * Renders a CSS, JS or image tag
	 * 
	 * @access public
	 * @return string The asset tag
	 * @param string $type The asset type
	 * @param string $name The name of the asset
	 * @param string $data Configuration of the asset
	 **/
	public function render_asset_tag($type, $name, $data = array())
	{
		$validated_data = $this->_validate_asset_data($type, $name, $data);

		return $this->_build_assets_tags($type, $this->_combine_assets($type, array($name => $validated_data)), FALSE);
	}
	
	/**
	 * Validates asset data
	 * 
	 * @access private
	 * @return array The validated configuration of the asset
	 * @param string $type The type of asset
	 * @param string $name The name of the asset
	 * @param array $data Configuration of the asset
	 **/
	private function _validate_asset_data($type, $name, $data)
	{
		$defaults = array();
		$conditionals = array();
		
		$defaults = array_merge($defaults, array('attributes' => array(), 'condcom' => ''));
		
		switch($type)
		{
			case 'css' :
			case 'js' :
			
				$defaults = array_merge($defaults, array('cache' => TRUE, 'group' => '', 'combine' => TRUE));
				$conditionals = array_merge($conditionals, array('external' => preg_match('/^[a-z]+\:\/\//', $name)));
			
			break;
		}
		
		switch($type)
		{
			case 'css' :
			
				$defaults = array_merge($defaults, array('type' => 'link', 'attributes' => array('type' => 'text/css', 'rel' => 'stylesheet', 'media' => 'screen'), 'extension' => $this->_ci->config->item('bucket_assets_css_extension')));
			
			break;
			
			case 'js' :
			
				$defaults = array_merge($defaults, array('attributes' => array('type' => 'text/javascript'), 'extension' => $this->_ci->config->item('bucket_assets_js_extension')));
			
			break;
			
			case 'img' :
			
				$defaults = array_merge($defaults, array('extension' => FALSE));
			
			break;
		}
		
		foreach($defaults as $key => $value)
		{
			if(!isset($data[$key]))
			{
				$data[$key] = $value;
			}
		}
		
		foreach($conditionals as $key => $value)
		{
			$data[$key] = $value;
		}
		
		return $data;
	}
	
	/**
	 * Combines assets into asset sets
	 * 
	 * @access private
	 * @return array A set of assets
	 * @param string $type The type of asset
	 * @param array $assets The assets
	 **/
	private function _combine_assets($type, $assets)
	{
		$asset_sets = array();
		
		foreach($assets as $file => $data)
		{
			if(empty($asset_sets) || !$this->get_behaviour_item('combine_'.$type))
			{
				$asset_sets[] = array('files' => array($file), 'data' => $data);
			}
			else
			{
				$found_asset_set = FALSE;
				
				foreach($asset_sets as $asset_set_key => $asset_set)
				{
					if($data == $asset_set['data'] && $data['combine'] == TRUE)
					{
						$found_asset_set = TRUE;
						
						$asset_sets[$asset_set_key]['files'][] = $file;
					}
				}
				
				if(!$found_asset_set)
				{
					$asset_sets[] = array('files' => array($file), 'data' => $data);
				}
			}
		}
		
		return $asset_sets;
	}
	
	/**
	 * Builds asset tags
	 * 
	 * @access private
	 * @return array The asset tags
	 * @param string $type The asset type
	 * @param array $asset_sets A set of assets
	 * @param string $group An asset group ID
	 **/
	private function _build_assets_tags($type, $asset_sets, $group)
	{
		$string = '';
		
		foreach($asset_sets as $asset_set)
		{
			if($group !== FALSE && $asset_set['data']['group'] !== $group)
			{
				continue;
			}
			
			if(in_array($type, array('css', 'js')))
			{
				foreach($asset_set['files'] as $file)
				{
					$this->remove_asset($type, $file);
				}
			}
			
			$asset_set_string = '';
			
			switch($type)
			{
				case 'css' :

					$asset_set['data']['asset_url'] = $this->_assets_url('css', $asset_set['files'], $asset_set['data']);

					$asset_set_string .= $this->_build_css_tag($asset_set['data'], $group);

				break;

				case 'js' :
					
					$asset_set['data']['attributes']['src'] = $this->_assets_url('js', $asset_set['files'], $asset_set['data']);

					$asset_set_string .= $this->_build_js_tag($asset_set['data'], $group);

				break;

				case 'img' :

					$asset_set['data']['attributes']['src'] = $this->_assets_url('img', $asset_set['files'], $asset_set['data']);

					$asset_set_string .= $this->_build_img_tag($asset_set['data']);

				break;
			}
			
			$asset_set_string = $this->_assets_condcom($asset_set_string, $asset_set['data']['condcom']);
			
			$string .= $asset_set_string;
		}
		
		return $string;
	}
	
	/**
	 * Build a CSS asset tag
	 * 
	 * @access private
	 * @return string The CSS asset tag
	 * @param array $data Configuration of the CSS asset
	 **/
	private function _build_css_tag($data)
	{
		if($data['type'] == 'link')
		{
			$data['attributes']['href'] = $data['asset_url'];
			
			return '<link ' . $this->_assets_attributes($data['attributes']) . ' />';
		}
		elseif($data['type'] == 'import')
		{
			return '<style ' . $this->_assets_attributes($data['attributes']) . '>@import url("' . $data['asset_url'] . '");</style>';
		}
		else
		{
			show_error("Invalid CSS type");
		}
	}
	
	/**
	 * Builds a JS asset tag
	 * 
	 * @access private
	 * @return string The JS asset tag
	 * @param array $data Configuration of the JS asset
	 **/
	private function _build_js_tag($data)
	{
		return '<script ' . $this->_assets_attributes($data['attributes']) . '></script>';
	}
	
	/**
	 * Builds an image asset tag
	 * 
	 * @access private
	 * @return string The image asset tag
	 * @param array $data Configuration of the image asset
	 **/
	private function _build_img_tag($data)
	{
		return '<img ' . $this->_assets_attributes($data['attributes']) . ' />';
	}
	
	/**
	 * Builds asset URL
	 * 
	 * @access private
	 * @return string The assets URL
	 * @param string $type The type of assets
	 * @param array $names The asset names
	 * @param array $data Configuration of the assets
	 **/
	private function _assets_url($type, $names, $data)
	{
		if(!is_array($names))
		{
			$names = array($names);
		}
			
		$url = '';
			
		if(isset($data['external']) && $data['external'])
		{
			$url .= $names[0];
		}
		else
		{
			$files = implode($this->_ci->config->item('bucket_asset_name_separator'), $names);

			$url .= base_url();
			$url .= $this->_ci->config->item('bucket_assets_url_path');
			$url .= $this->get_behaviour_item('browser_cache_'.$type)  && (!isset($data['cache']) || $data['cache']) ? $this->_get_assets_id() . '/' : '';
			$url .= $this->_ci->config->item('bucket_assets_' . $type . '_path');
			$url .= $files . $data['extension'];
		}
		
		return $url;
	}
	
	/**
	 * Builds asset tag attributes
	 * 
	 * @access private
	 * @return string The assets tag attributes
	 * @param array $attributes The assets tag attributes
	 **/
	private function _assets_attributes($attributes)
	{
		$string = '';
		
		foreach($attributes as $name => $value)
		{
			$string .= $name . '="' . $value . '" ';
		}
		
		return $string;
	}
	
	/**
	 * Builds asset conditional comment
	 * 
	 * @access private
	 * @return string The assets tag conditional comment
	 * @param string $asset_tag The assets tag
	 * @param array $attributes The assets tag conditional comment type
	 **/
	private function _assets_condcom($asset_tag, $condcom)
	{
		if($condcom)
		{
			$asset_tag = '<!--[if '.$condcom.']>' . $asset_tag . '<![endif]-->';
		}
		
		return $asset_tag;
	}
	
	/**
	 * Renders an asset set
	 * 
	 * @access public
	 * @return string The contents of the assets in the set
	 * @param string $type The asset set type
	 * @param string $extension The asset set extension
	 * @param string $names A separated list of asset names
	 * @param boolean $return Return or echo the asset set
	 **/
	public function render_asset($type, $extension, $names, $return = TRUE)
	{
		$this->_is_asset_layout = TRUE;
		
		$output = '';
		
		$asset_dir = $this->_get_asset_type_dir($type);
		
		$names_array = explode($this->_ci->config->item('bucket_asset_name_separator'), $names);
	
		$file_found = FALSE;
	
		if(($use_cache = $this->get_behaviour_item('application_cache_'.$type)) !== FALSE)
		{
			if(($cached = $this->_get_cached_asset($type, $names_array, $extension)) !== FALSE)
			{
				$file_found = TRUE;

				$output = $cached;
			}
		}

		if(!$file_found)
		{
			foreach($names_array as $name)
			{
				if(file_exists($this->_get_view_path() . $asset_dir . $name . $extension))
				{
					$file_found = TRUE;
					
					$asset_output = file_get_contents(APPPATH . 'views/'.$asset_dir . $name . $extension);
					
					if(in_array($type, array('css', 'js')))
					{
						if($this->get_behaviour_item('minify_'.$type))
						{
							$asset_output = $this->_minify($type, $asset_output);
						}
					}

					$output .= $asset_output;
				}
			}
		}

		if($file_found)
		{
			if($use_cache)
			{
				$this->_cache_asset($type, $names_array, $extension, $output);
			}

			if($this->get_behaviour_item('gzip_'.$type))
			{
				$this->_set_gzip($type);
			}
			
			if($this->get_behaviour_item('browser_cache_'.$type))
			{
				$this->_set_expires_headers();
			}

			$this->_set_content_type_headers($type, $extension);
			
			if($return)
			{
				return $output;
			}
			else
			{
				echo $output;

				return TRUE;
			}
		}
		else
		{
			show_404();
		}
	}
	
	/**
	 * Gets the assets directory
	 * 
	 * @access private
	 * @return string The assets directory
	 * @param string $type The asset type
	 **/
	private function _get_asset_type_dir($type)
	{
		$asset_dir = '';
		
		$asset_dir .= $this->_ci->config->item('bucket_assets_path');
		$asset_dir .= $this->_ci->config->item('bucket_assets_' . $type . '_path');
		
		return $asset_dir;
	}
	
	/**
	 * Gets the cached contents of each asset in the set
	 * 
	 * @access private
	 * @return string The contents of the assets in the set
	 * @param string $type The assets type
	 * @param array $assets The names of the assets
	 * @param string $extension The assets extension
	 **/
	private function _get_cached_asset($type, $assets, $extension)
	{
		$asset_dir = $this->_get_asset_type_dir($type);
		
		$filemtime = 0;
		
		foreach($assets as $asset_name)
		{
			$asset_file = $this->_get_view_path() . $asset_dir . $asset_name . $extension;
			
			if(file_exists($asset_file))
			{
				$asset_filemtime = filemtime($asset_file);
				
				if($asset_filemtime > $filemtime)
				{
					$filemtime = $asset_filemtime;
				}
			}
		}
		
		$cache = $this->_get_cached_asset_filename($type, $assets, $extension);
		
		if(file_exists($cache))
		{
			$cachemtime = filemtime($cache);
			
			if($cachemtime > $filemtime)
			{
				return file_get_contents($cache);
			}
			
			if(!unlink($cache))
			{
				log_message('error', "Could not delete cache file  \"$cache\" - check permissions on the cache directory");
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Caches an asset set
	 * 
	 * @access private
	 * @return string The contents of the assets in the set
	 * @param string $type The assets type
	 * @param array $assets The names of the assets
	 * @param string $extension The assets extensions
	 * @param string $output The contents of the assets in the set
	 **/
	private function _cache_asset($type, $assets, $extension, $output)
	{
		$file = $this->_get_cached_asset_filename($type, $assets, $extension);
		
		if(file_put_contents($file, $output))
		{
			return TRUE;
		}
		else
		{
			log_message('error', "Could not save cache file \"$file\" - check permissions on the cache directory");
			
			return FALSE;
		}
	}
	
	/**
	 * Gets the filename of a cached asset set or to-be-cached asset set
	 * 
	 * @access private
	 * @return string The filename of the asset set
	 * @param string $type The assets type
	 * @param array $assets The names of the assets
	 * @param string $extension The assets extension
	 **/
	private function _get_cached_asset_filename($type, $assets, $extension)
	{
		$file = '';
		
		$cache_path = $this->_ci->config->item('cache_path') == '' ? BASEPATH . 'cache/' : $this->_ci->config->item('cache_path');
		$cache_path = $this->_reduce_double_slashes($cache_path . $this->_ci->config->item('bucket_assets_cache_path'));
		
		$file .= $cache_path . '/';
		$file .=  md5(serialize(array('type' => $type, 'extension' => $extension, 'names' => $assets)));
		
		return $file;
	}
	
	/**
	 * Gets an ID for the current collection of assets
	 * 
	 * @access private
	 * @return string An asset collection ID
	 **/
	private function _get_assets_id()
	{
		$filemtime = $this->_get_most_recent_filemtime($this->_get_view_path() . $this->_ci->config->item('bucket_assets_path'));
	
		if($this->_ci->config->item('bucket_obfuscate_assets_timestamp'))
		{
			$unique_id = md5($filemtime);
		}
		else
		{
			$unique_id = $filemtime.'';
		}
	
		return $unique_id;
	}
	
	/**
	 * Gets the most recent filemtime of a directory
	 * 
	 * @access private
	 * @return int The most recent filetime of the directory
	 * @param string $dir_name The directory name
	 **/
	private function _get_most_recent_filemtime($dir_name)
	{
		$dir = dir($dir_name);
		
		$last_modified = 0;
		
		while($entry = $dir->read())
		{
			if ($entry != "." && $entry != "..")
			{
				if (!is_dir($dir_name . '/'. $entry))
				{
					$current_modified = filemtime($dir_name . '/' . $entry);
				}
				else if (is_dir($dir_name . '/' . $entry))
				{
					$current_modified = $this->_get_most_recent_filemtime($dir_name . '/' . $entry);
				}
				
				if ($current_modified > $last_modified)
				{
					$last_modified = $current_modified;
				}
			}
		}
		
		$dir->close();
		
		return $last_modified;
	}
	
	/**
	 * Sets content type headers
	 *
	 * @access private
	 * @param string $type The assets type
	 * @param string $extension The assets extension
	 **/
	private function _set_content_type_headers($type, $extension)
	{
		switch($type) {

			case 'css' :
			
				header("Content-type: text/css");
			
			break;
			
			case 'js' :
			
				header("Content-type: text/javascript");
				
			break;
			
			case 'img' :
			
				switch($extension)
				{
					case '.png' :
						
						header("Content-type: image/png");
					
					break;
					
					case '.gif' :
					
						header("Content-type: image/gif");
					
					break;
					
					case '.jpg' :
					case '.jpeg' :
					
						header("Content-type: image/jpeg");
					
					break;
				}
			
			break;
		}
	}
	
	/**
	 * Set expires headers
	 *
	 * @access private
	 **/
	private function _set_expires_headers()
	{
		header("Expires: " . date("r", time() + 31556926));
		header("ETag: None");
	}
	
	/**
	 * Minifies content if need be
	 *
	 * @access private
	 * @return string The minified or non-minified asset set content
	 * @param string $type The assets type
	 * @param string $content The asset set content
	 **/
	private function _minify($type, $content)
	{
		switch($type) {
			
			case 'css' :
			
				if($this->get_behaviour_item('minify_css'))
				{
					include_once("Bucket/csstidy/class.csstidy.php");
					
					$csstidy = new csstidy();
					$csstidy->parse($content);
					
					return $csstidy->print->plain();
				}
				
				return $content;
				
			break;
			
			case 'js' :

				if($this->get_behaviour_item('minify_js'))
				{
					include_once("Bucket/jsmin/jsmin.php");
					
					return jsmin::minify($content);
				}
				
				return $content;
				
			break;
			
			case 'layout' :
			
				if($this->get_behaviour_item('minify_layout'))
				{
					return preg_replace(array(
					    '/\>[^\S ]+/s', //strip whitespaces after tags, except space
				        '/[^\S ]+\</s', //strip whitespaces before tags, except space
				        '/(\s)+/s'  // shorten multiple whitespace sequences
				        ),
				    array(
				        '>',
				        '<',
				        '\\1'
				        ), $content);
				}
				
				return $content;
			
			break;
			
			case 'asset' : 
			
				return $content;
			
			break;
			
		}
	}
	
	/**
	 * Gzips
	 *
	 * @access private
	 **/
	private function _set_gzip()
	{
		if(!$this->_gzipped)
		{
			if ($this->_ci->output->_zlib_oc == FALSE && extension_loaded('zlib'))
			{
				if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
				{
					$this->_gzipped = TRUE;

					ob_start('ob_gzhandler');
				}
			}
		}
	}
	
	/**
	 * Removes double-slashes from a string
	 * 
	 * @access private
	 * @return string The string with any double-slashes removed
	 * @param string $string The string
	 **/
	private function _reduce_double_slashes($string)
	{
		$this->_ci->load->helper('string_helper');
		
		return reduce_double_slashes($string);
	}
	
	/**
	 * Gets the CodeIgniter views path
	 * 
	 * @access private
	 * @return string The CodeIgniter views path
	 **/
	private function _get_view_path()
	{
		return $this->_ci->load->_ci_view_path;
	}
}

/* End of file Bucket.php */
/* Location: ./application/libraries/Bucket.php */