<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="How to install and configure ubuntu server on VPS">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>cookbook › notset services</title>

	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet">
	<link href="https://lukin.nyc3.digitaloceanspaces.com/notset/favicon.png" rel="icon" type="image/png">
	<link rel="stylesheet" href="/assets/styles.min.css" type="text/css" media="all" />

	<meta name="theme-color" content="#222222">
</head>

<body>

<section class="wrap">
	<header class="header">
		<a class="header__link" href="https://github.com/antonlukin/notset">
			<svg class="header__icon" height="24" version="1.1" viewBox="0 0 16 16" width="24"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
		</a>
	</header>

	<article class="content content--shrink custom">
		<h1>Настройка Ubuntu Server</h1>
		<p>
			На данном этапе предполагается, что у вас установлен чистый дистрибутив Ubuntu Server старше 11.04. Все команды ниже гарантировано работают в версии 12.04.
		</p>

		<p>
			Для начала необходимо добавить пользователя, от имени которого вы будете работать в дальнейшем на сервере. Делается это командой:
			<code class="code code--root">adduser login</code>
			Здесь login ваше имя пользователя. На всех своих серверах я использую логин master.
		</p>
		<p>
			Я предпочитаю сразу же убрать необходимость ввода пароля при каждом запуске команды через sudo.
			Однако не советую этого делать всем и везде, так как это несомненно ударит по безопасности:
			<code class="code code--root">visudo</code>
			Находим строку, которая начинается с <em>%sudo</em> и заменяем ее на:
			<code class="code">%sudo ALL=(ALL) NOPASSWD: ALL</code>
		</p>
		<p>
			Открываем файл групповой политки любым редактором. Если вы не знакомы с vim, то лучше будет использовать интуитивный nano:
			<code class="code code--root">nano /etc/group</code>
			Напротив строчки sudo должен быть ваш логин. Добавьте его, если это не так.
		</p>
		<p>
			Теперь можно залогиниться под новым пользователем.
			На время настройки, перейдем в режим администратора:
			<code class="code code--user">sudo -s</code>

			Чтобы настроить имя хоста, используйте файлы
			<code class="code">/etc/hosts
/etc/hostname</code>

			А также команду <em>hostname</em>.
			В большинстве случаев, можете оставить все по умолчанию.
		</p>

		<h1>Установка окружения</h1>
		<p>
			Следующим шагом обновим информацию о пакетах и установим кое-что:
			<code class="code code--root">apt-get update &amp;&amp; apt-get install language-pack-ru language-pack-ru-base aptitude build-essential zlib1g-dev zsh git-core vim screen ctags curl zip unzip</code>
			Таким образом будут установлены:
			<ul>
				<li><b>language-pack-ru language-pack-ru-base</b> — поддержка русского языка</li>
				<li><b>aptitude</b> — удобный менеджер пакетов</li>
				<li><b>build-essential</b> — инструменты для сборки пакетов</li>
				<li><b>zsh</b> — удобная командная оболочка </li>
				<li><b>git-core</b> — система контроля версия git</li>
				<li><b>vim</b> — расширенный текстовый редактор</li>
				<li><b>screen</b> — работа с несколькими сессиями в рамках одной</li>
				<li><b>ctags</b> — утилита-плагин для vim, собирающая информацию об именах и позициях переменных, функций и процедур, встречающихся в исходном коде </li>
				<li><b>curl</b> — удобная сетевая утилита</li>
				<li><b>zip/unzip</b> — архвитор zip</li>
			</ul>
		</p>
		<p>
			Каждый из этих пакетов я рекомендую установить. Даже, если не планируете их использовать первое время или не знаете, зачем они нужны. Дальнейшие шаги этого руководства будут предполагать наличие большинства из них
		</p>
		<p>
			Если сервер находится не в вашем часовом поясе, то удобнее будет настроить на нем ваше локальное время. Делается это командой:
			<code class="code code--root">dpkg-reconfigure tzdata</code>

		<p>
			Далее мы будем настраивать рабочее окружение пользователя, поэтому нужно выйти из режима администратора командой <em>exit</em>.
		</p>
		<p>
			Перейдем в домашний каталог и установим пакет конфигураций <em>homedir</em>:
			<code class="code code--user">cd ~ &amp;&amp; wget https://notset.ru/workenv.zip &amp;&amp; unzip workenv.zip &amp;&amp; ./workenv/install</code>
		</p>
		<p>
			Сменим командную оболочку на <em>/bin/zsh</em> командой

			<code class="code code--user">chsh</code>
		</p>
		<p>
			Теперь необходимо перелогиниться, для применения изменений. Если zsh предложит провести предварительную настройку. Проще всего будет нажать q для выхода.
			Удаляем уже не нужные архивы:
			<code class="code code--user">rm -r ~/workenv*</code>

			Если все установлено верно, некоторые элементы строки станут цветными.
		</p>
	</article>

	<footer class="footer">
		<span class="footer__copy">2017 &ndash;&nbsp;<a class="footer__link" href="https://lukin.me/ask/#/notset.ru">Anton Lukin</a></span>
	</footer>

</section>


</body>
</html>
