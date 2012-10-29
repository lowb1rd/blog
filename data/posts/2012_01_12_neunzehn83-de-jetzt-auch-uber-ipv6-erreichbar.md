---
date: 2012-01-12 23:05:07
title: neunzehn83.de jetzt auch über IPv6 erreichbar
tags: Allgemein
slug: neunzehn83-de-jetzt-auch-uber-ipv6-erreichbar

last_updated: 2012-01-07 21:35:52
---

Dank IPv6 zu Hause über den Tunnelbroker [Sixxs](//www.sixxs.net) konnte ich nun endlich meinen vServer fit für "das neue Internet" machen. Natives IPv6 ist wohl in absehbarer Zeit nicht zu erwarten, da von meinem ISP, der Kabel Baden-Württemberg GmbH, noch kein Termin zur Einführung von IPv6 genannt wurde.

![v6ping](images/2012/v6ping.gif)

Eigentlich ganz einfach
-----------------------

Alles was wir brauchen ist:

- eine IPv6-Adresse
- einen Nameserver, der per IPv6 erreichbar ist
- DNS AAAA-Records
- Apache für den Dualstack-Betrieb konfigurieren

### Eine IPv6-Adresse
Der [vServer ALUMINIUM](//www.netcup.de/bestellen/produkt.php?produkt=88) von [Netcup](//www.netcup.de), auf dem neunzehn83.de läuft, ist IPv6 fähig. Per OpenVCP-Panel kann man bis zu 15 einzelne IPv6-Adressen aktivieren. Nach einem Reboot stehen diese zur Verfügung.

    ~$ sudo ifconfig
    eth0      Link encap:Ethernet  HWaddr 00:24:21:b4:xy:ab
          inet addr:188.40.201.74  Bcast:188.40.201.127  Mask:255.255.255.192
          inet6 addr: 2a01:4f8:100:5462:0:bc28:c94a:1/64 Scope:Global
          inet6 addr: 2a01:4f8:100:5462:0:bc28:c94a:2/64 Scope:Global

### Nameserver
Für IPv4 schreibt die DeNIC mindestens zwei Nameserver in unterschiedlichen /24 Netzen vor. Bei IPv6 genügt im Moment noch ein einziger. [Wie bereits erwähnt](2009/12/11/die-technik-hinter-diesem-blog-hosting-fur-schwaben.html), verwende ich die Nameserver von [Regworld](//www.regworld.com), meinem Domain-Registrar. Zusätzlich läuft auf dem vServer noch ein Bind Nameserver als zweiter Secondary. Dieser lauscht auch gleichzeitig an der IPv6-Adresse und wird somit als einziger Nameserver für IPv6-Anfragen genutzt.

Die Nameserver von Regworld kommen dafür leider nicht in Frage, da diese (noch) nicht per IPv6 erreichbar sind. Das lässt sich aber relativ leicht lösen, indem man einen Bind-Nameserver an eine oder mehrere IPv6 adressen binded und als reinen Forwarder für die Regworld-Nameserver via IPv4 konfiguriert.

In meinem Fall gebe ich mich aber mal mangels RAM mit nur einem Nameserver für IPv6 zufrieden.

Zu guter Letzt muss noch ein IPv6 GLUE-Record für den Nameserver angelegt werden. Das geht im Domainrobot von Regworld (AutoDNS) genau so wie bei IPv4: Man schreibt den IPv6-Glue einfach per Leerzeichen vom v4-Glue getrennt dahinter:
    
    ns.example.org 1.1.1.1 ::1

Beim DeNIC-Whois sieht das dann so aus:

![ipv6_nameserver_denic](images/2012/ipv6_nameserver_denic.gif)

### DNS AAAA-Records
Was der A-Record bei v4 ist der AAAA (Quad-A) Record bei v6. Diesen legen wir jetzt also für die Domain (neunzehn83.de) und die www-Subdomain an. Zusätzlich habe ich eine neue Subdomain [ipv6.neunzehn83.de](//ipv6.neunzehn83.de) erstellt, die nur ein AAAA aber kein A Record hat, somit also exklusiv über IPv6 erreichbar ist.

    @    IN A    188.40.201.74    
    @    IN AAAA 2a01:4f8:100:5462::bc28:c94a:1
    www  IN A    188.40.201.74
    www  IN AAAA 2a01:4f8:100:5462::bc28:c94a:1
    ipv6 IN AAAA 2a01:4f8:100:5462::bc28:c94a:1

IPv6 hat übrigens Priorität vor IPv4, weshalb alle Besucher die sowohl mit IPv4 als auch mit IPv6 unterwegs sind, diese Webseite bereits über IPv6 betrachten.

### Apache Dualstack
Dual-Stack nennt man das Verfahren, bei dem der Apache sowohl per IPv4 also auch per IPv6 erreichbar ist. Alles was dafür nötig ist, ist den Apachen auch auf der IPv6-Adresse lauschen zu lassen und die Virtualhost-Konfiguration um die IPv6-Adresse zu erweitern.

    Listen 188.40.201.74:80
    Listen [2a01:4f8:100:5462::bc28:c94a:1]:80
    
    <Virtualhost 188.40.201.74:80 [2a01:4f8:100:5462::bc28:c94a:1]:80>
    [...]
    </Virtualhost>

Nach einem Apache-Restart ist alles klar. 

IPv6 und Froxlor
----------------

Dummerweise schreibe ich für diesen Server die Apache-Configs nicht selbst, sondern lass das [Froxlor](//www.froxlor.org/) machen. Froxlor ist der Nachfolger von SysCP, unterstützt aber ebenso noch kein Dualstack, weshalb ich selbst an der Source Hand angelegt habe. Alle Änderungen finden sich in [diesem Patch](files/2012/IPv6.patch), welchen ich auch in einem [Thread bei Froxlor](//forum.froxlor.org/index.php?/topic/1415-ipv6-dualstack-apache-bind/) zur Diskussion veröffentlicht habe. In der Datenbank muss die Varchar-Länge des `ip`-Feldes der `panel_ipsandports`-Tabelle auf 55 vergrößert werden.
    
    ALTER TABLE `panel_ipsandports` CHANGE `ip` `ip` VARCHAR( 55 ) NOT NULL DEFAULT ''

Nun können unter `IPs und Ports` Dualstack-IPs angeben werden, indem die IPv4 einfach durch ein Leerzeichen von der IPv6-Adresse getrennt wird:

![froxlor_ipv6](images/2012/froxlor_ipv6.gif)

Achtung: der Patch berücksichtigt nur die Apache und Bind Konfiguration. NGIX und Lighttpd sind unverändert und somit wahrscheinlich nicht funktionsfähig.

IPv6 und PHP
------------
Wenn die eigene Webseite auch per IPv6 erreichbar ist, kann das auch Auswirkungen auf PHP-Scripte haben. `$_SERVER['REMOTE_ADDR']` beinhaltet dann nämlich die IPv6-Adresse als String und die ist in den meisten Fällen deutlich länger als eine IPv4-Adresse. Das kann ein Problem werden, wenn die IP-Adresse in einer Datenbank als ein `VARCHAR(15)` gespeichert wird. Hier benötigt man nun ein `VARCHAR(40)`. Auch [ip2long()](//php.net/manual/de/function.ip2long.php) funktioniert nicht mit IPv6-Adressen.