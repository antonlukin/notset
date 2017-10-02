<?php

class Ip extends App {
	function __construct() {
		$args = [
			"ip" => $_SERVER["REMOTE_ADDR"]
		];

		return $this->render("ip", $args);
	}
}

new Ip;
