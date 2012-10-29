---
date: 2010-07-04 15:13:21
title: SOHO Mailserver unter Debian Lenny mit Getmail
tags: Allgemein
slug: soho-mailserver-unter-debian-lenny-mit-getmail

last_updated: 2012-01-07 21:35:53
---

Hier geht's um die Idee, einen schicken, lokalen <em>SOHO</em> Mailserver unter Debian Lenny einzurichten. SOHO bedeutet <em>Small Office, Home Office</em> - wir reden hier also vom klassischen Serverlein daheim im Schrank, bis hin zum ausgewachsenem Server mit einigen (wenigen) Clients. Das ist kein Copy/Paste-Tutorial. Vielmehr geht es hier um die Möglichkeiten von Debian, einen solchen Mailserver zu realisieren.
<h2>Wozu überhaupt ein lokaler Mailserver?</h2>
E-Mails direkt am Client per <strong>POP</strong> abzuholen hat einige Nachteile. Besonders dann, wenn man von verschiedenen Rechner auf seine E-Mails zugreifen will. Außerdem ist die Datensicherung, auch bei nur einem Client, echt anstrengend.

Deswegen wurde <strong>IMAP</strong> erfunden! Mich stört aber:

- Die Daten liegen irgendwo (im schlimmsten Fall bei Google!).
- Begrenzter Speicherplatz: schon allein die Tatsache, dass eine Grenze existiert, stört.
- **Lag!** Egal ob xxMbit und lokaler Cache: inet &lt; LAN

Ein lokaler Mailserver löst all diese Probleme: E-Mails werden von diversen Konten per POP3 beim jeweiligen Provider abgeholt, und dann per IMAP im lokalen Netz zur Verfügung gestellt.
<h2>Die Anforderungen</h2>

- Abholung von E-Mails *verschiedener* POP3-Konten bei verschiedenen Providern (GMX, 1und1, usw.)
- Serverseitige (Spam-)Filter und Weiterleitungen
- Bereitstellung der Mails mehrerer externer POP-Konten in <strong>einem</strong> IMAP-Postfach (in Unterordnern)
- Versand über den SMTP-Server des *jeweiligen* Providers
- Internen Mailversand direkt zustellen
- Shared IMAP Ordner

<h2>Die Installation</h2>
Glücklicherweise sind alle benötigten Tools als Debian-Pakete verfügbar. Als MTA verwenden wir <strong><a href="http://www.postfix.org/">Postfix</a></strong>, IMAP-Postfächer werden von <a href="http://www.dovecot.org/"><strong>Dovecot</strong></a> bereitgestellt. <strong><a href="http://de.wikipedia.org/wiki/Sieve">Sieve</a></strong> filtert die E-Mails serverseitig. <strong><a href="http://pyropus.ca/software/getmail/">Getmail</a></strong> holt E-Mails von POP3-Konten und <strong><a href="http://spamassassin.apache.org/">Spamassassin</a></strong> kümmert sich um den SPAM. Als Bonus gibt's den Webmailer <strong><a href="http://squirrelmail.org/">Squirrelmail</a></strong> mit <strong><a href="http://squirrelmail.org/plugin_view.php?id=73">Avelsieve</a></strong>, zum Erstellen/Bearbeiten von Sieve-Regeln per Browser.
<h3>Benutzer</h3>
Die verschiedenen Mail-Benutzer sind "normale" Shell-Benutzer. Die Mail-Verzeichnisse sind jeweils im Home-Verzeichnis des Users. Wir entscheiden uns mal für ~/mail. Das Format ist Maildir und die Verzeichnisse cur, tmp und new müssen wir <span style="text-decoration: line-through;">von Hand</span> <em>mit maildirmake</em> unterhalb von ~/mail anlegen oder einmalig in /etc/skel.
<h3>Postfix</h3>
Eigentlich könnte man die Mails auch direkt vom Client aus wegschicken. Der "Umweg" über den lokalen Mailserver hat aber den Vorteil, dass wir interne Mails direkt zustellen können (dazu später mehr). Außerdem finde ich es schicker, die SMTP-Daten einmalig am Server zu konfigurieren und dann am Client die einfachen Shell-Logindaten einzutippen. Für serverseitige Weiterleitungen (mit Sieve) brauchen wir sowieso einen MTA.
<pre>apt-get install postfix postfix-tls</pre>

Wir wählen Internetbetrieb mit Smarthost. Wir wollen ausgehende Mails über den jeweiligen SMTP-Server des Mail-Anbieters senden (also alles von me@gmx.net über smtp.gmx.net, alles von you@1und1.de über smtp.1und1.de usw.). Deshalb müssen wir Postfix so konfigurieren, dass es je nach Absenderadresse verschiedene SMTP-Server mit Authentifizierung verwendet. Das geht mit <em>sender_dependent_relayhost_maps</em>:

<strong>/etc/postfix/main.cf: </strong>
<pre>sender_dependent_relayhost_maps = hash:/etc/postfix/sender_relay
smtp_tls_auth_enable = yes
smtp_tls_password_maps = hash:/etc/postfix/tls_passwd</pre>
<strong>/etc/postfix/sender_relay:</strong>
<pre>me@gmx.net     [smtp.gmx.net]
you@1und1.de   [smtp.1und1.de]
...</pre>
<strong>/etc/postfix/tls_passwd:</strong>
<pre>me@gmx.net     Benutzer:Passwort
you@1und1.de   you:geheim</pre>

