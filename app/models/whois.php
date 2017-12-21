<?php
/**
 * notset tools
 *
 * Simple personal project providing whois service,
 * cookbook manual, custom search and something more
 *
 * @copyright   Copyright (c) 2017, Anton Lukin <anton@lukin.me>
 * @license     MIT, https://github.com/antonlukin/notset/LICENSE
 * @version     2.0
 */

namespace notset\models;

use Flight as app;

class whois {
	public function render() {
		$args = [
			"ip" => app::request()->ip
		];

//		echo $this->lookup("https://lukin.blog/?fds");

		return app::render("whois", $args);
	}

	private function lookup($url) {
		$host = parse_url($url, PHP_URL_HOST);
		$server = $this->server($host);

		if($server === null)
			return ;

		$data = '';

		$socket = fsockopen($server, 43);
		fputs($socket, "$host\r\n");

		while(!feof($socket)) {
			$data .= fgets($socket, 128);
		}

		fclose($socket);

		echo $data;
	}

	private function server($host, $server = null) {
		if(!file_exists(app::get('app.data') . "/whois.json"))
			return $server;

		$servers = json_decode(file_get_contents(app::get('app.data') . "/whois.json"), true);

		if(json_last_error() !== JSON_ERROR_NONE)
			return $server;

		for($i = 0; $i <= substr_count($host, "."); $i++) {
			$part = substr($host, strpos($host, ".") + 1);

			if(array_key_exists($part, $servers)) {
				$server = $servers[$part];

				break;
			}

			$host = $part;
		}

		return $server['host'];
	}
}
