---
date: 2011-12-18 08:22:49
title: PHP CLI Fortschrittsbalken
tags: Webtechnik
slug: php-cli-fortschrittsbalken

last_updated: 2012-01-07 21:35:52
---

Hin und wieder führt man PHP-Skripte direkt auf der Kommandozeile (CLI) aus. Das hat den Vorteil, dass man nicht den Umweg über den Webserver gehen muss, wenn man ihn garnicht braucht. Außerdem lässt sich das Skript leicht mit STRG+C abbrechen, den Webserver hingegen müsste man bei einer Endlosschleife neu starten.

Der aktuelle Fortschritt lässt sich auch ganz einfach mit echo ausgeben - kein (ob_)flush notwendig.

Wenn man mit echo einen Carriage Return (`"\r"`) ausgibt, wird der Cursor auf den Anfang der Zeile zurückgestellt und man kann eine bereits ausgegebene Zeile überschreiben. Perfekt also für einen Fortschrittsbalken!

    #!php@1
    $total = 10;
    $bar_length = 20;
    $spinner = '-\\|/';
    for ($i = 1; $i <= $total; $i++) {
        usleep(1000000);
        
        $spin = $spinner[$i%strlen($spinner)];    
        $cur = sprintf('%'.strlen($total).'.d', $i);
        $percent = $i/$total*100;
        
        $progress_len = floor($bar_length * $percent / 100);
        $progress = str_repeat('=', $progress_len);
        if ($progress_len < $bar_length) {
            $progress .= '>';
            $progress .= str_repeat('-', $bar_length-$progress_len-1);
        }
        
        echo " $cur/$total $spin [$progress] $percent%\r";
    }

![PHP-Progressbar](images/2011/php-cli-progress.gif)