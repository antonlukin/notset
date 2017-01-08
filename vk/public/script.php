<?php

function init_settings(){
	require_once('../app/settings.php'); 

    return get_session();
}

function is_ajax(){
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function halt_app($mixed){
	$message = isset($mixed['message']) ? $mixed['message'] : '';
	if(!is_ajax()) : 
		if(isset($mixed['location']))
			header("Location: " . $mixed['location']);

		exit($message);
	endif;
	$result = (isset($mixed['success']) &&  $mixed['success'] === TRUE) ? json_encode(array("success" => $message)) : json_encode(array("error" => $message)); 

 	header('Content-type: application/json'); 
	exit($result);
}

function get_session(){
	session_start(); 
	
	$session = (!isset($_SESSION[SESSION_NAME])) ? FALSE : $_SESSION[SESSION_NAME];
	
	session_write_close();

	return $session;
}

function set_session($session){
 	session_start(); 

	if(!empty($session))
		$_SESSION[SESSION_NAME] = $session;

	session_write_close();

	return true;
}

function get_template($name, $replace = array()){
	$path = ABS_PATH . "/templates/{$name}.html";
	
	if(!file_exists($path))
		halt_app(array("message" => "Невозможно загрузить шаблон", 'success' => FALSE));

	$template = file_get_contents($path);

	foreach($replace as $k => $v){
		$template = str_replace($k, $v, $template);
	}

	return $template;
}

function cut_filename($response){
	return html_entity_decode(mb_substr($response, 0, 30, 'utf-8'));
}

function set_filename($response){
    $folder = "files/{$response->aid}";

	$filename = cut_filename($response->artist) . " - " . cut_filename($response->title);
	$filename = trim(preg_replace("/([^\w\s\d\-_~\[\]\(\)]|[\.]{2,})/u", '', $filename), " ,.");

	if(!$filename)
		$filename = rand(10000,99999);

	if(!is_dir($folder) && !mkdir($folder))
		halt_app(array('message' => 'Не удалось сохранить файл', 'success' => FALSE));

	return array("{$folder}/{$filename}.mp3", $filename);
}

function get_audio($url, $path){
	if(file_exists($path))
		return SITE_URL."/{$path}"; 

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	 
	if(file_put_contents($path, $data))
		return SITE_URL."/{$path}";

	return false;
}

function update_db($aid, $filename, $vkid){
	$aid = intval($aid);
	$vkid = intval($vkid);

	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		return false;

	$filename = mysqli_real_escape_string($link, $filename);
	
	mysqli_set_charset($link, "utf8");
	mysqli_query($link, "INSERT INTO history (vkid, name) VALUES ({$vkid}, '{$filename}')"); 

	if($count = mysqli_query($link, "SELECT aid FROM audio WHERE aid='$aid' LIMIT 1"))
		if(mysqli_num_rows($count) > 0)
			return true;

	if(!mysqli_query($link, "INSERT INTO audio (aid, vkid, name) VALUES ({$aid}, {$vkid}, '{$filename}')"))
		return false;


	return true;
}

function query_promo($key){
 	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
		halt_app(array('message' => 'Не удалось подключиться к базе данных', 'success' => FALSE)); 

	mysqli_set_charset($link, "utf8");

	$ip = ip2long($_SERVER['REMOTE_ADDR']);

	if(!mysqli_query($link, "INSERT INTO promo (ip) VALUES ('{$ip}')"))
		halt_app(array('message' => 'Не удалось добавить действие в БД', 'success' => FALSE)); 

	halt_app(array('message' => 'Действие успешно добавлено', 'success' => TRUE));  
}

function query_count($key){
	if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
 		halt_app(array('message' => 'Не удалось подключиться к базе данных', 'success' => FALSE));
	
	if(!$select = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(id) as count FROM history")))
		halt_app(array('message' => 'Не удалось подключиться к базе данных', 'success' => FALSE));  

	halt_app(array('message' => $select['count'], 'success' => TRUE));
}

function query_download($key){
	if($key === FALSE)   
		halt_app(array('location' => '/', 'message' => 'Необходима авторизация'));     

	if(!$aid = $_POST['aid'])
        halt_app(array('location' => '/', 'message' => 'Неверный аудиофайл'));

	if(!$answer = file_get_contents("https://api.vk.com/method/audio.get?aids=" . $aid . "&access_token=" . $key))
		halt_app(array('location' => '/', 'message' => 'Нет доступа к VK api'));

	$answer = @json_decode($answer)->response[0];

	if(!$answer)
		halt_app(array('message' => 'Возникла ошибка доступа к API ВКонтакте', 'success' => FALSE));   
	
	$url = $answer->url;
	list($path, $filename) = set_filename($answer);

	if(($location = get_audio($url, $path)) && update_db($aid, $filename, $answer->owner_id))
		halt_app(array('message' => $location, 'success' => TRUE)); 

   	halt_app(array('message' => 'Возникла ошибка при попытке сохранить аудиофайл', 'success' => FALSE));  
}

function query_get($key){
	$count = PER_PAGE;
	$offset = isset($_REQUEST['offset']) ? (int)$_REQUEST['offset'] * $count : 0;

	if($key === FALSE)    
		halt_app(array('location' => '/', 'message' => 'authentication required'));

	if(!$answer = @file_get_contents("https://api.vk.com/method/audio.get?count={$count}&offset={$offset}&access_token=" . $key))
		halt_app(array('location' => '/', 'message' => 'VK api не доступен'));

	$answer = json_decode($answer);

	if(isset($answer->error))
		halt_app(array('location' => '/', 'message' => 'VK ошибка:' . serialize($answer->error))); 

	halt_app(array('message' => $answer, 'success' => TRUE));
}
 
function query_login($key){
	if($key !== FALSE)
		halt_app(array('message' => get_template("close")));   

	if(isset($_GET['error']))
 		halt_app(array('message' => get_template("login")));    

	if(isset($_GET['code'])){
		$authlink  = "https://oauth.vk.com/access_token?client_id=" . APP_ID;
		$authlink .= "&client_secret=" . APP_SECRET . "&code=" . $_GET['code'];
		$authlink .= "&redirect_uri=" . SITE_URL . "/login";

		$answer = json_decode(file_get_contents($authlink));
		if(!$answer->access_token)
			halt_app(array('message' => get_template("login")));

		set_session($answer->access_token);
 		halt_app(array('message' => get_template("close")));
	}

	$authlink  = "https://oauth.vk.com/authorize?client_id=" . APP_ID;      
	$authlink .= "&scope=audio&redirect_uri=" . SITE_URL . "/login";
	
	halt_app(array('location' => $authlink));
}
 
function request_uri($url, $key){
	$locations = array("login" => "query_login", "get" => "query_get", "download" => "query_download", "count" => "query_count", "promo" => "query_promo");

	preg_match("~^[a-z0-9]+~", $url, $uri);
	$uri = array_shift($uri);                                    

	if(!array_key_exists($uri, $locations) || !function_exists($execution = $locations[$uri]))
		halt_app(array('message' => 'unsigned request', 'location' => '/'));

	$execution($key);
} 

{
	$key = init_settings();
	request_uri(strtolower(trim($_SERVER['REQUEST_URI'], "/")), $key); 
}    
