<?php

class CookBook {
	function __construct() {
        $app = new App;

		return $this->router($app);
	}

	private function router($app) {
		$args = $app->args;

		return $app->render("cookbook", []);
	}
}

new CookBook;
