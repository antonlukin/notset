<?php

function parse_host($url){

	$url = preg_replace("/^(https?:\/\/)*(www.)*/is", "", $url); 
	$url = preg_replace("/\/.*$/is" , "" ,$url); 

	$url = trim($url, ".");
	return strtolower($url); 
}

function domain($req, $uri){
	if(isset($req))
		return $req;

	if(isset($uri))
		return $uri;

	return false;
}

function whois($data = '', $default = false){
	include "classes/list.func.php"; 
	$uri = trim($_SERVER['REQUEST_URI'], "/");
	$req = @$_REQUEST['domain'];

	if(!$url = domain($req, $uri))
		return array(false, false);

	$domain = parse_host($url);

	if(preg_match("~\.рф$~", $domain)) {
        include "classes/idn.class.php";

        $idn = new idna_convert(); 
		$default = $domain;  
		$domain = $idn->encode($domain);       
	}

	for($i = 0; $i <= substr_count($domain, "."); $i++){
		$zone = substr($domain, strpos($domain, ".") + 1);
		if(!array_key_exists($zone, $servers)){
			$domain = $zone;
 			continue;
		}
		$whois = $servers[$zone];
		break;
	}

	if(empty($whois))
		return array(parse_host($url), false);

	$fp = fsockopen($whois, 43);
	fputs($fp, "$domain\r\n");
	while(!feof($fp))
		$data .= fgets($fp,128);
	fclose($fp);

	if($default)
		return array($default, $data);
	
	return array($domain, $data);
}

{
	set_time_limit(30); 
	
	list($domain, $data) = whois();
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8" />
 	<title>notset / Whois доменов</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<meta name="viewport" content="width=600" /> 
	<meta name="description" content="Whois доменов из более 50 зон" />
	<meta name="keywords" content="определение whois, узнать адрес домена, workenv, разработка, администрирование" />
	<link rel="shortcut icon" href="//icons.notset.ru/cogs.png"> 
	<link rel="stylesheet" type="text/css" href="//notset.ru/assets/styles/common.css" />
	<link href="//fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="content">
		<h1>Whois доменов<span>Данные о владельцах и регистраторах доменов</span></h1>
		<div class="block">
			<form action="/" method="post">
				<div class="block-input">
				<input type="text" name="domain" placeholder="Доменное имя" value="<?= $domain ? $domain : '' ?>" />
					<button type="submit"><img src="//notset.ru/assets/images/search.png" alt="Вперед" /></button>
				</div>     
			</form>
			<?php if($domain) : ?>
				<div class="block-extended">
					<pre><?= $data ? $data : "Не удалось получить информацию. Возможно адрес введен в неверном формате или зона не поддерживается" ?></pre>
				</div>
			<?php endif; ?>
		</div>
		<header>
			&larr; <a href="http://notset.ru/" title="Сервисы для администрирования и разработки">на главную</a>
		</header> 
	</div>
<?php include "classes/ga.php"; ?>  
</body>
</html> 
