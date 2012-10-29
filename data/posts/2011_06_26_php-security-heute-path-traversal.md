---
date: 2011-06-26 17:21:13
title: PHP-Security! Heute: path traversal
tags: Webtechnik
slug: php-security-heute-path-traversal

last_updated: 2012-01-07 21:35:52
---

![PHP Security](images/2011/php-security.jpg)

Path traversal bzw. directory traversal ist eine Methode, um aus vorgesehenen Verzeichnissen auszubrechen. In Bezug auf PHP findet diese Sicherheitslücke Anwendung, da Dateien oftmals anhand des Querystrings eingebunden werden. Beispiel:

    http://example.org/index.php?site=impressum.php
~~~
    #!php@1
    <?php
        include './includes/sites/' . $_GET['site'];
    ?>
~~~
Das ist natürlich fatal. Mittels `../` in $_GET['site'] kann man das vorgegebene Verzeichnis verlassen und je nach Rechten auch sicherheitsrelevante Dateien ausgeben lassen. Denn: wird eine nicht-PHP-Datei mittels include eingebunden wird deren Inhalt 1:1 an den Browser gesendet.

Oftmals wird das Ausbrechen aus einem Verzeichnis dadurch versucht zu verhindern, indem '../' aus $_GET['site'] entfernt wird.

    #!php
    $save = str_replace('../', '', $_GET['site']);

Auch ganz schlecht: Aus einem `....//` würde `../`, da str_replace bereits ersetzte Teile nicht nochmals ersetzt. Außerdem kommen durch URL-Encoding und unterschiedliche Directory-Separator (Linux/Windows) noch weitere Zeichen in Frage, die es erlauben aus einem Verzeichnis auszubrechen.

Sehr verbreitet ist auch die Dateiendung vorzugeben:

    #!php@0
    include './includes/sites/' . $_GET['site'] . '.php';

Je nach Betriebssystem ist hier mit einem NULL-Byte (Url-Encoded: %00) die vorzeitige Terminierung des Strings möglich:

    http://example.org/index.php?site=../../../../../../../etc/passwd%00

Hier muss man übrigens nicht die exakte Anzahl der nach oben zu gehenden Verzeichnisse wissen - ist man im Root-Verzeichnis angelangt, können beliebige `../` ohne Auswirkungen folgen.

Mit aktiviertem allow_url_fopen gibt es noch weitere Zeichen zu prüfen - das ist dann aber kein path traversal mehr und somit nicht bestandteil dieses Blog-Beitrages.

Was tun?
--------
Statt str_replace sollte man besser preg_replace mit einem Pattern wie `#\\.+[/\\]+#` verwenden. Noch besser ist es natürlich von vorne herein eine Whitelist zu führen - also ein Array mit erlaubten Dateinamen und dann prüfen, ob $_GET['site'] in diesem Array vorkommt. Eine weitere Methode ist, den kanonischen, absoluten Pfad mittels realpath() zu erzeugen und dann den Beginn dieses Pfades mit dem Document-Root (bzw. dem erlaubten Verzeichnis) abzugleichen.