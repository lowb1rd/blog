---
date: 1970-01-01 01:00:00
title: Port abhängig von der Source-IP für verschiedene Dienste verwenden
tags: Linux
slug: 

last_updated: 2012-01-07 20:21:20
---

Mit xinetd kann man relativ leicht Ports umleiten. Auch kann man die Weiterleitung mit der "only_from"-Option auf bestimmte Source-IPs beschränken.

Möchte man aber anhand der Source-IP auf unterschiedliche Ports weiterleiten, kommt man um iptables nicht herum.

Folgende iptables-Regeln leiten den von außen erreichbaren Port 123 auf den Port 993 (imaps) weiter. Verbindungen von der IP x.x.x.x werden auf Ports 22 (ssh) weitergeleitet.

<pre>
iptables -A INPUT -p tcp --dport **123** -j ACCEPT 
iptables -A PREROUTING -t nat -p tcp -s **x.x.x.x** --dport **123** -j REDIRECT --to-port **22**
iptables -A PREROUTING -t nat -p tcp --dport **123** -j REDIRECT --to-port **993**
</pre>

Ja, man könnte natürlich einfach zwei Ports im Router weiterleiten. Hat man aber hinter dem NAT gerade nur einen Port zur Verfügung, bietet sich die Lösung über iptables an.