<?php
if(trim($_SERVER["REQUEST_URI"], "/") === 'plain')
	die($_SERVER["REMOTE_ADDR"]);

$vars = array(
	"HTTP_X_FORWARDED_FOR", 
	"HTTP_USER_AGENT", 
	"HTTP_ACCEPT_LANGUAGE", 
	"HTTP_CONNECTION", 
	"HTTP_REFERER", 
	"HTTP_FORWARDED_FOR", 
	"HTTP_X_COMING_FROM", 
	"HTTP_VIA", 
	"HTTP_XPROXY_CONNECTION", 
	"HTTP_CLIENT_IP", 
	"REMOTE_HOST", 
	"REMOTE_USER"
);

function print_vars($vars, $out = ''){
	foreach($vars as $var){
		if(!isset($_SERVER[$var]))
			continue;

		$value = strtolower($var) . ": " . $_SERVER[$var];
		$out .= "<div>{$value}</div>";
	}
	return $out;
}
?> 
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8" />
 	<title>Определение ip-адреса</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<meta name="viewport" content="width=600" />  
	<meta name="description" content="Определение ip-адреса" />
	<meta name="keywords" content="определение ip, узнать ip адрес, workenv, разработка, администрирование" />
	<link rel="icon" type="image/png" href="//icons.notset.ru/fav/icon-globe.png" />
	<link rel="stylesheet" type="text/css" href="//notset.ru/assets/styles/common.css" />
	<link href="//fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="content">
		<h1>Определение ip-адреса<span>Информация об ip и расширенные заголовки браузера</span></h1>
		<div class="block">
			<p>
				<a class="black" target="_blank" href="https://www.nic.ru/whois/?query=<?= $_SERVER["REMOTE_ADDR"] ?>&amp;do_search=Search"><?= $_SERVER["REMOTE_ADDR"] ?></a>
			</p>       
			<div class="block-extended">
				<?= print_vars($vars) ?>       		   
			</div>
		</div>
		<header>
			&larr; <a href="http://notset.ru/" title="Сервисы для администрирования и разработки">на главную</a>
		</header> 
	</div>
	<?php include "include/ga.php"; ?> 
</body>
</html> 
