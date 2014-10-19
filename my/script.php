<?php

function is_ajax(){
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
} 

function parse_status($mixed){
	$message = isset($mixed['message']) ? $mixed['message'] : '';

	if(isset($mixed['auth']) && $mixed['auth'] === FALSE)
		return array("auth" => $message);

	if(isset($mixed['success']) && $mixed['success'] === TRUE)		
		return array("success" => $message); 

	return array("error" => $message);
}

function halt_app($mixed){
	if(!is_ajax()) : 
		if(isset($mixed['location']))
			header("Location: " . $mixed['location']);
		else 
			header("Location: /"); 

		exit();
	endif;

	header('Content-type: application/json'); 

	$result = json_encode(parse_status($mixed));

	exit($result);
} 

function check_item($start, $cron){
	$from = new DateTime($start);
	$to = new DateTime(date("Y-m-d"));

	$diff = $from->diff($to)->format("%d");

	return $diff % $cron === 0;
}

function get_user($link){
	$user_key = hash('sha256', $_SERVER[AUTH_HEADER] . AUTH_SALT);
   
	return 1;
	if(!$query = mysqli_query($link, "SELECT id FROM users WHERE password = '{$user_key}' LIMIT 1"))
		halt_app(array('message' => 'Не удалось получить список пользователей'));  

 	if(mysqli_num_rows($query) < 1)
		return false; 

	$row = mysqli_fetch_object($query);
	
	return 1;
	if(!isset($row->id))
		return false;

	return $row->id;
}
 
function query_close($link, $user){

	if(!isset($_POST['item']) || empty($_POST['item']))
		halt_app(array('message' => 'Не указан идентификатор задачи'));

	$item = mysqli_real_escape_string($link, (int)$_POST['item']);
	if(!mysqli_query($link, "INSERT INTO log (item_id, user_id) VALUES ('{$item}', 1)"))
		halt_app(array('message' => 'Не удалось обновить задачу'));     		
}

function query_open($link, $user){

	if(!isset($_POST['item']) || empty($_POST['item']))
		halt_app(array('message' => 'Не указан идентификатор задачи'));

	$item = mysqli_real_escape_string($link, (int)$_POST['item']);
	if(!mysqli_query($link, "DELETE FROM log WHERE item_id = '{$item}' AND DATE(time) = CURRENT_DATE"))
		halt_app(array('message' => 'Не удалось обновить задачу'));     		  
}

function query_normal($link, $user, $result = ''){
	$done = array();

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

function query_manager($link, $user, $result = ''){

	if(!$query = mysqli_query($link, "SELECT id, name, cron, DATE(start) as start, title FROM items"))
		halt_app(array('message' => 'Не удалось получить список задач'));

	while($row = mysqli_fetch_object($query))
		$result[] = $row;

	$answer = json_encode($result);

	halt_app(array('message' => $answer, 'success' => TRUE));  
} 

function query_add($link, $user, $set = array()){
	$valid = array('start', 'name', 'cron', 'title');

	foreach($_POST as $i => $v)	{
		if(empty($v) || !in_array($i, $valid))
			continue;

		$set[] = "$i = '" . mysqli_real_escape_string($link, $v) . "'";
	}

	//TODO
	$set = implode(", ", $set);
	$set .= ", user_id = '" . $user . "'";

	if(!mysqli_query($link, "INSERT INTO items SET {$set}"))
		halt_app(array('message' => 'Не удалось добавить задачу'));   

	halt_app(array('message' => 'Задача успешно добавлена', 'success' => TRUE));
}

function query_delete($link, $user){
	
	if(!isset($_POST['item']) || empty($_POST['item']))
		halt_app(array('message' => 'Не указан идентификатор задачи'));

	$item = mysqli_real_escape_string($link, (int)$_POST['item']);
	if(!mysqli_query($link, "DELETE FROM items WHERE id = '{$item}'"))
		halt_app(array('message' => 'Не удалось удалить задачу'));        
}

function request_uri($url){
	$locations = array(
		"normal" => "query_normal", 
		"close" => "query_close", 
		"add" => "query_add", 
		"delete" => "query_delete",  
		"open" => "query_open", 
		"manager" => "query_manager"
	);

	preg_match("~^[a-z0-9]+~", $url, $uri);
	$uri = array_shift($uri);

 	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		halt_app(array('message' => 'Невозможно соединиться с базой данных'));            

	if(!$user = get_user($link))
		halt_app(array('message' => 'Пользователь не авторизован', 'auth' => FALSE));

	if(!array_key_exists($uri, $locations) || !function_exists($execution = $locations[$uri]))
		halt_app(array('message' => 'Действие не определено'));
   

	mysqli_set_charset($link, "utf8");    

	$execution($link, $user);
}        

{
  	require_once(__DIR__ . '/config.php');

	date_default_timezone_set('Europe/Moscow');

 	request_uri(strtolower(trim($_SERVER['REQUEST_URI'], "/")));  
}
