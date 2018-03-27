<?php
/**
 * notset tools
 *
 * Simple personal project providing whois service,
 * custom search and something more
 *
 * @copyright   Copyright (c) 2017, Anton Lukin <anton@lukin.me>
 * @license     MIT, https://github.com/antonlukin/notset/LICENSE
 * @since       2.1
 */

namespace notset\models;

use Flight as app;

class notepad {
	public function render() {
		return app::render('notepad');
	}
}
