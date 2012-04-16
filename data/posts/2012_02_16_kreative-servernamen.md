---
title: Kreative Servernamen
tags: Allgemein, Linux
date: 2012-02-16 8:00
slug: kreative-servernamen
excerpt: Bei der Vergabe von Servernamen wird ja oft nach einem bestimmten Schema vorgegangen: Hauptstädte, Sternzeichen, Charakter aus Filmen oder Serien. Wie unkreativ!
featured: true

last_updated: 2012-01-07 16:15:50
---

Bei der Vergabe von Servernamen wird ja oft nach einem bestimmten Schema vorgegangen: Hauptstädte, Sternzeichen, Charakter aus Filmen oder Serien.

Wie laaangweilig. Bei <a href="http://reddit.com">reddit</a> [Link wird nachgereicht sobald ich den Beitrag wieder finde] habe ich von einem wirklich interessanten Schema erfahren: Servernamen werden nach den Elementen im Periodensystem der selbigen benannt.

Server #1 bekommt also den Namen "<strong>hydrogen</strong>" (Wasserstoff, 1 Proton), Server #2 dem Namen "<strong>helium</strong>" (2 Protonen) usw. Soweit schon mal nicht schlecht.

Jetzt muss noch das letzte Byte der IP-Adresse mit der Protonenanzahl übereinstimmen. Server #1 hätte also die IP-Adresse 192.168.0.1, Server #2 die 192.168.0.2 usw. Somit lässt sich von dem Servernamen direkt auf die IP-Adresse schließen (vorausgesetzt man hat das Periodensystem der Elemente so grob im Kopf :)).

Zudem kann man auch Aliasnamen im DNS anlegen, so dass das jeweilige Kürzel des Elements in die IP auflöst:

    $ ping h
        PING h (192.168.0.1) 56(84) bytes of data.
    64 bytes from hydrogen.lokal (192.168.0.1): icmp_seq=1 ttl=64 time=0.023 ms

Wohoo! Leider nur in der Theorie wirklich nett. In der Wirklichkeit macht die Benennung nach Verwendung des Servers mehr Sinn (server-terminal, webserver, nasbox1, usw.). Die Beziehung zwischen Servername und IP-Adresse macht auch keinen Sinn. Zum einen haben die wenigsten das Periodensystem im Kopf, zum anderen wurde genau für diesen Zweck DNS erfunden: um Servernamen und IP-Adressen unabhängig voneinander zu machen. Der aufmerksame, chemie-affine Leser wird zudem die Begrenzung auf 118 unterschiedliche Servernamen bemängeln.
