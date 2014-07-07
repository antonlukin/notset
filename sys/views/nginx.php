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

 	<h2 class="content-subhead">Общая настройка nginx</h2> 
	<p>В <em>nginx.conf</em> советую добавить новый формат лога:</p>
<code class="no">log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
   '$status $body_bytes_sent "$http_referer" '
   '"$http_user_agent" "$http_x_forwarded_for"';
</code>

	Стандартный конфиг для wordpress:
<code class="no">server {
	listen  80;

	server_name  www.example.ru;
	rewrite ^ http://example.ru$request_uri? permanent;
}

server {
	listen   80;

	server_name example.ru;
	root /srv/http/example.ru;
	index index.php;

	charset utf-8;
	access_log  /var/log/http/example_nginx_access.log  main;
	error_log   /var/log/http/example_nginx_error.log  warn;

	location / {
			try_files $uri $uri/ /index.php?$args;
	}

	location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
			expires 2w;
			log_not_found off;
	}

	location = /favicon.ico {
			log_not_found off;
			access_log off;
	}

	location = /robots.txt {
			allow all;
			log_not_found off;
			access_log off;
	}

	location ~* /(?:uploads|files)/.*\.php$ {
			deny all;
	}

    location ~ /\.ht {
        deny  all;
    }

    location ~ \.php$ {
        include fastcgi_params;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Request-Filename $request_filename;

        fastcgi_param SCRIPT_FILENAME /srv/http/example.ru$fastcgi_script_name;
		fastcgi_param QUERY_STRING    $query_string;

		fastcgi_split_path_info ^(.+\.php)(/.+)$;

		proxy_read_timeout 512;
		fastcgi_pass unix:/var/run/fpm-common.sock;
    }
}
</code>
	
</div>
