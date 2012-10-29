---
date: 2011-03-16 21:11:11
title: Hash-Bangs (#!) in URLs
tags: Webtechnik
slug: hash-bangs-in-urls-2

last_updated: 2012-01-07 21:35:52
---

![Raute Ausrufezeichen](images/2011/Shebang.png) Hash-Bangs in URLs sind stark in Mode gekommen. Facebook, Twitter, DeviantART und viele weitere ganz oder teilweise auf Javascript/Ajax basierende Seiten benutzen es.

Alles was nach einer Raute in der URL kommt, ist der sog. <em>named anchor</em>. Ursprünglich dazu gedacht um zu einem <strong>&lt;a&gt;nchor</strong> mit diesem Namen, oder Element mit dieser ID zu scrollen/springen. Das ist praktisch, wenn man auf eine gewisse Stelle einer langen HTML-Seite verlinken will. Bei der Wikipedia ist z.B. das Inhaltsverzeichnis immer auch ein Anker-Link zur jeweiligen Sektion. Außerdem erzeugt der Sprung sinnigerweise einen History-Back-Eintrag.

Der Hash-Bang (oder Shebang) in der URL ist eine <a href="http://code.google.com/intl/de-DE/web/ajaxcrawling/">Google-Erfindung</a> (oder besser: Notlösung) um Ajax-Content zu Crawlen. Diese Notlösung scheint sich aber seit der Definition durch Google erstaunlich schnell auszubreiten. Die Tatsache, dass andere Suchmaschinen/Crawler/Caches/Bots diese URLs nicht unterscheiden, da sie den <em>anchor-part</em> grundsätzlich ignorieren, scheint der Sache keinen Abbruch zu tun. Zudem gibt es bei dieser-URL-Struktur keine <a href="http://de.wikipedia.org/wiki/Graceful_degradation">graceful degradation</a>, da der <em>anchor-part</em> Serverseitig gar nicht ankommt. Wenn das Javascript also fehlerfaft ist, gibt es gar nichts mehr zu sehen. Das hat Gawker zuletzt schmerzlich erfahren müssen.

Was ist dann aber der Vorteil einer solchen URL-Struktur:
<pre>http://lifehacker.com/#!5622470/eight-clever-ways-to-take-adva..</pre>
anstelle von:
<pre>http://lifehacker.com/5622470/eight-clever-ways-to-take-adva..</pre>
Ich sehe keinen! Bis auf die Tatsache, dass beim Laden der zweiten Seite etwas weniger Traffic ensteht - was aber in keinem Verhältnis zu den Nachteilen steht.
<h2>Wie funktioniert's eigentlich?</h2>
Serverseitig kommt der Anchor nicht an. Mit Javascript ist er Clientseitig aber sehr wohl auslesbar (via document.location.hash). Mit einem setInterval muss  der hash permanent auf Änderungen überprüft werden - echt häßlich! Im Falle einer Änderung wird der Hash geparst und ein XMLHTTP-Request gesendet. Das Result dieses Requests ersetzt dann Teile des Contents.

Das hat den nervigen Effekt, dass z.B. beim Aufruf dieser URL

<a href="http://browse.deviantart.com/#/d1muyik">http://browse.deviantart.com/#/d1muyik</a>

zunächst die DevianArt-Startseite geladen wird und erst dann das JS eingreift und das gewünschte Bild anzeigt. NO-GO.
<h2>Die Rettung: HTML5 History API?</h2>
Die <a href="http://www.whatwg.org/specs/web-apps/current-work/multipage/history.html">History-API</a> gab es schon in früheren HTML-Versionen - allerdings nur lesend z.B. via history.back(). Ab HTML5 ist auch ein schreibender Zugriff auf die History möglich. Man kann also per Javascript neue History-Einträge erzeugen und so den <strong>Inhalt der Adressbar</strong> ändern, ohne eine neue Seite aufzurufen. Yay! Betätigt der Benutzer anschließend den Zurück-Button wir ein ensprechendes Event gefeuert. Das würde das Erzeugen von History-Einträgen via Anker-Sprünge und damit die Hash-Bangs in URLs ein für alle mal abschaffen. Content könnte teilweise per AJAX-Request nachgeladen werden und die neu entstehende Seite hätte trotzdem Ihre eindeutige URL und wäre auch direkt über diese aufzurufen.

Lediglich Firefox ab Version 4 und Safari ab Version 5 unterstützen schreibende Zugriffe auf die History-API im Moment. Kein IE9 und kein Opera 11. Prinzipiell kann aber bei Browsern, die die History-API nicht unterstzützen auf herkömmliche URLs ohne das Nachladen via AJAX zurückgefallen werden.