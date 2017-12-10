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

		return app::render("whois", $args);
	}
}
