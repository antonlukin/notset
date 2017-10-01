<?php

class App {
	function __construct() {
		$query = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
		$model = $query[0];

		if(strlen($model) < 1)
			return $this->render("index");

		$worker = __DIR__ . "/models/class-{$model}.php";

		if(!preg_match("~^[a-z0-9]+$~is", $model) || !file_exists($worker))
			$this->redirect("/");

		require_once($worker);
	}

	protected function redirect($path) {
		header("Location: " . $path);
		exit;
	}

	protected function render($view, $vars = []) {
		extract($vars);

		require_once(__DIR__ . "/views/{$view}.html");
		exit;
	}
}

new App;
