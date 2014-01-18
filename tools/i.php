<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="robots" content="follow, index" />
    <meta name="keywords" content="ip, test, notset" />
    <meta name="description" content="ip test" />
	<meta name="viewport" content="user-scalable=no, width=device-width" />
	<link rel="icon" type="image/png" href="//icons.notset.ru/cogs.png" />
	<title>notset / ip</title>
	<style type="text/css">
		*{margin:0; padding:0;}
		html,body{height:100%;}
		body{position:relative; color:#333; font: normal 13px/16px Tahoma, Helvetica, sans-serif;}
		div.main{position:absolute; top:50%; left:50%; left:50%; width:100px; text-align:center; margin-top:-50px; margin-left:-50px; }
		p.line{border-bottom:solid 1px #ccc; display:inline-block; width:auto;padding-bottom:5px;margin-bottom:5px;}
		div.ext{margin:10px 15px; padding-top:10px; border-top:solid 1px #ccc; position:absolute;}
		a{color:#333;text-decoration:none;}
		a:hover{text-decoration:underline;}
	</style>
</head>
<body>
	<div class="main">
		<p>
			<a target="_blank" href="https://www.nic.ru/whois/?query=<?= $_SERVER["REMOTE_ADDR"] ?>&amp;do_search=Search"><?= $_SERVER["REMOTE_ADDR"] ?></a>
		</p>
	</div>
<?php include "classes/ga.php"; ?>
<!--ip:<?= $_SERVER["REMOTE_ADDR"] ?>-->
</body>
</html>
