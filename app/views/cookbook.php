<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Modern and convenient lookup service">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>notset services</title>

	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
	<link href="https://lukin.nyc3.digitaloceanspaces.com/notset/favicon.png" rel="icon" type="image/png">
	<link rel="stylesheet" href="/assets/styles.min.css" type="text/css" media="all" />

	<meta name="theme-color" content="#222222">
</head>
<body>

<section class="wrap">
    <div id="main">
<div class="header">
	<h1>Настройка Ubuntu Server</h1>
	<h2>Всё самое необходимое сразу после установки Ubuntu Server</h2>
</div>

<div class="content">
	<h2 class="content-subhead">Создание пользователя</h2>
	<p>
		На данном этапе предполагается, что у вас установлен чистый дистрибутив Ubuntu Server старше 11.04. Все команды ниже гарантировано работают в версии 12.04.
	</p>

	<p>
		Для начала необходимо добавить пользователя, от имени которого вы будете работать в дальнейшем на сервере. Делается это командой:
		<code class="root">adduser login</code>
		Здесь login ваше имя пользователя. На всех своих серверах я использую логин master.
	</p>
	<p>
		Я предпочитаю сразу же убрать необходимость ввода пароля при каждом запуске команды через sudo.
		Однако не советую этого делать всем и везде, так как это несомненно ударит по безопасности:
		<code class="root">visudo</code>
		Находим строку, которая начинается с <em>%sudo</em> и заменяем ее на:
		<code class="no">%sudo ALL=(ALL) NOPASSWD: ALL</code>
	</p>
	<p>
		Открываем файл групповой политки любым редактором. Если вы не знакомы с vim, то лучше будет использовать интуитивный nano:
		<code class="root">nano /etc/group</code>
		Напротив строчки sudo должен быть ваш логин. Добавьте его, если это не так.
	</p>
	<p>
		Теперь можно залогиниться под новым пользователем.
		На время настройки, перейдем в режим администратора:
		<code>sudo -s</code>


		Чтобы настоить имя хоста, используйте файлы
<code class="no">/etc/hosts
/etc/hostname
</code>

		А также команду <em>hostname</em>.
		В большинстве случаев, можете оставить все по умолчанию.
	</p>


	<h2 class="content-subhead">Настройка инфраструктуры</h2>
	<p>
		Следующим шагом обновим информацию о пакетах и установим кое-что:
		<code class="root">apt-get update && apt-get install language-pack-ru language-pack-ru-base aptitude build-essential zlib1g-dev zsh git-core vim screen ctags curl zip unzip</code>
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
		<code class="root">dpkg-reconfigure tzdata</code>

	<p>
		Далее мы будем настраивать рабочее окружение пользователя, поэтому нужно выйти из режима администратора командой <em>exit</em>.
	</p>
	<p>
		Перейдем в домашний каталог и установим пакет конфигураций <em>homedir</em>:
<code>cd ~
wget http://workenv.notset.ru/workenv.zip
unzip workenv.zip
cd workenv
./install
</code>
	</p>
	<p>
		Сменим командную оболочку на <em>/bin/zsh</em> командой
		<code>chsh</code>
	</p>
	<p>
		Теперь необходимо перелогиниться, для применения изменений. Если zsh предложит провести предварительную настройку. Проще всего будет нажать q для выхода.
		Удаляем уже не нужные архивы:
		<code>rm -r ~/workenv*</code>

		Если все установлено верно, некоторые элементы строки станут цветными.
	</p>
</div>
    </div>
</section>


</body>
</html>
