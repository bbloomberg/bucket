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

/**
 * Renders the content
 * 
 * @access public
 * @return string
 * @param boolean $return
 **/
if (!function_exists('render_content'))
{
	function render_content($return = FALSE)
	{
		$_ci =& get_instance();

		return $_ci->bucket->render_content($return);
	}
}

/**
 * Renders a partial
 * 
 * @access public
 * @return string
 * @param string $partial_id
 * @param array $data
 * @param boolean $return
 **/
if (!function_exists('render_partial'))
{
	function render_partial($partial_id, $data = array(), $return = FALSE)
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_partial($partial_id, $data, $return);
	}
}

/**
 * Renders CSS and/or JS asset tags
 * 
 * @access public
 * @return string
 * @param string $type
 * @param string $group
 **/
if (!function_exists('get_assets'))
{
	function get_assets($type = FALSE, $group = FALSE)
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_assets_tags($type, $group);
	}
}

/**
 * Renders CSS asset tags
 * 
 * @access public
 * @return string
 * @param string $group
 **/
if (!function_exists('get_css'))
{
	function get_css($group = FALSE)
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_assets_tags('css', $group);
	}
}

/**
 * Renders JS asset tags
 * 
 * @access public
 * @return string
 * @param string $group
 **/
if (!function_exists('get_js'))
{
	function get_js($group = FALSE)
	{
		$_ci =& get_instance();

		return $_ci->bucket->render_assets_tags('js', $group);
	}
}

/**
 * Renders a CSS, JS or image tag
 * 
 * @access public
 * @return string
 * @param string $type
 * @param string $name
 * @param string $data
 **/
if (!function_exists('asset'))
{
	function asset($type, $name, $data = array())
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_asset_tag($type, $name, $data);
	}
}

/**
 * Renders a CSS tag
 * 
 * @access public
 * @return string
 * @param string $name
 * @param string $data
 **/
if (!function_exists('css'))
{
	function css($name, $data = array())
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_asset_tag('css', $name, $data);
	}
}

/**
 * Renders a JS tag
 * 
 * @access public
 * @return string
 * @param string $name
 * @param string $data
 **/
if (!function_exists('js'))
{
	function js($name, $data = array())
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_asset_tag('js', $name, $data);
	}
}

/**
 * Renders an image tag
 * 
 * @access public
 * @return string
 * @param string $name
 * @param string $data
 **/
if (!function_exists('img'))
{
	function img($name, $data = array())
	{
		$_ci =& get_instance();
	
		return $_ci->bucket->render_asset_tag('img', $name, $data);
	}
}

/* End of file bucket_helper.php */
/* Location: ./application/helpers/bucket_helper.php */