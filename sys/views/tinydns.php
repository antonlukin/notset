<div class="header">
	<h1>Tinydns и dyndns своими руками</h1>
	<h2></h2>
</div>

<div class="content">
	<h2 class="content-subhead"></h2>
	<p>
<!--
sudo apt-get install daemontools daemontools-run ucspi-tcp djbdns
sudo adduser --no-create-home --disabled-login --shell /bin/false dnslog
sudo adduser --no-create-home --disabled-login --shell /bin/false tinydns

sudo tinydns-conf tinydns tinydns /etc/tinydns/ EXTERNAL.IP.ADDRESS
sudo tinydns-conf tinydns tinydns /etc/tinydns/ 178.79.180.253

sudo ln -s /etc/tinydns /etc/service/tinydns

commands:
	sudo initctl start svscan

	sudo svstat /etc/tinydns			check
	sudo svc -d /etc/tinydns 			stop
	sudo svc -u /etc/tinydns 			start
            -->
	</p>
</div>
