<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bucket
 * 
 * A layout and asset library for CodeIgniter
 * 
 * @package		Bucket
 * @version		0.1.1
 * @author		Backstack Development <http://backstack.ca>
 * @link		http://backstack.ca/projects/bucket
 * @copyright	Copyright (c) 2010, Backstack Development
 * @license		http://opensource.org/licenses/mit-license.php MIT Licensed
 * 
 */

$config['bucket_layouts_path'] = 'layouts/';
$config['bucket_content_path'] = 'content/';
$config['bucket_partials_path'] = 'partials/';
$config['bucket_assets_path'] = 'assets/';

$config['bucket_assets_cache_path'] = 'assets/';

$config['bucket_assets_css_path'] = 'css/';
$config['bucket_assets_js_path'] = 'js/';
$config['bucket_assets_img_path'] = 'img/';

$config['bucket_assets_css_extension'] = '.css';
$config['bucket_assets_js_extension'] = '.js';

$config['bucket_assets_url_path'] = 'assets';
$config['bucket_asset_name_separator'] = '--';
$config['bucket_obfuscate_assets_timestamp'] = FALSE;

$config['bucket_default_behaviour'] = 'production';

$config['bucket_behaviours']['development'] = array(

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
	
);

$config['bucket_behaviours']['production'] = array(

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
	
);

/* End of file layout.php */
/* Location: ./application/config/layout.php */