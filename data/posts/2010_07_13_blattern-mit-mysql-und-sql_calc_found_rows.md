---
date: 2010-07-13 19:00:57
title: Blättern mit MySQL und SQL_CALC_FOUND_ROWS
tags: Webtechnik
slug: blattern-mit-mysql-und-sql_calc_found_rows

last_updated: 2012-01-07 21:35:53
---

Verwendet man in einem MySQL-Query die Option <em><a href="http://dev.mysql.com/doc/refman/4.1/en/information-functions.html#function_found-rows">SQL_CALC_FOUND_ROWS</a></em> im Zusammenspiel mit <em>LIMIT</em>, berechnet MySQL die Gesamtzahl der Datensätze unabhängig vom verwendeten Limit.

Beispiel: Will man Daten seitenweise darstellen (-&gt; <a href="http://en.wikipedia.org/wiki/Pagination_(web)">Pagination</a>), so braucht man z.B. für eine Blätternavigation die Gesamtzahl der vorhandenen Seiten bzw. Zeilen/Datensätze. Die Option <em>SQL_CALC_FOUND_ROWS</em> erspart das doppelte Ausführen des selben Queries, um an die Gesamtzahl zu kommen.
<h2>Schlecht</h2>
<pre>SELECT
    a, b, c
FROM
    table
WHERE  
    /\* wahnsinnig kompliziertes Zeug \*/
LIMIT
    0, 10
 
#--
 
SELECT
    COUNT(\*)
FROM
    table
WHERE
    /\* wahnsinnig kompliziertes Zeug \*/</pre>

<h2>Besser:</h2>
<pre>SELECT <strong>SQL_CALC_FOUND_ROWS</strong>
    a, b, c
FROM
    table
WHERE
    /\* wahnsinnig kompliziertes Zeug \*/
LIMIT
    0, 10</pre>

Besonders bei komplexeren Queries schont das den Datenbankserver. Der MySQL-Query-Cache trifft nämlich nicht, wenn das LIMIT (oder sonst irgendwas) verändert wird.

Das Query mit <em>SQL_CALC_FOUND_ROWS</em> und <em>LIMIT</em> gibt wie gewohnt eine eingeschränkte Ergebnismenge zurück. In diesem Beispiel also die ersten 10 Datensätze. Um an die Gesamtzahl der Datensätze zu kommen muss ein zweites Query ausgeführt werden:
<pre>SELECT FOUND_ROWS()</pre>

Unterm Strich sind das dann trotzdem zwei Queries - aber <em>FOUND_ROWS()</em> gibt lediglich die bereits im ersten Query berechnete Gesamtzahl zurück, zählt also nicht wirklich als komplexes Query.

Eine Sache noch: Unter bestimmten Umständen, z.B. dann wenn nicht sortiert wird, ist das Query mit LIMIT und <strong>ohne</strong> <em>SQL_CALC_FOUND_ROWS</em> schneller, da das Query beendet ist, sobald das LIMIT erreicht ist. Das ändert aber nichts an der Tatsache, dass unterm Strich die Variante mit <em>SQL_CALC_FOUND_ROWS</em> schneller ist.