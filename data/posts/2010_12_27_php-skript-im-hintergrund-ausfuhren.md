---
date: 2010-12-27 21:40:52
title: PHP-Skript im Hintergrund ausführen
tags: Webtechnik
slug: php-skript-im-hintergrund-ausfuhren

last_updated: 2012-01-07 21:35:53
---

![Achtung: Paint-Skills](images/2010/content-length1.png)

Wenn gleichzeitig mit einem Seitenaufruf eine rechen- oder zeitintensive Aufgabe ausgeführt werden soll, hat das den Nachteil, dass der Benutzer im Browser so lange einen Ladebalken sieht, bis die gesamte Rechenoperation beendet ist. Auch wenn die Berechnung an das Skriptende mit vorherigem <a href="http://php.net/manual/de/function.ob-flush.php">ob_flush()</a> gesetzt wird, bleibt der Ladebalken im Browser sichtbar. Das sieht nicht nur unschön aus - auch JavaScript-Events wie onload oder <a href="http://api.jquery.com/ready/">domready</a> werden verzögert gefeuert.

Idealerweise startet man rechenintensive Aufgaben nicht zusammen mit einem Webseitenaufruf, sondern per <a href="http://de.wikipedia.org/wiki/Cron">cron</a> direkt über das <a href="http://php.net/manual/de/features.commandline.php">PHP CLI</a> ohne Webserver-Overhead. Cron steht aber nicht immer zur Verfügung. Außerdem macht es die Anwendung weniger portabel.

Die Tatsache, dass jeder Browser die Verbindung beendet, sobald er alle mittels HTTP-Header "<a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html">content-length</a>" angekündigten Bytes empfangen hat, lässt sich ausnutzen. Den Rest regelt PHPs Ausgabepuffer.

Beispiel:
    #!php@1
    <?php
    ignore_user_abort(true);
    ob_start();
     
    // Webseiten-Content
    echo '<html>...</html>;';
     
    header('HTTP/1.1 200 OK');
    header('Content-Length: ' . ob_get_length());
     
    ob_end_flush();
    flush();
     
    // Background-Prozess ab hier
    sleep(10);
    ?>

Ein <em>ignore_user_abort(true)</em> verhindert den Scriptabbruch durch den Benutzer <strong>während der Ladezeit</strong>. Nach dem flush() kann das Skript in keinem Fall mehr abgebrochen werden (abgesehen von Runtime-Fehlern, time- oder memory_limit). Sämtlicher Output vom Background-Prozess landet im Nirvana.

Wird das obige Skript aufgerufen, erscheint nur während der Ladezeit des ge-flush-ten Buffers ein Ladebalken ("Webseiten-Content"). Der Background-Prozess (beispielhaftes Sleep(10)) findet statt, nachdem die Verbindung zum Browser bereits beendet wurde.

Die Implementierung ist je nach Browser/Webserver-Umgebung etwas hakelig und bedarf in jedem Einzelfall der genauen Überprüfung. Der Beispielcode oben hat in meinen Tests gut funktioniert.

Wider Erwarten funktioniert das auch ohne die explizite Angabe von <em>header('Connection: close')</em>. Im IE6 führt die Angabe sogar dazu, dass der Hintergrundprozess überhaupt nicht mehr funktioniert. Wenn der IE darüber hinaus immer noch Zicken macht, so hilft es vielleicht mindestens 256 Byte zu flushen. Code:

<pre><span style="color: #333333;">echo '&lt;html&gt;...&lt;/html&gt;';</span>
 
if (($diff = 256 - ob_get_length()) &gt; 0) echo str_repeat(' ', $diff);
 
<span style="color: #808080;"><span style="color: #333333;">header('HTTP/1.1 200 OK');
header('Content-Length: ' . ob_get_length());</span>
</span></pre>

Ade, 's war schee!