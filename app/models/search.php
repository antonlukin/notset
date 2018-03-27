<?php
/**
 * notset tools
 *
 * Simple personal project providing whois service,
 * custom search and something more
 *
 * @copyright   Copyright (c) 2017, Anton Lukin <anton@lukin.me>
 * @license     MIT, https://github.com/antonlukin/notset/LICENSE
 * @since       2.0
 */

namespace notset\models;

use Flight as app;


class search {
	public $tld = 'test|local|example|invalid';
	public $cse = 'https://www.google.com/search?q=';

	public function render() {
		$query = app::request()->query;

		if(empty($query->q))
			return app::render('search');

		if(isset($query->tld) && preg_match("~^[a-z0-9|]+$~i", $query->tld))
			$this->tld = $query->tld;

		return $this->request($query->q);
	}

	private function request($query) {
		if(preg_match("~^[a-z0-9-.]+\.({$this->tld})(/[^\s]*)?$~i", $query))
			return app::redirect('http://' . $query);

		return app::redirect($this->cse . $query);
	}
}
