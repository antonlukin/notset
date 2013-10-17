<?php

function site_url(){
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domain = $_SERVER['HTTP_HOST'];
	return $protocol . $domain;
} 
{
	define('APP_ID', 'vk_app_id');  
	define('APP_SECRET', 'vk_app_secret');
	define('ABS_PATH', realpath(__DIR__ . '/..')); 
	define('SITE_URL', site_url());
 	define('CLEAR_TTL', 5);  // how long to store audiofiles (in minutes)
	define('PER_PAGE', 20);  // audiofiles count per page

	define('DB_HOST', 'localhost');
 	define('DB_USER', 'database_user'); 
 	define('DB_PASSWORD', 'database_password'); 
 	define('DB_NAME', 'database_name'); 
	
}
