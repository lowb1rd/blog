---
date: 1970-01-01 01:00:00
title: getmail mit IMAP IDLE (unter Debian)
tags: Allgemein
slug: getmail-mit-imap-idle-unter-debian

last_updated: 2012-01-07 20:21:21
---

Mit [getmail](//pyropus.ca/software/getmail/) lassen sich prima verschiedene POP3-Konten abholen und im lokalen Mailserver organisieren. Zum Setup habe ich bereits früher etwas geschrieben: [SOHO Mailserver mit Debian](/blog/2010/07/04/soho-mailserver-unter-debian-lenny-mit-getmail/). Per Cron kann man den getmail-Prozess in bestimmten Intervallen anstoßen:

    ~$ crontab -l
    */1 8-23 * * 1-5 /usr/local/bin/getthemail.sh >/dev/null 2>&1
    0 8-23 * * 6,7 /usr/local/bin/getthemail.sh >/dev/null 2>&1
    0 0-7 * * * /usr/local/bin/getthemail.sh >/dev/null 2>&1

Wochentags zwischen 8 und 24 Uhr werden jede Minute, außerhalb dieser Zeit und an Wochenenden jede Stunde Mails abgepoppt.

Der kürzeste Intervall ist 1 Minute. Das ist manchmal viel zu lange, und außerdem ist pollen ja sowas von 90er-Jahre...

Abhilfe würde hier der [IMAP IDLE-Modus](//www.faqs.org/rfcs/rfc2177.html) schaffen. Dabei baut der Client eine permanente Verbindung auf und wird bei einer ankommenden E-Mail **vom Mailserver** benachrichtigt.

Leider unterstützt getmail kein IMAP IDLE. [Laut Mailingliste](//osdir.com/ml/mail.getmail.user/2005-10/msg00011.html) wird es das wohl in naher Zukunft auch nicht tun. Also: Selbst ist der Mann!

Die Idee
--------
Das IMAP-Protokoll ist extrem simpel. Mit Python und [imaplib2](//janeelix.com/piers/python/imaplib2) ist schnell ein IMAP-Client geschrieben der im IDLE-Modus auf neue E-Mails wartet. Beim Eintreffen einer E-Mail kann das Python-Script dann den getmail-Prozess anstoßen. Getmail holt weiterhin die E-Mails per POP3 ab - aber nur dann, wenn auch wirklich neue Mails verfügbar sind und nicht mit POP3-Login-Spam.

Python?
-------
Eigentlich ist auch auf der Kommandozeile PHP die Skriptsprache meiner Wahl - doch wenn es um Linux-Daemons und Threading geht, nimmt man besser Python. Außerdem wollte ich mich schon länger einmal mit Python beschäftigen. An dieser Stelle ist denke ich der Hinweis angebracht, dass ich absoluter Python-Neuling bin, und es wohl bessere Implementierungen eines Imap-Idlers gibt. Wie so oft, ist das hier kein Copy+Paste Tutorial - es geht einfach nur darum die Möglichkeiten aufzuzeigen, getmail via IMAP IDLE anzustoßen.

Die Anforderungen
-----------------
Wir wollen ein **Linux-Daemon** inkl. **start-stop-Script** und **Logfile**. Es sollen mehrere Accounts parallel überwacht werden (**threading**), konfigurierbar über eine **INI-Datei**. Selbstverständlich wollen wir ausschließlich IMAPS (SSL) verwenden.

Die Umsetzung
-------------
Laut [IMAP-RFC](//www.faqs.org/rfcs/rfc2177.html) sollte der IDLE-Modus nach spätestens 29 Minuten neu gestartet werden. Das werden wir natürlich berücksichtigen.

> The server MAY consider a client inactive if it has an IDLE command running, and if such a server has an inactivity timeout it MAY log the client off implicitly at the end of its timeout period. Because of that, clients using IDLE are advised to terminate the IDLE and re-issue it **at least every 29 minutes** to avoid being logged off.

Für das restliche IMAP-Handling verwenden wir [imaplib2](http://janeelix.com/piers/python/imaplib2). Von [diesem IMAP-Idler](http://blog.timstoop.nl/2009/03/11/python-imap-idle-with-imaplib2/) habe ich mich inspirieren lassen. Herausgekommen ist ein 110-Zeilen Python-Skript (imapidle.py), welches sich auch direkt starten lässt (./imapidle.py).

Code
----
[imapidle.py](http://neunzehn83.de/blog/wp-content/uploads/2011/02/imapidle.py_.html)

Unix-Daemon
-----------
Auf [Chris' Python Page](http://homepage.hispeed.ch/~py430/python/) findet sich ein nettes Script, welches unser imapidle.py-Skript zu einem Unix-Daemon macht. Inkl. Start-Stop-Skript und Logging. Isi manni!

Download/Installation
---------------------
Alle Files gibt es [hier](#) zum herunterladen. Die imapidle.py kann auch direkt auf der Konsole gestartet werden. Wer das ganze als Daemon laufen lassen will, sollte die Dateien nach /usr/local/sbin kopieren. Die Datei "initd-imapidled" ist das Start-Stop-Skript welches nach /etc/init.d kopiert werden muss. Zum automatischen Start beim Boot fehlt außerdem noch ein Symlink nach /etc/rcx.d

    cp /usr/local/sbin/initd-imapidled /etc/init.d/imapidled
    ln -s /etc/init.d/imapidled /etc/rc2.d/S30imapidled
    ln -s /etc/init.d/imapidled /etc/rc0.d/K30imapidled

    /etc/init.d/imapidled start

Ausblick: iPhone PUSH
---------------------
Hier lässt sich prima anknüpfen und z.B. auch gleich eine PUSH-Benachrichtigung aufs iPhone mit [notifio](http://notifo.com/) triggern. Mehr dazu gibt es vielleicht bald hier zu lesen..