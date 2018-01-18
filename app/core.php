<?php
/**
 * notset tools
 *
 * Simple personal project providing whois service,
 * custom search and something more
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
Flight::map('error', function(Exception $ex) {
	Flight::render('500');
});


Flight::map('notFound', function() {
	Flight::render('404');
});


/**
 * Route index page - ip and domains lookup
 *
 * @since 2.0
 */
Flight::route("/", [
	(new notset\models\whois), 'render'
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
