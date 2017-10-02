<?php

class CS extends App {
	function __construct() {
		$query = $_GET['q'];
		$tld = $_GET['tld'];

		if(!preg_match("~^[a-z0-9|]+$~i", $tld))
			$tld = "test|local|example|invalid";

		return $this->request($query, $tld);
	}

	function request($query, $tld) {
		$dev = preg_match("~^[a-z0-9-.]+\.({$tld})(/[^\s]*)?$~i", $query);
		$url = ($dev === 1) ? "http://{$query}" : "https://www.google.com/search?q={$query}";

		return $this->redirect($url);
	}
}

new CS;
