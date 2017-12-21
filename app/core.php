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


/**
 * Set required framework options
 *
 * @since 2.0
 */
Flight::set('flight.views.path', __DIR__ . "/views");


/**
 * Set application options
 *
 * @since 2.0
 */
Flight::set('app.data', __DIR__ . "/data");


/**
 * We have to reconfigure default error handler
 *
 * @since 2.0
 */
/*
Flight::map('error', function(Exception $ex) {
	echo $ex->getTraceAsString();
});
*/


/**
 * Route index page as whois
 *
 * @since 2.0
 */
Flight::route("/", [
	(new notset\models\whois), 'render'
], true);


/**
 * Route cookbook - sysadmin advice
 *
 * @since 2.0
 */
Flight::route("/cookbook/", [
	(new notset\models\cookbook), 'render'
], true);


/**
 * Route custom search
 *
 * @since 2.0
 */
Flight::route("/cs/", [
	(new notset\models\search), 'render'
], true);


/**
* Start application with Flight
*
* @link http://flightphp.com/
*/
Flight::start();
