---
date: 2010-02-01 18:40:50
title: Policy based routing auf Debian Lenny/Etch mit Squid Proxyserver
tags: Webtechnik
slug: policy-based-routing-auf-debian-lennyetch-mit-squid-proxyserver

last_updated: 2012-01-07 21:35:53
---

Folgendes Szenario: Ein Debian Lenny Server und zwei Router mit je einer Internetverbindung befinden sich im selben (lokalen) Netzwerk. Wir wollen beide Router (sprich Internetverbindungen) für verschiedene Anwendungen nutzen.
<h2>Routing-Tabelle</h2>
Routing im privaten Netzwerk ist eigentlich ganz einfach: Liegt die Ziel-IP nicht im selben Subnetz, wird das Paket an den Default-Gateway geschickt. Das nennt sich dann static routing. Verwaltet wird das über sog. Routing-Tabellen. Unter Debian sieht das ungefähr so aus:
<pre><em>~$ sudo route</em>
Kernel-IP-Routentabelle
Ziel            Router          Genmask         Flags Metric Ref    Use Iface
<strong>192.168.123.0</strong>   *                255.255.255.0   U     0      0        0 eth0
default         <strong>192.168.123.99</strong>   0.0.0.0         UG    0      0        0 eth0</pre>

<em>192.168.123.0</em> ist das eigene, private Netz, <em>192.168.123.99</em> der Router an dem eine DSL oder Kabel-Verbindung hängt. Das sieht bei jedem Client-PC übrigens ähnlich aus. Windows-Benutzer müssen aber "route print" ins cmd tippen.
<h2>Policy Based Routing</h2>
So weit, so einfach. Was ist aber, wenn wir bestimmte Dienste über einen anderen Gateway leiten wollen? Dann brauchen wir <a href="http://www.linupedia.org/opensuse/Policy_Based_Routing">policy based routing</a>. Damit lassen sich unheimlich tolle Sachen machen - wir beschränken uns aber erstmal auf das unterschiedliche Routen anhand der Source-IP-Adresse.

<strong>Konkret:</strong> Der Lenny-Server bekommt eine zweite (virtuelle) IP, den Squid-Proxyserver binden wir exklusiv auf diese IP, und per PBR schicken wir alle Pakete die vom Squid-Proxy kommen über einen <em>bestimmten</em> Gateway. Der Rest geht nach wie vor zum Default-Gateway.

Mit PBR könnte man natürlich auch nach dem Desination-Port routen, und sich somit die zweite IP-Adresse sparen. Leider bietet  "ip rule" keine Möglichkeit direkt den TCP destination port als Kriterium auszuwählen. Deshalb müssten die entsprechenden Pakete erst mit iptables markiert, und dann nach dieser Markierung gefiltert werden. Was diesen Post betrifft, blieben wir also bei zwei IPs und somit dem routing anhand der Source-IP.
<h3>Ein paar Zahlen zum Verständnis:</h3>
- Lenny-Server: 192.168.123.1 &amp; 192.168.123.2
- Router1: 192.168.123.99
- Router2: 192.168.123.98
<h2>Die Software</h2>
Als Software brauchen wir <strong>iproute2</strong> und <strong>iptables</strong>. Beide Tools sollten selbst bei einer minimalen Debian Installation schon vorhanden sein. Wenn nicht, installieren wir die Tools nach: <em>apt-get install iproute iptables</em>.
<h2>Die Konfiguration</h2>
<h3>Routing-Tabelle anlegen</h3>
Zunächst legen wir zwei neue Routing-Tabellen an. Eine für jede ausgehende Leitung. Dazu fügen wir folgenden Zeilen an das Ende von <strong>/etc/iproute2/rt_tables</strong> hinzu.
<pre>#
# reserved values
#
255     local
254     main
253     default
0       unspec
#
# local
#
#1      inr.ruhep
<strong>100 arcor
200 kabelbw</strong></pre>

Der Name ist jeweils frei wählbar. Ich habe hier die Namen der jeweiligen Provider genommen. Die ID ist auch frei wählbar, muss aber zwischen 1 und 254 liegen.
<h3>Routing-Konfiguration</h3>
Wir bearbeiten <strong>/etc/network/interfaces</strong>, fügen ein zweite, virtuelle IP-Adresse hinzu, und teilen Debian mit, mit welchem Adapter welche Routing-Tabellen genutzt werden sollen.
<pre># The loopback network interface
auto lo
iface lo inet loopback
# The primary network interface
#allow-hotplug eth0
auto eth0
iface eth0 inet static
        address 192.168.123.1
        netmask 255.255.255.0
        network 192.168.123.0
        broadcast 192.168.123.255
        post-up ip route add default via 192.168.123<strong>.99</strong> dev eth0 table main
        pre-down ip route del default via 192.168.123<strong>.99</strong> dev eth0 table main
<strong>auto eth0:1</strong>
iface eth0:1 inet static
        <strong>address 192.168.123.2</strong>
        netmask 255.255.255.0
        network 192.168.123.0
        broadcast 192.168.123.255
        post-up ip route add default via 192.168.123<strong>.98</strong> dev eth0:1 table arcor
        post-up ip rule add from 192.168.123.2 table arcor
        pre-down ip route del default via 192.168.123<strong>.98</strong> dev eth0:1 table arcor
        pre-down ip rule del from 192.168.123.2 table arcor</pre>

Die <strong>post-up</strong> &amp; <strong>pre-down</strong> Einträge legen automatisch die entsprechenden Regeln in der Routing-Tabelle an, wenn die Netzwerkkarte aktiviert bzw. deaktiviert wird. Von Hand angelegte Einträge verschwinden sonst nämlich nach einem Neustart. Der Traffic über <strong>eth0</strong> (192.168.123.1) geht demnach an den Router mit der <strong>.99</strong>. Traffic über das virtuelle Interface <strong>eth0:1</strong> (192.168.123.2) geht an den Router mit der <strong>.98</strong>.
<h3>Squid-Konfiguration</h3>
Da nun sämtlicher Traffic der IP 192.168.123.2 über einen speziellen Gateway geht, müssen wir nur noch den Squid-Proxy explizit auf diese IP binden. Dazu öffnen wir die Datei <strong>/etc/squid/squid.conf</strong> und passen die <strong>http_port-</strong>Zeile an:
<pre>http_port 192.168.99.2:3128</pre>

Der Port ist natürlich frei wählbar - wichtig ist hier nur die IP-Adresse. Nach einem Squid-Neustart sollte jetzt sämtlicher Proxy-Traffic über die 192.168.123.98 laufen - der restliche Traffic nach wie vor über die 192.168.123.99.
<h2>Sinn?</h2>
Gute Frage! PHP-Skripte können zum Beispiel über <a href="http://sourceforge.net/projects/php-proxy/">PHP-Proxy</a> die zwei unterschiedlichen Leitungen benutzen. Selbiges gilt natürlich auch für Clients, die in Ihrem Browser die virtuelle IP des Squid als Proxy eintragen. Somit kann man mit wenigen Klicks mal eben die IP wechseln...