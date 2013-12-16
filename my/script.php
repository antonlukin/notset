<?php

function is_ajax(){
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
} 

function halt_app($mixed){

	$message = isset($mixed['message']) ? $mixed['message'] : '';
	if(!is_ajax()) : 
		if(isset($mixed['location']))
			header("Location: " . $mixed['location']);
		else 
			header("Location: /"); 

		exit();
	endif;
	$result = (isset($mixed['success']) &&  $mixed['success'] === TRUE) ? json_encode(array("success" => $message)) : json_encode(array("error" => $message)); 

 	header('Content-type: application/json'); 
	exit($result);
} 

function check_item($start, $cron){
	$from = new DateTime($start);
	$diff = $from->diff(new DateTime())->format("%d");

	return $diff % $cron === 0;
}
 
function query_close(){

	if(!isset($_POST['item']) || empty($_POST['item']))
		halt_app(array('message' => 'Не указан идентификатор задачи'));

 	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		return false;

	mysqli_set_charset($link, "utf8");

	$item = mysqli_real_escape_string($link, (int)$_POST['item']);
	if(!mysqli_query($link, "INSERT INTO log (item_id) VALUES ('{$item}')"))
		halt_app(array('message' => 'Не удалось обновить задачу'));     		
}

function query_open(){

	if(!isset($_POST['item']) || empty($_POST['item']))
		halt_app(array('message' => 'Не указан идентификатор задачи'));

 	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		return false;

	mysqli_set_charset($link, "utf8");

	$item = mysqli_real_escape_string($link, (int)$_POST['item']);
	if(!mysqli_query($link, "DELETE FROM log WHERE item_id = '{$item}' AND DATE(time) = CURRENT_DATE"))
		halt_app(array('message' => 'Не удалось обновить задачу'));     		  
}

function query_normal($result){

  	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		return false; 

	mysqli_set_charset($link, "utf8");  

	if(!$query = mysqli_query($link, "SELECT DISTINCT item_id as id FROM log WHERE DATE(time) = CURRENT_DATE"))
		halt_app(array('message' => 'Не удалось получить список задач')); 

	while($row = mysqli_fetch_object($query))
		$done[] = $row->id;

	if(!$query = mysqli_query($link, "SELECT id, name, cron, DATE(start) as start, title FROM items"))
		halt_app(array('message' => 'Не удалось получить список задач'));

	while($row = mysqli_fetch_object($query)) :

		if(!check_item($row->start, $row->cron))
			continue; 

		$row->done = intval(in_array($row->id, $done));
		$result[] = $row;

	endwhile;

	$answer = json_encode($result);

	halt_app(array('message' => $answer, 'success' => TRUE));  
}

function query_manager($result){

  	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		return false; 

	mysqli_set_charset($link, "utf8");  

	if(!$query = mysqli_query($link, "SELECT id, name, cron, DATE(start) as start, title FROM items"))
		halt_app(array('message' => 'Не удалось получить список задач'));

	while($row = mysqli_fetch_object($query))
		$result[] = $row;

	$answer = json_encode($result);

	halt_app(array('message' => $answer, 'success' => TRUE));  
} 

function request_uri($url){
	$locations = array("normal" => "query_normal", "close" => "query_close", "open" => "query_open", "manager" => "query_manager");

	preg_match("~^[a-z0-9]+~", $url, $uri);
	$uri = array_shift($uri);                                    

	if(!array_key_exists($uri, $locations) || !function_exists($execution = $locations[$uri]))
		halt_app(array('message' => 'Действие не определено'));

	$execution();
}        

{
  	define('DB_HOST', 'localhost');
 	define('DB_USER', 'myset'); 
 	define('DB_PASSWORD', 'foepGk32fFvdxDW'); 
 	define('DB_NAME', 'myset'); 	

 	request_uri(strtolower(trim($_SERVER['REQUEST_URI'], "/")));  
}
