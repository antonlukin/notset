<div class="header">
	<h1>Правильный pureftp</h1>
	<h2></h2>
</div>

<div class="content">
	<h2 class="content-subhead"></h2>
	<p>     <!--
Настройка сервера pure-ftpd, с заведением логинов-паролей стандартными средствами pure-ftpd.
1. Устанавливаем pure-ftpd
2. Создаем пользователя, который будет владельцем файлов в директории ftp для SuSe, Gentoo, Ubuntu Server
useradd -K UID_MIN=1100 ftpfile -s /usr/sbin/nologin
для Debian Sarge
adduser --firstuid 1100 --shell /usr/sbin/nologin
-1- ftpfile - имя пользователя

Добавить пользователя и установить для него пароль:
# pure-pw useradd YourUser -u ftpusers -g ftpusers -d /home/YourDirFTP -c Ivanov -y 4
Доступ к хостингу с полным доступом
# pure-pw useradd YourUser -u ftpusers -g www-data -d /var/www/YourDomen.ua -c "Ivanov Ivan" -y 4
# chown -R ftpusers:www-data
Для применения изменений нужно обновить файл pureftpd.pdb командой. Чтобы избежать использования 'pure-pw mkdb' после каждого изменения данных, используйте опцию '-m' в командах модификации
# pure-pw mkdb
Просмотр данных пользователя
pure-pw show YourLogin
Изменить пароль
pure-pw passwd YourLogin
Механизмом Virtual Users, который есть в Pure FTPD, представляет из себя следующее - в системе заводится системный пользователь, который ассоциируется с виртуальным пользователем. Можно ассоциировать несколько виртуальных пользователей с реальным аккаунтом. Для виртуальных пользователей назначается свой каталог, можно назначить ему так же квоты и пр. Управление виртуальными пользователями осуществляется с помощью программы pure-pw, параметры пользователей хранятся в /usr/local/etc/pureftpd.pdb. У нас имеется пользователь portal , создадим для него виртуального пользователя.

#pure-pw useradd portal -u portal -g portal -d /home/portal/        -->
	</p>
</div>
