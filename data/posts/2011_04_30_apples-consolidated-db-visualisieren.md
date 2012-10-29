---
date: 2011-04-30 00:13:28
title: Apples consolidated.db visualisieren
tags: Allgemein
slug: apples-consolidated-db-visualisieren

last_updated: 2012-01-07 21:35:52
---

![consolidated.db](images/2011/consolidated.db.png) 

Es wurde wohl schon genug über das Speichern von Bewegungsdaten auf Apples iPhone geschrieben. Nur eins noch: Mit Hilfe <a href="http://markolson.github.com/js-sqlite-map-thing/">dieser Webseite</a> kann man die consolidated.db (die böse Datei, in der all die Bewegungsdaten gespeichert sind) parsen, und übersichtlich in einer Google-Map darstellen. Das ganze passiert zu 100% Clientseitig mit Javascript. Ausgewählt werden kann direkt der iTunes-Backup-Ordner, oder besser, nur die consolidated.db via Drag&amp;Drop. Bei meinen Tests hat das ausschließlich mit Google-Chrome funktioniert.

Besitzer eines Jailbroken iPhones finden die besagte Datei via scp in
<pre><em>/private/var/root/Library/Cache/locationd/consolidated.db</em></pre>

Übrigens: die <a href="http://www.android.com/">Smartphone-Plattform</a> der <a href="http://www.google.de">Datenkrake Goole</a> scheint Apple da in nichts nachzustehen - zumindest beim Datensammeln. Deshalb rate ich allen <a href="http://www.pocketpc.ch/c/930-android-sammelt-geo-daten.html">ihre Häme besser zurückzuhalten</a> ;)