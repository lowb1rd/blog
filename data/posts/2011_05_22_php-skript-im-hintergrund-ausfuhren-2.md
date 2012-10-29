---
date: 2011-05-22 12:23:53
title: PHP-Skript im Hintergrund ausführen #2
tags: Webtechnik
slug: php-skript-im-hintergrund-ausfuhren-2

last_updated: 2012-01-07 21:35:52
---

Die [bereits erwähnte Methode](2010/12/27/php-skript-im-hintergrund-ausfuhren.html) über Content-Length funktioniert zwar etwas hakelig, ist aber in Webspace-Umgebungen oft die einzige Methode ein PHP-Skript im Hintergrund auszuführen.

Eine andere Methode (neben dem Cronjob) besteht darin, über <a href="http://de.php.net/manual/de/function.exec.php">exec</a> einen Hintergrundprozess zu starten. Beispiel (Linux only):
<pre>&lt;?php exec('/usr/bin/php -f /var/www/cron.php &gt; /dev/null &amp;') ?&gt;</pre>

Mit -f wird die auszuführende Datei angegeben. In Shared-Hosting-Umgebungen wird man keinen Zugriff auf die php-binary haben. Wget hingegen sollte verfügbar oder zumindest installierbar sein. Mit wget kann man prima "Cronjobs" über HTTP-GET im Hintergrund anstoßen:
<pre>&lt;?php exec('wget -bq -o /dev/null -O /dev/null -t 1 http://www.example.org/cron.php') ?&gt;</pre>

Wget's Parameter sind case-sensitive. -b sorgt dafür, dass der wget-Prozess im Hintergrund ausgeführt wird, -q unterdrückt jegliche Ausgabe, -o leitet die heruntergeladene Datei nach /dev/null, -O schreibt das Logfile ebenfalls ins Nirvana und -t 1 unternimmt nur einen Versuch die folgende URL aufzurufen.