---
date: 2012-01-08 22:39:56
title: Froxlor Bug: maximale Länge von ServerAlias (Apache)
tags: Webtechnik
slug: froxlor-bug-maximale-lange-von-serveralias-apache

last_updated: 2012-01-07 21:35:52
---

SysCP bzw. dessen Nachfolger [Froxlor](http://www.froxlor.org/) kümmert sich hier auf diesem Server um das Erstellen diverser Konfigurationsdateien sowie um die Verwaltung der virtuellen User für E-Mail- und FTP-Zugänge.

Schon vor einiger Zeit habe ich festgestellt, dass bei Verwendung vieler Alias-Domains die erzeugte Apache-Konfiguration fehlerhaft sein kann. Dann nämlich, wenn die ServerAlias-Direktive mehr als 8000 Zeichen hat. Scheinbar hat noch keiner zuvor so viele Alias-Domains einer Hauptdomain hinzugefügt. 8000 Zeichen hört sich auch viel an, doch wenn pro Domain jeweils noch die www-Subdomain hinzukommt, reichen bereits ca. 150 Domains um dieses Limit zu erreichen.

Der Apache vHost-Container unterstützt mehrere ServerAlias-Direktiven, so dass bevor die 8000 Zeichen erreicht werden einfach ein neuer ServerAlias-Eintrag erzeugt werden sollte. Die Datei `cron_tasks.inc.http.10.apache.php` in `scripts/jobs` ist für das Erstellen der Apache-Konfigurationsdateien verantwortlich und mit wenigen Zeilen auf dieses Verhalten anpassbar ([Patch][1]).

Da ich nicht nach jedem Update daran denken möchte, diese Änderung einzuspielen, habe ich bei Froxlor mal ein [Ticket erstellt][2] und diesen Patch angehängt. Vielleicht schafft er es ja in die 0.9.27 :)
 
[1]: files/2012/01/ServerAlias.patch
[2]: http://redmine.froxlor.org/issues/1012