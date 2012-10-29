---
date: 2011-12-22 08:58:43
title: PHP und Y2K38: Werden wir alle sterben?
tags: Webtechnik
slug: php-und-y2k38-werden-wir-alle-sterben

last_updated: 2012-01-07 21:35:52
---

Der 19. Januar 2038 3:14:07 Uhr ist ein besonderer Zeitpunkt. Dann sind nämlich genau 2147483647 Sekunden seit dem 1. Januar 1970 vergangen. PHPs `date()`-Funktion arbeitet genau auf dieser Grundlage - auch bekannt als der UNIX-Timestamp.

Der Unix-Timestamp ist in PHP sehr populär. Funktionen wie `date()` oder `strtotime()` arbeiten mit UNIX Timestamps. Das ist auf den ersten Blick auch unheimlich praktisch, weil sich damit relativ platzsparend Daten (*Plural von Datum*) speichern lassen. Außerdem lässt es sich mit einem Timestamp leicht rechnen.

Ein PHP signed Integer auf 32bit-Systemen kann Werte zwischen -2147483648 und 2147483647 annehmen. Am 19. Januar 2038 3:14:08 läuft der Int also über und es ist plötzlich der 13. Dezember 1901 20:45:52 Uhr (Freitag der 13.!).

Folgendes Beispiel zeigt das Verhalten auf 32bit-Systemen. Mit einem 64bit-System und 64bit PHP kann PHPs unsinged Int Werte bis 9223372036854775807 annehmen. Dort stellt sich die Y2K38-Frage also garnicht. Hier stellt sich dann die **Jahr 292471210689-Frage** - aber bis dahin sind 64bit Systeme genau so ausgestorben, wie es 2038 die 32bit-Systeme sein werden.

    #!php@0
    var_export(strtotime("20 jan 2038")); // false :(