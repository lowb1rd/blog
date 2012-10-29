---
date: 2009-12-17 23:22:10
title: AWStats: die eigenen Besuche ausschließen
tags: Webtechnik
slug: awstats-die-eigenen-besuche-ausschliesen

last_updated: 2012-01-07 21:35:26
---

<a href="http://awstats.sourceforge.net/">AWStats</a> analysiert Logfiles und erstellt nette Statistiken. So auch hier für diesen Blog. Um die schier unfassbare Menge an Visits auch nur im Ansatz zu begreifen, rödelt also jede Nacht AWStats durch die Apache-Logfiles. Das hat im Gegensatz zu Google-Analythics den Vorteil, dass die Daten schön hier lokal gespeichert werden. Außerdem erfolgt die Erfassung der Daten Serverseitig und nicht per Javascript. Das wird nämlich von vielen Usern einfach geblockt (Hallo &lt;noscript&gt;!).

Statistiken sind nie zu 100% genau. Dennoch stört mich, dass <strong>die eigenen Hits</strong> in den Statistiken auftauchen. Das lässt sich aber verhindern..
<h2>Methode 1: eigener vHost</h2>
Einfach einen zweiten VHost, z.b. <em>dev.domain.com</em> anlegen. Der VHost zeigt ins selbe Verzeichnis wie die Hauptdomain, hat aber eine extra Logdatei. AWStats wertet nur die Logs der Hauptdomain aus. Die Links auf der Webseite müssen dazu aber zwingend relativ sein - sonst landet man früher oder später wieder auf der Hauptdomain und wird "erfasst".

Bei Wordpress stellte sich das schon mal als sehr schwierig heraus. Viele Links enthalten hier direkt den Domainnamen.
<h2>Methode 2: Apache conditional logging</h2>
Per <a href="http://httpd.apache.org/docs/2.2/logs.html">conditional logging</a> werden bestimmte Logeinträge erst gar nicht geschrieben. Als Kriterium kann z.B. die IP-Adresse verwendet werden. Mit DSL und daher dynamischer IP aber eher schwierig. Bleibt also nur noch der Cookie zur Identifikation. Wir setzen auf der <em>eigenen</em> Seite einen bestimmten Cookie, z.B. "DONTSTATME=true". Dazu einfach in die Adressleiste des Browsers folgendes tippen:
<pre>javascript:document.cookie="<strong>DONTSTATME=true</strong>; expires=Sat, 17 Dec 2021 22:59:00 GMT"</pre>

Nach reload der Seite ist der Cookie gesetzt. Jetzt können wir das conditional logging konfigurieren:
<pre>SetEnvIf HTTP_COOKIE "(^| )DONTSTATSME=true($|;)" dontlog
CustomLog logs/access_log common env=!dontlog</pre>

Nachteil hier: Requests mit diesem Cookie werden jetzt gar nicht mehr geloggt. Nirgends. Generell eine schlechte Idee. Irgendwie. Außerdem legt <a href="http://syscp.de">Syscp</a> pro VHost automatisch einen CustomLog-Eintrag an, der sich leider nicht so leicht um "env=!dontlog" erweitern lässt. Zumindest nicht ohne an der Source rumzufummeln.
<h2>Methode 3: Logfile greppen</h2>
AWStats akzeptiert auch Logfiles von einer Pipe: mit <strong>grep -v DONTSTATME=true /path/to/log</strong> würden bei AWStats nur "richtige" Hits ankommen. Dazu muss man den LogFile-Parameter der AWStats-Config folgendermaßen anpassen (<em>/etc/awstats/awstats.domain.com.conf</em>):
<pre>LogFile="grep -v DONTSTATME=true /path/to/access.log |"</pre>

Problem: Das Apache "combined"-Logformat loggt gar keine Cookies. Lässt sich aber leicht ändern. Wir öffnen die Datei /etc/apache2/apache2.conf und suchen die Zeilen mit "LogFormat".
<pre>LogFormat "%h %l %u %t \"%r\" %&gt;s %b \"%{Referer}i\" \"%{User-Agent}i\" <strong>\"%{Cookie}i\"</strong>" <span style="text-decoration: underline;">combined
</span></pre>

Entweder die Zeile mit "combined" direkt abändern oder ein neues Logformat erstellen, z.B. combined-cookies.

Dann muss AWStats das neue Logformat noch mitgeteilt werden (<em>/etc/awstats/awstats.domain.com.conf</em>):
<pre>LogFormat = "%host %other %logname %time1 %methodurl %code %bytesd %refererquot %uaquot <strong>%otherquot</strong>"</pre>

Apache restart/force-reload und alles ist gut. Vorausgesetzt man <strong>vergisst nicht den Cookie zu setzten</strong>, wenn man Methode 2 oder 3 verwendet.
<h2>Wie jetzt?</h2>
Jede Methode hat Ihre Vor- und Nachteile. Keine ist Perfekt. Ich bin noch am evaluieren :D Im Endeffekt müssen einfach nur insgesamt genug Hits vorhanden sein. Dann spielen die paar eigenen Visits auch keine Rolle mehr und man muss sich um das ganze Zeug keine Gedanken mehr machen. Solange das nicht der Fall ist, macht es durchaus Sinn, sich die Mühe zu machen und die eigenen Besuche auszuschließen.