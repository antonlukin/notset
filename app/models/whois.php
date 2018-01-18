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
		$query = app::request()->query;

		if(empty($query->q))
			return app::render('whois', ['query' => app::request()->ip]);

		if(filter_var($query->q, FILTER_VALIDATE_IP))
			return app::render('whois', $this->detect($query->q));

		return app::render('whois', $this->lookup($query->q));
	}

	private function detect($query) {
		$check = 'http://ip-api.com/json/' . $query;
		$reply = json_decode(file_get_contents($check), true);

		if(json_last_error() !== JSON_ERROR_NONE)
			return ['query' => $query, 'reply' => "Error: can't fetch ip data. Please try later"];

		return ['query' => $query, 'reply' => json_encode($reply, JSON_PRETTY_PRINT)];
	}

	private function lookup($query, $data = '') {
		$link = (strpos($query, '://') === false) ? 'http://' . $query : $query;

		list($server, $domain) = $this->server(parse_url($link, PHP_URL_HOST));

		if($server === null)
			return ['query' => $query, 'reply' => "Error: can't find related whois server"];

		$socket = fsockopen($server, 43, $errno, $errstr, 10);
		if (!$socket)
			return ['query' => $query, 'reply' => "Error: whois server is unreachable"];

		fputs($socket, $domain . "\r\n");

		while(!feof($socket)) {
			$data .= fgets($socket, 128);
		}

		fclose($socket);

		return ['query' => $query, 'reply' => $data];
	}

	private function server($host, $server = null) {
		if(!file_exists(app::get('app.data') . '/whois.json'))
			return $server;

		$servers = json_decode(file_get_contents(app::get('app.data') . '/whois.json'), true);

		if(json_last_error() !== JSON_ERROR_NONE)
			return $server;

		for($i = 0; $i <= substr_count($host, '.'); $i++) {
			$part = substr($host, strpos($host, '.') + 1);

			if(array_key_exists($part, $servers)) {
				$server = $servers[$part]['host'];

				break;
			}

			$host = $part;
		}

		return [$server, $host];
	}
}
