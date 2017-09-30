<?php

function r($url) {
	header("Location: {$url}");
	exit();
}

function q($query) {
	$tld = "test|local|example|invalid";

	$dev = preg_match("~^[a-z0-9-.]+\.({$tld})(/[^\s]*)?$~i", $query);

	if($dev === 1)
		return r("http://{$query}");

	return r("https://www.google.com/search?q={$query}");
}

return q($_GET['q']);

