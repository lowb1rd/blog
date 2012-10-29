---
date: 2010-01-23 13:56:09
title: PHP: gleich oder nicht gleich?
tags: Webtechnik
slug: php-gleich-oder-nicht-gleich

last_updated: 2012-01-07 21:35:53
---

PHP ist <a href="http://de.wikipedia.org/wiki/Schwache_Typisierung">schwach typisiert</a>. Und das ist im Prinzip auch gut so! Dennoch passieren dadurch teilweise wirklich sonderbare Dinge:
<pre style="font-size: 22px;"><strong>"2d3" </strong>!=<strong> "02d3"</strong></pre>

Das ist einfach: <em>Strings</em> unterschiedlicher Länge können niemals gleich sein.

<strong>Aber:</strong><pre style="font-size: 22px;"><strong>"2e3" </strong>==<strong> "02e3"</strong></pre>

Was läuft hier anders? Beim einfachen Vergleich ("==") versucht PHP zuerst jeden String als Zahl zu interpretieren. Das gelingt im ersten Beispiel offensichtlich nicht, wohl aber im zweiten: "2e3" wird als <a href="http://de.wikipedia.org/wiki/Wissenschaftliche_Notation">wissenschaftliche Notation</a> (2 mal zehn hoch 3)  interpretiert und somit zur Zahl. Da bei Zahlen führende Nullen irrelevant sind, wird auch "02e3" zur Zahl. Man könnte also genauso gut "2e3" == 2000 schreiben.

Leider ist das mit den führenden Nullen nicht immer so:
<pre>"0012" != 0012</pre>

Hier kommt eine weitere Eigenschaft von PHP ins Spiel: Zahlen, die mit einer Null beginnen werden von PHP als Oktalzahl interpretiert. <strong>Nicht aber in Strings!</strong> Somit wird <span style="text-decoration: underline;">"0012"</span> zu 12(dec) und <span style="text-decoration: underline;">0012</span> zu 12(oct).

Da die internen Konvertierungen und Interpretationen von PHP nicht immer offensichtlich sind, empfiehlt es sich, in gewissen Situationen den "==="-Operator (<a href="http://php.net/manual/de/language.operators.comparison.php">strikter Vergleich</a>) zu verwenden. Dieser ergibt nur dann True, wenn auch die Variablentypen (int, string, ..) auf beiden Seiten übereinstimmen. Beim "==="-Operator findet niemals eine Konvertierung statt.
<pre>"2e3" !== "02e3"</pre>

Das macht Sinn. Aber auch hier gibt es Überraschungen:
<pre>2e3 !== 1000</pre>

Hmm..
<pre>echo gettype(2e3); // double</pre>

Aha!  Zahlen mit Exponentialdarstellung sind immer vom Typ double. Liegt wohl daran, dass man damit auch sehr kleine Zahlen darstellen kann ($angstrom = 1e-10;). Also gilt:
<pre>2e3 === 1000.0</pre>

Dieses Spiel ließe sich beliebig fortsetzen. Dennoch: die gezeigten Verhaltensweisen sind so gewollt und auch <a href="http://php.net/manual/de/language.types.type-juggling.php">Dokumentiert</a>. Diese Extremfälle zeigen lediglich, wie gefährlich (weil schwer nachvollziehbar/erkennbar) manchmal ein scheinbar einfacher Vergleich in PHP sein kann.