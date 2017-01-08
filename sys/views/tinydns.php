<div class="header">
	<h1>Tinydns и dyndns своими руками</h1>
	<h2></h2>
</div>

<div class="content">
	<h2 class="content-subhead"></h2>
	<p>
		<code>sudo apt-get install daemontools daemontools-run ucspi-tcp djbdns</code>
		<code>sudo adduser --no-create-home --disabled-login --shell /bin/false dnslog</code>
		<code>sudo adduser --no-create-home --disabled-login --shell /bin/false tinydns</code>

		<code>sudo tinydns-conf tinydns tinydns /etc/tinydns/ EXTERNAL.IP.ADDRESS</code>

		<code>sudo ln -s /etc/tinydns /etc/service/tinydns</code>

	</p>
	
	<p>
		Запуск:
		<code>sudo initctl start svscan</code>
	
		Проверка:
		<code>sudo svstat /etc/tinydns</code>

 		Остановка:
		<code>sudo svc -d /etc/tinydns</code> 
	</p>
</div>
