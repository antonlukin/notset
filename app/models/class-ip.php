<?php

class Ip {
	function __construct() {
        $app = new App;

		return $this->router($app);
	}

	private function router($app) {
		$args = $app->args;

 		$vars = [
			'ip' => (filter_var($args[0], FILTER_VALIDATE_IP)) ? $args[0] : $_SERVER['REMOTE_ADDR'],
			'hl' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
		];

		return $app->render("ip", $vars);
	}
}

new Ip;
