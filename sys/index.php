<?php

require 'flight/Flight.php';

$menu = array(
	"ubuntu" => "Настройка Ubuntu",
	"tinydns" => "Tinydns и dnscache",
	"pureftp" => "Правильный pureftp",
	"vpn" => "Настройка VPN",
	"postfix" => "Postfix DKIM",
	"nginx" => "Nginx и https",
 	"misc" => "Полезные команды", 
	"scripts" => "Бесполезные скрипты"
);


{
	Flight::set('menu', $menu);

	Flight::route('/@uri', function($uri){
        $menu = Flight::get('menu');

		if(!array_key_exists($uri, $menu))
			return true;

		$title = $menu[$uri];

		Flight::render($uri, array('menu' => $menu, 'current' => $uri), 'content');

		Flight::render('layout', array('title' => $title));
	});

	Flight::route('*', function(){
		$menu = Flight::get('menu'); 

		Flight::redirect('/' . current(array_keys($menu)));
	});

	Flight::start();
}
?>
