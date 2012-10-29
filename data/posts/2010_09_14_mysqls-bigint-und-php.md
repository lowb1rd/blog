---
date: 2010-09-14 19:00:27
title: MySQLs BIGINT  .. und PHP
tags: Webtechnik
slug: mysqls-bigint-und-php

last_updated: 2012-01-07 21:35:53
---

<strong>Übrigens</strong>: Ein <a href="http://dev.mysql.com/doc/refman/5.1/de/numeric-type-overview.html">MySQL BIGINT</a> ist 8 Byte lang. Unsigned lassen sich also Ganzzahlen von <em>0</em> bis <em>18446744073709551615</em> speichern. Eine MySQL-BIGINT-Spalte mit <a href="http://dev.mysql.com/doc/refman/5.1/de/example-auto-increment.html">auto_increment</a> läuft bei 1000 Inserts pro Sekunde erst nach über <strong>500 Milliarden Jahren</strong> über. An dieser Stelle darf der Vergleich mit dem Alter des Universums natürlich nicht fehlen: lächerliche <a href="http://de.wikipedia.org/wiki/Universum#Alter_und_Zusammensetzung">13,75 Milliarden Jahre</a>!

Die Länge eines PHP-Integers ist dagegen von der Plattform abhängig.  Der maximale Wert ist in der Konstanten <strong>PHP_INT_MAX</strong> hinterlegt. PHP-Integers sind immer signed.
<pre>echo PHP_INT_MAX; // "2147483647" auf einem 32bit System/32bit PHP
echo PHP_INT_MAX+1; // "214748364<strong>8</strong>" .. Nanu?</pre>
Wird INT_MAX überschritten gibt PHP immer ein float zurück
<pre>echo typeof(PHP_INT_MAX+1); // double
echo (int)(PHP_INT_MAX+1); // "-2147483648" Aha!</pre>
Wie wir sehen, ist ein PHP-Float eigentlich ein <strong>DOUBLE</strong>, also doppelte Genauigkeit (<em>double precision</em>) mit 8 Byte Länge.

52 der 64 bits werden für die <a href="http://de.wikipedia.org/wiki/Gleitkommazahl#Mantisse">Mantisse</a> verwendet. Ganzzahlen können also bis 2^53-1 <strong>exakt</strong> abgespeichert werden. Darüber hinaus wird es ungenau:
<pre><code>ini_set('precision', 17);

// 2^53
echo 9007199254740991; // "9007199254740991"
// 2^54
echo 18014398509481983; // "1801439850948198<strong>4</strong>"
</code></pre>

Zugegeben, das Ganze ist etwas sehr theoretisch. Für das Rechnen mit wirklich große Zahlen verwendet man besser <a href="http://php.net/manual/en/book.bc.php">BCMath</a>. Ein MySQL BIGINT ist auf 64bit Systemen mit 64bit PHP kein Problem. Auf 32bit Systemen wird's ab 2^53 haarig. Das sind dann aber immerhin noch ca. 285 Mio. Jahre bei 1000 Inserts/Sekunde ;)<strong>
</strong>
