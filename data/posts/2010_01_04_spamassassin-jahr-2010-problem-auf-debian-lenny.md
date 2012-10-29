---
date: 2010-01-04 19:05:41
title: Spamassassin: Jahr 2010 Problem auf Debian Lenny?
tags: Allgemein
slug: spamassassin-jahr-2010-problem-auf-debian-lenny

last_updated: 2012-01-07 21:35:53
---

<a href="http://www.heise.de/newsticker/meldung/Jahr-2010-Problem-im-Spam-Filter-von-GMX-Update-894258.html">Heise</a> hat es direkt an Neujahr berichtet: <a href="http://spamassassin.apache.org/">Spamassassin</a> hat offenbar ein <strong>Jahr-2010-Bug</strong>. So werden alle Mails mit Datum 2010 mit dem <strong>FH_DATE_PAST_20XX</strong>-Flag versehen.

Spam-E-Mails haben oft ein Datum weit in der Zukunft, damit Sie im Mail-Client immer ganz oben erscheinen (<em>clever!</em>). Spamassassin versucht dem mit dieser Regel entgegen zu wirken. Leider wurde die Jahreszahl 2010 fest einprogrammiert (<em>clever?</em>), und nun offenbar von der Realität eingeholt :)

Da dieses Flag eine hohe Gewichtung hat, landen jetzt also auch normale E-Mails oft fälschlicherweise im Spam-Ordner (false positive). Das Problem betrifft nicht nur den Spamfilter von  GMX und 1&amp;1 sondern im Prinzip jede Spamassassin-Installation.

Für Debian Lenny gab es für das Spamassassin-Paket noch am selben Tag einen bugfix (Spamassassin <a href="http://news.debian.net/2010/01/03/spamassassin-update-to-avoid-false-positives-in-2010/">3.2.5-2+lenny1.1~volatile1</a> aus dem <a href="http://www.debian.org/volatile/">Debian Volatile-Repo</a>). Mittlerweile gibt es auch ein offizielles Regel-Update, was die Installation des Volatile-Bugfixes überflüssig macht. Ein einfaches <strong><a href="http://wiki.apache.org/spamassassin/RuleUpdates">sa-update</a></strong> reicht aus, um die Regeln auf den neusten Stand zu bekommen und den Jahr-2010-Bug zu eliminieren.
<h3>Wie finden wir nun heraus, ob der eigene Debian Lenny/Etch-Server von diesem Bug betroffen ist?</h3>
SA-Regeln liegen im Ordner /usr/share/spamassassin/, Regel-Updates im Ordner /var/lib/spamassassin/<em>&lt;sa-version&gt;</em>/updates_spamassassin_org

Die Dateien im Update-Ordner überschreiben die Standard-Regeln. Die betroffene Datei heißt <strong>72_active.cf</strong>. Diese öffnen wir und suchen nach der FH_DATE_PAST_20XX-Regel:
<pre>sudo vi /var/lib/spamassassin/<em>&lt;sa-version&gt;</em>/updates_spamassassin_org/72_active.cf
  ##{ FH_DATE_PAST_20XX
  header   FH_DATE_PAST_20XX      Date =~ <strong>/20[1-9][0-9]/</strong> [if-unset: 2006]
  describe FH_DATE_PAST_20XX      The date is grossly in the future.
  ##} FH_DATE_PAST_20XX</pre>
Wichtig ist der <strong>fett</strong> markierte Regex. Dieser trifft auch auch das Datum 2010 zu. Höchste Zeit für ein Update:
<pre>sudo sa-update</pre>
Wir öffnen die Datei erneut:
<pre>sudo vi /var/lib/spamassassin/<em>&lt;sa-version&gt;</em>/updates_spamassassin_org/72_active.cf
  ##{ FH_DATE_PAST_20XX
  header   FH_DATE_PAST_20XX      Date =~ <strong>/20[2-9][0-9]/</strong> [if-unset: 2006]
  describe FH_DATE_PAST_20XX      The date is grossly in the future.
  ##} FH_DATE_PAST_20XX</pre>
Aus <strong>[1-9]</strong> wurde <strong>[2-9]</strong>.  Spamassassin-Neustart:
<pre>sudo /etc/init.d/spamassassin restart</pre>
Problem gelöst! Zumindest bis zum Jahr 2020...

Dass damit das Problem nur aufgeschoben wird, ist aber <a href="https://issues.apache.org/SpamAssassin/show_bug.cgi?id=6269#c26">bekannt</a>. Die Regel soll längerfristig sowieso komplett abgeschafft werden.

Spamassassin aktualisiert sich übrigens unter Lenny i.d.R. automatisch via Cronjob (/etc/cron.daily/spamassassin). Hier muss man also nichts weiter tun. Ansonsten beseitigt ein manuelles <em>sa-update</em> diesen Bug ganz sicher, und bringt für mindestens 10 Jahre Ruhe :)