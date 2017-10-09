<?php

class App {
	public static $args;

	function __construct() {
		$query = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
		$model = array_shift($query);

		$this->args = $query;

		if(strlen($model) < 1)
			return $this->render("index");

		$worker = __DIR__ . "/models/class-{$model}.php";

		if(!preg_match("~^[a-z0-9]+$~is", $model) || !file_exists($worker))
			$this->redirect("/");

		require_once($worker);
	}

	public function redirect($path) {
		header("Location: " . $path);
		exit;
	}

	public function stop($text) {
		echo $text;

		exit;
	}

	public function render($view, $vars = []) {
		extract($vars);

		require_once(__DIR__ . "/views/{$view}.html");
		exit;
	}
}

new App;
