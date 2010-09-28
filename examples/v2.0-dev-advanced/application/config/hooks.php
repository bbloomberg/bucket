<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Bucket

$hook['post_controller_constructor'][] = array(
	'class' => 'Bucket_Hook',
	'function' => 'init',
	'filename' => 'bucket_hook.php',
	'filepath' => 'hooks/bucket',
	'params' => array()
);

$hook['post_controller'][] = array(
	'class' => 'Bucket_Hook',
	'function' => 'dinit',
	'filename' => 'bucket_hook.php',
	'filepath' => 'hooks/bucket',
	'params' => array()
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */