---
date: 2011-03-27 12:29:05
title: Protocol Relative URLs
tags: Webtechnik
slug: protocol-relative-urls

last_updated: 2012-01-07 21:35:52
---

Ist eine Webseite per https-Protokoll SSL-verschlüsselt aufrufbar, so müssen alle externen Objekte auf dieser Webseite ebenfalls über HTTPS geladen werden. Ist das nicht der Fall, gibt es im Internet-Explorer eine hässliche Meldung, bei anderen Browsern verschwindet einfach das begehrte Schloss-Icon.

![IE mixed content](images/2011/iesux.png)

Da Objekte (also Bilder, Scripte, CSS-Dateien usw.) meistens mit relativer Pfadangabe geladen werden, ist das aber kein Problem. Anders sieht das bei Benutzung eines CDN oder externen JS-Dateien, z.B. über Google APIs, aus. Hier muss nämlich die komplette URI zum Script angegeben werden, inklusive dem Protokoll. Das ist ein Problem, wenn die Seite sowohl über HTTP als auch HTTPS erreichbar ist. Abhilfe schafft das "<a href="http://tools.ietf.org/html/rfc3986#section-4.2">network-path reference</a>" Schema:
<pre>&lt;script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"&gt;&lt;/script&gt;</pre>
In diesem Fall würde <a href="http://jquery.com/">jQuery</a> wahlweise per http oder https geladen werden - je nach Kontext.
<h2>Google-Analytics</h2>
Der Google-Analytics Code zum Einbauen in die eigene Webseite sieht so oder so ähnlich aus:
<pre>&lt;script type="text/javascript"&gt;
   var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
   document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
&lt;/script&gt;</pre>

Hier wird je nach Protokoll per <em>document.write</em> ein <em>script-Tag</em> erzeugt. Echt unschön. Mit einer protocol relative URL sähe das so aus:

<pre>&lt;script type="text/javascript" src="//google-analytics.com/ga.js"&gt;&lt;/script&gt;</pre>

Viel schöner! Warum benutzt dann Google aber keine protokoll-relativen URLs? Schuld ist mal wieder, wie so oft, der IE. Und zwar in der Version 6 - hier soll es je nach Sicherheitseinstellungen zu Zertifikats-Fehlermeldungen kommen. Wen weder den IE6 noch seine Sicherheitseinstellungen interessieren, kann sein GA-Script also protokoll-relativ einbinden. Ach ja, wo wir schon beim Internet Explorer sind: In der Version 7 und 8 läd dieser protokoll-relative &lt;link&gt; und @import Ressourcen leider <a href="http://www.stevesouders.com/blog/2010/02/10/5a-missing-schema-double-download/">zwei mal herunter</a>.