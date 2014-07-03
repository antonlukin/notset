<div class="header">
	<h1>Установка и настройка Nginx</h1>
	<h2>Настройка и простенькая оптимизация отличного веб-сервера nginx</h2>
</div>

<div class="content">
	<h2 class="content-subhead">Установка</h2>
	<p>
		Установка последней стабильной версии. Добавим репозиторий nginx:
		<code>sudo apt-get install python-software-properties software-properties-common</code>
		<code>sudo add-apt-repository ppa:nginx/stable</code>
		и установим веб-сервер:
		<code>sudo apt-get update && sudo apt-get install nginx</code>
	</p>
	
	<h2 class="content-subhead">Настройка SSL</h2>
	<p>
<code class="no">server {
	listen   443 ssl;
	server_name example.org;        
	include /etc/nginx/ssl/example.conf;
</code>
Листинг конфига <em>example.conf</em>:
<code class="no">ssl on;
ssl_protocols SSLv3 TLSv1 TLSv1.1 TLSv1.2;

ssl_session_cache    shared:SSL:10m;
ssl_session_timeout  10m;

ssl_certificate     /etc/nginx/ssl/example-unified.pem;
ssl_certificate_key /etc/nginx/ssl/example.key;
</code>
	</p>
	
</div>
