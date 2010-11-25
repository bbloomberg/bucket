<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// COPY these two routes to ./application/config/routes.php

// Default controller

	$route['default_controller'] = "bucket/index/index";

// Assets

	$route['assets/([0-9a-zA-Z]+/)?(css|js|img)/([a-zA-Z0-9\-\/]+)([a-zA-Z\.]{2,4})'] = "assets/index/index/$2/$4/$3";