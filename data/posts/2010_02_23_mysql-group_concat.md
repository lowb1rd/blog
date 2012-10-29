---
date: 2010-02-23 21:31:48
title: MySQL's GROUP_CONCAT()
tags: Webtechnik
slug: mysql-group_concat

last_updated: 2012-01-07 21:35:53
---

Die <a href="http://dev.mysql.com/doc/refman/5.0/en/group-by-functions.html#function_group-concat">GROUP_CONCAT</a>-Funktion von MySQL ist eine tolle Erweiterung zu GROUP BY.  Insbesondere wenn man es mit one-to-many oder many-to-many Beziehungen zu tun hat. GROUP_CONCAT() ist seit der Version 4.1 Bestandteil von MySQL.

<strong>Beispiel</strong>: Personen haben Hobbys. Die Personen werden in der <strong>Personen-Tabelle</strong> mit dem Namen (<em>name</em>) und einer eindeutigen ID (<em>person_id</em>) gespeichert. Die zugehörigen Hobbys stehen in der <strong>Hobbys-Tabelle</strong>. Die Hobbys haben ebenfalls eine fortlaufende ID (<em>hobby_id</em>). Der Name des Hobbys steht in der Spalte <em>hobby</em>, die Verknüpfung zur Person wird über die <em>person_id</em>-Spalte hersgestellt.
<pre>mysql&gt; SELECT * FROM <strong>personen</strong>;
+-----------+-------+
| <strong>person_id</strong> | <strong>name</strong>  |
+-----------+-------+
|         1 | Peter |
|         2 | Paul  |
+-----------+-------+

mysql&gt; SELECT * FROM <strong>hobbys</strong>;
+----------+-----------+-----------+
| <strong>hobby_id</strong> | <strong>person_id</strong> | <strong>hobby</strong>     |
+----------+-----------+-----------+
|        1 |         1 | Schwimmen |
|        2 |         1 | Reiten    |
|        3 |         2 | Radfahren |
+----------+-----------+-----------+</pre>
Da eine Person mehrere Hobbys haben, und das selbe Hobby von mehreren Personen ausgeübt werden kann, wäre hier eine many-to-many Beziehung mit extra Join-Tabelle sinnvoller. Der Einfachheit halber belassen wir es aber bei zwei Tabellen. Die Funktionsweise von GROUP_CONCAT() ist in beiden Fällen sowieso die selbe.

Peter hat also die Hobbys "<em>Schwimmen und Reiten</em>", Paul hat das Hobby <em>Radfahren</em>. Um jetzt mit PHP eine einfache Tabelle auszugeben, in der jeder Name mit den jeweiligen Hobbys aufgelistet wird, könnte man zunächst alle Namen holen, und dann pro Reihe die jeweiligen Hobbys in einem separatem Query. Das würde aber bedeuten, dass wir für <em>n</em> Datensätze <em>n+1</em> Queries brauchen. Viel zu viel!

Packt man das alles in ein einziges  Query, und joint die Hobbys-Tabelle, taucht im Result der Peter leider doppelt auf. Auch doof...

Abhilfe schafft die <em>GROUP_CONCAT</em>()-Funktion in Verbindung mit einem <em>GROUP BY</em>. GROUP_CONCAT() konkateniert (=verbindet) <strong>alle Werte einer Gruppe</strong> zu einem String. Null-Werte werden ignoriert. Standardmäßig wird das ganze Komma-getrennt.
<pre>SELECT
    p.name
    , GROUP_CONCAT(h.hobby SEPARATOR ', ') AS hobbys
FROM
    personen p
LEFT JOIN
    hobbys h ON h.person_id = p.person_id
GROUP BY
    p.person_id</pre>
Mit der Angabe von <em>SEPARATOR</em> innerhalb der <em>GROUP_CONCAT</em>-Funktion trennen wir die einzelnen Hobbys zusätzlich mit einer Leertaste. Ergebnis:
<pre>+-------+-------------------+
| <strong>name</strong>  | <strong>hobbys</strong>            |
+-------+-------------------+
| Peter | Schwimmen, Reiten |
| Paul  | Radfahren         |
+-------+-------------------+<strong>
</strong></pre>
Perfekt! Für die besonders aufmerksamen Leser gibt's [hier](files/2010/group_concat.sql_.html) nochmals das komplette Beispiel als SQL-File zum auswendig lernen ;)