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
			return app::render('whois', ['query' => '']);

		if(filter_var($query->q, FILTER_VALIDATE_IP))
			return app::render('whois', $this->detect($query->q));

		return app::render('whois', $this->lookup($query->q));
	}

	private function detect($query) {
		return [
			'query' => $query,
			'error' => "Sorry, we can't process your request"
		];
	}

	private function lookup($query, $data = '') {
		return [
			'query' => $query,
			'error' => "Sorry, we can't process your request"
		];

		list($server, $domain) = $this->server($host);

		if($server === null)
			return ;

		$socket = fsockopen($server, 43);
		fputs($socket, $domain . '\r\n');

		while(!feof($socket)) {
			$data .= fgets($socket, 128);
		}

		fclose($socket);

		return $data;
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
