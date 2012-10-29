---
date: 2010-08-25 22:14:10
title: Webserver-Umgebung erkennen mit PHP
tags: Webtechnik
slug: webserver-umgebung-erkennen-mit-php

last_updated: 2012-01-07 21:35:53
---

<strong>Development</strong> oder <strong>Production</strong> - das ist hier die Frage. Bei der Webseiten-Entwicklung bietet es sich selbst bei kleinsten Projekten an, zunächst in einer lokalen Entwicklungsumgebung die Anwendung zu testen. Ist der Test erfolgreich werden die Änderungen online gestellt.

Oftmals unterscheidet sich die Produktivumgebung im Internet von der lokalen. So müssen z.B. unterschiedliche MySQL-Konfigurationen  oder Pfade berücksichtigt werden.

Doch wie kann man zwischen den verschiedenen Umgebungen in einem PHP-Skript automatisch unterscheiden? Schließlich wollen wir ja nicht vor jedem Upload die Konfiguration händisch anpassen.

Das Auslesen des Server-Namens per <strong>$_SERVER['HTTP_HOST']</strong> scheint die einfachste Möglichkeit. Doch da DNS-Namen und IP-Adressen, speziell in Entwicklungsumgebungen, vergänglich sind, ist das nicht immer die sicherste Methode seinen Entwicklungsserver zu identifizieren.

Besser ist es, in der lokalen Webserver-Konfiguration eine Umgebungsvariable zu setzen und diese dann per PHP abzufragen. Im Falle eines Apache-Webservers mit <a href="http://httpd.apache.org/docs/2.2/mod/mod_env.html">mod_env</a> wäre das <a href="http://httpd.apache.org/docs/2.2/mod/mod_env.html#setenv">setEnv</a> in der conf (httpd.conf), in einer VHOST-Direktive oder per .htaccess. Beispiel .htaccess:
<pre>SetEnv DEVELOP true</pre>
Auslesen mit PHP:

    #!php@1 
    if (getenv('DEVELOP')) {
        // DEVELOP
    } else {
        // Production
    }

Unter manchen Konfigurationen sind die Umgebungsvariablen nur in <strong>$_SERVER</strong> verfügbar und nicht per <strong>getenv</strong> (IIS z.B.)