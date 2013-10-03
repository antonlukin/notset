<?php

require_once(realpath(__DIR__ . '/settings.php')); 

function delete_dir($path) {
	return is_file($path) ? @unlink($path) : array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

function halt_app($message){
	die($message);
}

function connect_db(){
	return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

function select_db_data($link, $ids = array()){
	if(!$select = mysqli_query($link, "SELECT aid FROM audio WHERE NOW() > timestamp(DATE_ADD(created, INTERVAL " . CLEAR_TTL . " MINUTE))"))
		halt_app("can't query db");  

	while($row = mysqli_fetch_assoc($select))
		$ids[] = $row['aid'];

	mysqli_free_result($select);
	return $ids;
}

function delete_db_data($link, $id){
	$id = intval($id);

	if(!mysqli_query($link, "DELETE FROM audio WHERE aid = '{$id}'"))
		halt_app("can't delete aid: {$id}");
}

{
	if(!$link = connect_db())
		halt_app("can't connect to db");

	foreach(select_db_data($link) as $id){
		$path = ABS_PATH . "/files/" . $id;
		echo $path."\n";
		delete_dir($path);

		if(!is_dir($path))
			delete_db_data($link, $id);
	}

}
