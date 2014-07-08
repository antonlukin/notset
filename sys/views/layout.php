<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Набор скриптов и прочей информации для помощи в настройке linux-сервера">
	
    <title><?= $title ?> | Бесполезный справочник юного сисадмина</title>
	<link rel="icon" type="image/png" href="http://icons.notset.ru/fav/icon-beeralt.png" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600&subset=latin,cyrillic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/assets/css/pure-min.css">
    <!--[if lte IE 8]-->
        <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <!--[endif]-->
    <!--[if gt IE 8]-->
        <link rel="stylesheet" href="/assets//css/layouts/side-menu.css">
    <!--[endif]-->
	<link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body>

<div id="layout">    
    <a href="#menu" id="menuLink" class="menu-link"><span></span></a>

    <div id="menu">
        <div class="pure-menu pure-menu-open">            

			<ul>    
			<?php foreach($menu as $uri => $value) : ?>

				<?php $class = ($current == $uri) ? 'pure-menu-selected' : ''; ?>

				<li class="<?= $class ?>">
					<a href="/<?= $uri ?>"><?= $value ?></a>
				</li>

			<?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div id="main">
		<?= $content ?>
    </div>
</div>

<script src="/assets/js/ui.js"></script>

</body>
</html>
