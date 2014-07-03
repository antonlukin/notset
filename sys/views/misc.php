<div class="header">
	<h1>Полезные команды</h1>
	<h2>Мелочи, которые не хочется запоминать, но использовать приходится</h2>
</div>

<div class="content">
	<h2 class="content-subhead">Работа с файловой системой и пользователями</h2>
	<p>
		Рекурсивно изменить права  на файлы внутри папки:
		<code>find . -type f -exec sudo chmod 664 {} \;</code>
		То же самое для папок:
 		<code>find . -type d -exec sudo chmod 775 {} \;</code> 
	</p>

	<p>
		Удалить файлы старше 7 дней
		<code>find /home/database -type f -mtime +7 -print0 | xargs -0 rm -f</code>
	</p>
	
	<p>
		Создать внутрисистемного пользователя:
		<code>sudo adduser --no-create-home --disabled-login --shell /bin/false user</code>
	</p>
	
	<h2 class="content-subhead">MySQL</h2>
	<p>
		Команды для создания нового пользователя и базы данных:
		<code class="mysql">grant all privileges on test.* to test@localhost identified by "password";</code>
 		<code class="mysql">create database test character set 'utf8'</code> 
	</p>
	<p>
		Резервное копирование всех баз с архивированием:
		<code>mysqldump -u root --all-databases --events --ignore-table=mysql.event | gzip --rsyncable > /tmp/mysql.sql.gz</code>
	</p>
	</p>
</div>
