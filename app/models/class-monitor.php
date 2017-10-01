<?php

function ajax(){
	return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function halt($message){
	if(!ajax()) :
		header("Location: /");
		exit();
	endif; 
	
	if($message)
		$result = json_encode($message);

	header('Content-type: application/json');
	exit($result);
}

function address($host, $timeout = 3) {
	$query = `nslookup -timeout=$timeout -retry=1 $host`;
	if(preg_match('/\nAddress: (.*)\n/', $query, $matches))
		return trim($matches[1]);
	return $host;
}

function query_status(){
	$host = $_POST['host'];
	$url = "http://" . $host;

	if(!isset($host))
		halt(array('error' => 'host not set'));
	
	$ctx = stream_context_create(array('http'=> array('timeout' => 3)));

	$ip = address($host);
	if(preg_match("/^127\./", $ip))
		$ip = file_get_contents("http://ip.notset.ru/plain", false, $ctx);
	
	if(!isset($ip) || $ip == $host)
		halt(array('address' => '-', 'status' => false));

	$page = file_get_contents($url, false, $ctx);
	if(!$page || 0 === substr_count ($page, $host))
		halt(array('address' => $ip, 'status' => false));

	halt(array('address' => $ip, 'status' => true));
}

{
	$function = "query_" . trim($_SERVER['REQUEST_URI'], '/');

	if(function_exists($function))
		return $function();

	halt(array('error' => 'wrong url'));
}
