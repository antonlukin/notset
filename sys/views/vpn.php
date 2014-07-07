<div class="header">
	<h1>Настройка VPN для собственных нужд</h1>
	<h2>Установка и настройка pptp</h2>
</div>

<div class="content">
	<h2 class="content-subhead">Установка и настройка</h2>
	<p>
		Начнем с установки необходимых пакетов:
		<code>sudo apt-get install ppp pptpd ipx</code>
	</p>
	
	<p>
		Отредактируем файл опций сервиса:
		<code>sudo vim /etc/ppp/pptpd-options</code>

		Удалим все, что есть и добавим:
<code class="no">auth
name pptpd
refuse-pap
refuse-chap
refuse-mschap
require-mschap-v2
require-mppe-128
ms-dns 8.8.8.8
ms-dns 8.8.4.4
proxyarp
nodefaultroute
lock
nobsdcomp</code>

	</p>
	<p>
		На очереди файл конфигурации:

		<code>sudo vim /etc/pptpd.conf</code>

		Добавим в конец файла диапазоны адресов, которые будут присавиваться подключаемым клиентам:
<code class="no">localip 10.0.1.1
remoteip 10.0.2.10-99</code>
	</p>

	<p>Разрешим пересылку пакетов между внутренними сетями:
		<code>sudo vim /etc/sysctl.conf</code>

		Нужно найти и раскомментировать строку:
		<code class="no">net.ipv4.ip_forward=1</code>
	</p>

	<p>
		Создадим файл <em>/etc/vpn-firewall.sh</em>:
<code class="no">#!/bin/sh
echo 1 &gt; /proc/sys/net/ipv4/ip_forward
echo 1 &gt; /proc/sys/net/ipv4/ip_dynaddr
iptables --flush
iptables --delete-chain
iptables --table nat --flush
iptables --table nat --delete-chain
iptables -P FORWARD ACCEPT
iptables --table nat --append POSTROUTING --out-interface eth0 -j MASQUERADE
echo vpn firewall loaded OK.
</code>
		Далее нужно сделать его исполняемым и добавить в <em>rc.local</em>.
	</p>

	<p>
		Добавим пользователей для подключения:

		<code>sudo vim /etc/ppp/chap-secrets</code>

		Формат файла:
		<code class="no">master pptpd   "password"  "*"</code>
	</p>

	
</div>