<h3>Dovecot</h3>
Wir installieren Dovecot 1.2 aus den Lenny-Backports. Warum wir die Backports-Version von Dovecot nehmen, und wie man die Backports zur sources.list hinzufügt, habe ich in einem <a href="http://neunzehn83.de/blog/2009/12/07/sieve-mailfilter-unter-debian-lenny-mit-syscp-und-dovecot/">früheren Beitrag</a> schon einmal erwähnt.
<pre>apt-get install -t lenny-backports dovecot-common dovecot-imapd</pre>

<h3>Getmail4 /Spamassassin</h3>
<pre>apt-get install getmail4 spamassassin</pre>

Jeder Benutzer hat pro Account eine <em>getmailrc_*-Datei</em> in <em>~/home/.getmail/</em>. Ein Cronjob führt Getmail regelmäßig aus. Ein Lockfile sorgt dafür, dass E-Mails nicht doppelt abgeholt werden, wenn ein von Cron angestoßener Getmail-Prozess einmal länger dauert.
<pre><strong>~$ vi ~/.getmail/getmailrc_me_gmx.net</strong>
[options]
delete = true
 
[retriever]
type = SimplePOP3Retriever
server = pop.gmx.net
port = 110
username = me@gmx.net
password = geheim
use_apop = false
timeout = 180
delete_dup_msgids = false
 
[destination]
type = MDA_external
path = /usr/sbin/sendmail
arguments = ('-f', 'me@gmx.net', '-oi', '<em>Shell-Login-Name</em>@<em>Lokaler-Host'</em>)
 
[filter]
type = Filter_external
path = /usr/bin/spamc
arguments = ("-s", "250000", "-u", "<em>Shell-Login-Name</em>", "-p", "783", )</pre>

An Postfix wird einmal die lokale Adresse (Shell-User@Hostname) und die externe E-Mail-Adresse übergeben. Das erlaubt die spätere Einsortierung in IMAP-Unterordner anhand des POP-Kontos mittels Envelope-From-Header.

Die Spamassassin-Filterung muss direkt von Getmail über [Filter] konfiguriert werden. Die Weiterleitung an Postfix erfolgt nämlich <strong>NICHT</strong> per SMTP, und somit ist der Spamassassin in Postfix nutzlos.
<h4>Der Cronjob</h4>
Der Cronjob wird von root ausgeführt und sucht in Home-Verzeichnissen nach getmailrc_*-Dateien. In diesem Beispiel macht er das jede Minute.
<pre><strong>~$ sudo crontab -u root -e</strong>
*/1 * * * * /usr/local/bin/getthemail.sh &gt;/dev/null 2&gt;&amp;1
  
<strong>~$ sudo vi /usr/local/bin/getthemail.sh</strong> 
 #!/bin/sh
 LOCK_FILE="/var/lock/getthemail"
 if [ ! -f "${LOCK_FILE}".lock ];
 then
 lockfile-create "${LOCK_FILE}"
 lockfile-touch "${LOCK_FILE}" &amp;
 
 for getfile in $( find /home/\*/.getmail/ -name getmailrc\\\* -print )
 do
 Y=`ls -l $getfile | awk '{print $3; }'`
 touch $getfile
 sudo -u $Y /usr/bin/getmail --getmaildir /home/$Y/.getmail/ --rcfile $getfile
 done
 lockfile-remove "${LOCK_FILE}"
 fi
exit 0;</pre>

<h3>Sieve-Filterung</h3>
Dovecot 1.2 kommt mit eigener Sieve-Implementierung. Ganz fix:
<pre>~$ sudo apt-get install squirrelmail avelsieve
~$ sudo vi /etc/dovecot/dovecot.conf
protocols = imap pop3 pop3s imaps <strong>managesieve</strong>
protocol lda {
   [...]
   <strong>mail_plugins = sieve</strong>
}</pre>

Per Sieve können wir jetzt auch E-Mails von verschiedenen POP3-Accounts in einem IMAP-Account organisieren. Getmail kann Mails verschiedener Konten in einem IMAP-Ordner zur Verfügung stellen. Mit einer Sieve-Regel lassen sich diese E-Mails in verschiedene Ordner schieben. Der Envelope-To-Header entspricht dabei immer der ursprünglichen Ziel-Adresse.
<h3>Interne Mails</h3>
Da alle ausgehenden Mails über den Postfix laufen ist das ein Klacks! Wir "mappen" externe Adressen auf lokale User-Accounts. Das ganze passiert mit Hilfe von <strong>alias_maps</strong>
<pre>~$ postconf -e alias_maps = hash:/etc/postfix/local_delivery
~$ sudo vi /etc/postfix/local_delivery
test@gmx.net    test</pre>

Wobei "test" ein lokaler Shell-Benutzer wäre.
<h3>Shared IMAP Ordner</h3>
Auch hier bietet Dovecot verschiedene Möglichkeiten. Die einfachste: wir symlinken das Shared-Folder einfach in das jeweilige IMAP-Root-Verzeichnis:
<pre>ln -s /home/share/mail /home/user/.Shared</pre>

In diesem Fall sharen wir die INBOX des users "share". Das funktioniert natürlich auch für Unterverzeichnisse. Wichtig ist nur, dass dann der user "user" Schreibrechte auf cur, tmp, new und auf die Dovecot-Index-Dateien des "share"-Benutzers besitzt.