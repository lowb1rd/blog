---
date: 2010-08-15 18:13:42
title: GET-Requests minimieren. Heute: Externe CSS-Dateien
tags: Webtechnik
slug: get-requests-minimieren-heute-externe-css-dateien

last_updated: 2012-01-07 21:35:53
---

Selbst bei kleineren Webprojekten hat man oft mehrere CSS-Dateien. Zumindest das <a href="http://meyerweb.com/eric/tools/css/reset/">CSS-Reset</a> ist bei mir, zwecks Wiederverwendbarkeit, immer in einer separaten Datei. Eine weitere Unterteilung in typo.css, lists.css usw. macht dann bei größeren Projekten Sinn. Zudem kommen von externen Paketen oft weitere, separate CSS-Dateien hinzu (Lightbox, TinyMCE, ..).

So sieht das dann im HTML &lt;head&gt; aus:
<pre>&lt;link href="css/reset.css" rel="stylesheet" type="text/css" media="screen" /&gt;
&lt;link href="css/style.css" rel="stylesheet" type="text/css" media="screen" /&gt;
[...]</pre>

Doch hier benötigt der Browser für jede CSS-Datei einen extra HTTP GET-Request! Das geht auch schöner: ein PHP-Skript kann alle Dateien zusammenfassen und ausgeben. Damit wir die Endung *.css* beibehalten können, schreiben wir mittels <a href="http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html">mod_rewrite</a> die URI zu unser CSS-Datei um:

    .htaccess:
    RewriteEngine On
    RewriteRule css/init.css css/init.php

<pre><strong>Im &lt;head&gt;:</strong> 
&lt;link href="css/init.css" rel="stylesheet" type="text/css" media="screen" /&gt;</pre>

In unserer  Haupt-CSS-Datei (init.php) machen wir dem Browser klar, dass diese Datei CSS ausgibt. Gleichzeitig buffern wir den Output mittels <a href="http://de2.php.net/manual/en/function.ob-start.php">ob_start()</a>. Durch die Angabe von "*ob_gzhandler*" wird der Output gzip-komprimiert ausgegeben, sofern der Browser das Unterstützt. (je nach HTTP "accept" header - Keine Sorge, PHP kümmert sich von ganz allein darum).

    #!php
    //init.php:
    header("Content-Type: text/css");
    ob_start("ob_gzhandler");

Danach lesen wir bestimmte CSS-Dateien in einem Verzeichnis ein, und speichern den jeweiligen Inhalt in einer Variablen:

    #!php
    $css = '';
    foreach (explode(',', 'reset,typo,style') as $file) {
        $css .= "/* File: $file.css */\n";
        if ($_SERVER['HTTP_HOST'] == '*webserver*') {
            $css .= '@import url("'.$file.'.css");';
        } else {
            $css .= file_get_contents($file . '.css');
        }
        $css .= "\n\n";
    }

In diesem Fall speichern wir die Dateien "*reset.css*", "*typo.css*" und "*style.css*" in **$css**. Wenn das Ganze lokal ausgeführt wird,  geben wir nur "**@import url(..)**" und nicht den eigentlichen Dateiinhalt aus. Das hat den Vorteil, dass z.B. in <a href="https://addons.mozilla.org/de/firefox/addon/1843/">Firebug</a> noch immer die richtigen Dateinamen und Zeilen angezeigt werden. "*webserver*" wäre der DNS-Name des lokalen Entwicklungsservers.

Der Cache
---------
Da die CSS-Datei dynamisch generiert wird, trifft hier kein "*If-Modified-Since"-Cache, wie bei Bilddateien oder statischen CSS-Dateien.

Wir müssen uns also selbst um das Cachen unserer dynamisch generierte CSS-Datei auf Clientseite kümmern. Cache mittels [ETag](//de.wikipedia.org/wiki/HTTP_ETag) bietet sich hier an. Dazu hashen wir den Dateiinhalt aller Dateien ($css) mit MD5. Diesen Hash übergeben wir dann als ETag an den Client. Ändert sich eine CSS-Datei, so ändert sich auch der MD5-Hash bzw. ETag.

Hat der Client einmal einen ETag empfangen, so sendet er diesen mit jedem weiteren Request zu dieser Datei im HTTP-Header mit. Auf Serverseite können wir also in unserem PHP-Script überprüfen ob der gesendete ETag noch aktuell ist. Wenn das der Fall ist, setzen wir einen *304 Not Modified* Header und brechen ab.

    #!php
    $etag = md5($css);
    header('ETag: '.$etag);
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &amp;&amp;
     trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
        header("HTTP/1.1 304 Not Modified");
        die();
    }
    echo $css

Die komplette **init.php** kann [hier](files/2010/init.php_.html) nochmals angeguckt werden (mit *gehighlightetem* Syntax, yay!) .

Wir überprüfen:
---------------
(Screenshots stammen aus FireBug)
###1. Request
![Etag1](images/2010/etag1.png)


Im Antwort-Header taucht der ETag auf. Da im Anfrage-Header kein ETag gesendet wurde, trifft hier kein Cache, und somit Status *200 OK*.
###2. Request
![Etag1](images/2010/etag2.png)

Unter **IF_NONE_MATCH** wird der ETag an den Server gesendet. Dieser antwortet dann mit *304 Not Modified*.