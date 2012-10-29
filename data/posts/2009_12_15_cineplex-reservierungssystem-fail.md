---
date: 2009-12-15 22:58:40
title: Cineplex Reservierungssystem fail
tags: Allgemein
slug: cineplex-reservierungssystem-fail

last_updated: 2012-01-07 21:35:26
---

Beim <a href="http://www.cineplex.de/kino/home/">Cineplex</a> Neckarsulm kann man unter <a href="http://212.20.182.131/">http://212.20.182.131/</a> online Karten (Sitzplätze) reservieren. Nach kostenloser Registrierung kann man ebenfalls kostenlos <strong>bis zu vier</strong> Sitzplätze reservieren. Das ist natürlich viel zu wenig. Glücklicherweise nimmt es das System mit der Überprüfung der Formulardaten nicht so genau. Stichwort: Input Validation. Das ist die Überprüfung aller Nutzer-Daten auf Plausibilität hin: Ist die E-Mail-Adresse korrekt, ist die Postleitzahl 5-stellig oder in diesem Fall: liegt die Anzahl der zu reservierenden Sitze zwischen 1 und 4 ?

Formulare lassen sich auf Clientseite leicht manipulieren. <a href="https://addons.mozilla.org/de/firefox/addon/1843">Firebug</a> bietet sich hier geradezu an. Firebug ist ein Firefox-Plugin, mit dem man unter anderem das DOM der Webseite einsehen und bearbeiten kann. Um die Anzahl der zu reservierenden Sitze <em>leicht</em> zu erhöhen, einfach mit dem Inspector auf das SELECT-Element klicken, und einen der Option-Werte anpassen. Opera kann das übrigens von  Hause aus: Darstellung -&gt; Quelltext, &lt;option&gt; manipulieren, klick oben auf "Änderungen anwenden".

[![Cineplex1](images/2009/cineplex1-300x257.jpg)](images/2009/cineplex1.jpg)

Normalerweise sollten die Daten nach dem Absenden des manipulierten Formulars auf der Serverseite überprüft werden. Das ist hier aber offenbar nicht der Fall.

[![Cineplex2](images/2009/cineplex2-300x241.jpg)](images/2009/cineplex2.jpg)

Schon besser.

Das Beispiel zeigt, dass eine Webanwendung grundsätzlich jedem User-Input misstrauen sollte und diese Daten niemals ungeprüft übernehmen sollte. Das betrifft nicht nur Formular-Daten sondern auch Cookie-Daten, GET/POST und sonstige, vom Nutzer übermittelte Daten.
Aber wer wird es dem armen ASP-<span style="text-decoration: line-through;">Frickler</span> <em>Entwickler</em> schon verübeln ..