---
date: 2010-01-26 22:44:04
title: Kurz notiert: einfache CSS Browser-Hacks für IE6/7
tags: Webtechnik
slug: kurz-notiert-einfache-css-browser-hacks-fur-ie67

last_updated: 2012-01-07 21:35:53
---

Mit nur <strong>einem Zeichen</strong> lassen sich CSS-Eigenschaften gezielt für den Internet Explorer 6 und 7 schreiben:
<pre>#meinElement {
    background: red;    /* alle Browser */
    <strong>*</strong>background: green; /* IE 7 und darunter */
    <strong>_</strong>background: blue;  /* nur IE6 */
}</pre>

<a href="http://neunzehn83.de/blog/wp-content/uploads/2010/01/css-browser-hack.jpg">So sieht das dann aus</a>. Doch sind solche Anpassungen für bestimmte Browser in sparaten CSS-Dateien und per <a href="http://de.wikipedia.org/wiki/Conditional_Comments">Conditional-Comment</a> eingebunden weitaus besser aufgehoben. Aber zum schnellen Debuggen für zwischendurch kann man diese Hacks schonmal benutzen..