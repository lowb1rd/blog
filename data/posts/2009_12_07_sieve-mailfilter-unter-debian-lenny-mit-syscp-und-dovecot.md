---
title: Sieve Mailfilter unter Debian Lenny mit Syscp und Dovecot
tags: Webtechnik
date: 2009-12-07 13:37:00
slug: sieve-mailfilter-unter-debian-lenny-mit-syscp-und-dovecot

last_updated: 2012-01-07 21:35:53
---

Mit [Sieve](//de.wikipedia.org/wiki/Sieve) können Serverseitig Filterregeln für E-Mails angelegt werden. So lassen sich z.B. Spam-eMails automatisch in einen SPAM-Ordner verschieben oder automatisch löschen.
Um eine Sieve-Regel zu erstellen, braucht man entweder einen Mail-Client mit Sieve-Unterstützung (z.B. Mozilla Thunderbird mit den [Sieve-Plugin](https://addons.mozilla.org/de/thunderbird/addon/2548)) oder einen Webmailer mit Sieve-Plugin (z.B. Squirrelmail mit avelsieve).

**Voraussetzungen:** Debian Lenny mit installiertem [Syscp](//www.syscp.org) und Dovecot als MDA.

Die Dovecot-Version in Debian Lenny ist 1.0. Sieve ist erst ab Dovecot 1.1 fester Bestandteil - um mit Dovecot 1.0 Sieve zu nutzen, [muss man Dovecot mit einem Patch neu kompilieren](//wiki.dovecot.org/ManageSieve/Install/1.0) oder einen standalone managesieve-Server, wie z.B. [pysieved](//github.com/miracle2k/pysieved) verwenden.

Glücklicherweise ist Dovecot 1.2 als <a href="http://packages.debian.org/de/lenny-backports/dovecot-imapd">lenny-backport</a> verfügbar. Und genau diese Version werden wir benutzen.

<!--more-->
<ol>
	<li>
<h3>Lenny-Backports in die sources.list aufnehmen</h3>
<pre>sudo vi /etc/apt/sources.list</pre>
Folgende Zeile hinzufügen:
<pre>deb http://www.backports.org/debian lenny-backports main contrib non-free</pre>
Anschließend den Backports-Key hinzufügen &amp; sourcen neu einlesen:
<pre>sudo wget -O - http://backports.org/debian/archive.key | sudo apt-key add -
sudo apt-get update</pre>
</li>
	<li>
<h3>Dovecot 1.2 von den Backports installieren</h3>
<pre>sudo apt-get -t lenny-backports install dovecot-common dovecot-pop3d dovecot-imapd</pre>
</li>
	<li>
<h3>Dovecot-Config anpassen</h3>
<pre>sudo vi /etc/dovecot/dovecot.conf</pre>
Änderungen sind <strong>FETT</strong> dargestellt
<pre>protocols = imap pop3 <em>pop3s imaps</em> <strong>managesieve
</strong>protocol lda {
    [...]
<strong>    mail_plugins = sieve
</strong>}</pre>
</li>
	<li>
<h3>Avelsieve für Squirrelmail installieren</h3>
<pre>sudo apt-get install avelsieve</pre>
</li>
</ol>
So weit, so einfach. Die individuellen Filterregeln werden immer im Home-Verzeichnis des Mail-Users abgelegt. Das ist bei Syscp <strong>/var/customers/mail/kunde/mail@example.org</strong>

Wenn jetzt in diesem Verzeichnis die Sieve-Filterregeln abgelegt würden, könnte man diese als IMAP-Ordner abonnieren, was aber nicht wirklich funktionieren kann. Deswegen legen wir alle Mailordner in ein weiteres Unterverzeichnis "mail". Dazu müssen wir die SQL-Queries von Dovecot anpassen.

Datei: <strong>/etc/dovecot/dovecot-sql.conf</strong> öffnen. Die Zeilen mit <strong>password_query</strong> und <strong>user_query</strong> durch folgendes ersetzen:
<pre>password_query = "SELECT username AS user, password_enc AS password, CONCAT(homedir,maildir) AS userdb_home, uid AS userdb_uid, gid AS userdb_gid, concat('maildir:',homedir,maildir,'mail/' ) AS userdb_mail, CONCAT('maildir:storage=', (quota*1024)) as quota FROM mail_users WHERE username = '%u' OR email = '%u'"
user_query = "SELECT CONCAT(homedir,maildir) AS home, concat('maildir:',homedir,maildir,'mail/' ) AS mail, uid, gid, CONCAT('maildir:storage=', (quota*1024)) as quota FROM mail_users WHERE username = '%u' OR email = '%u'"</pre>
Nach einem Restart von Dovecot (<strong>sudo /etc/init.d/dovecot restart</strong>) sollte auf Port 2000 TCP der Sieve-Server lauschen. Überprüfen kann man das z.B. mit nmap:

    nmap localhost
    > 2000/tcp open  callbook

In Squirrelmail unter dem Menüpunkt "Filters" kann man jetzt eine Sieve-Filterregel erstellen.

[![Sieve](images/2009/sieve1.jpg)](images/2009/sieve2.jpg)