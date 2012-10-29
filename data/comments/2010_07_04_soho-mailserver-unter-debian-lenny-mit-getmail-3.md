---
date: 2011-01-30 13:22:12
name: bjoern
www: 
email: mrgoofy911@gmx.net
---

MOin Moin,

danke nochmal fuer die schnelle hilfe. Aber leider besteht das Problem noch und ich finde es nicht.
Habe unter VMWare ein frisches Debiansystem Lenny aufgesetzt, funktioniert auch alles, habe dann nach diesem Howto hier alles gemacht, auch Dovecot 1.2 von backports installiert, funktionierte auch alles soweit..

nur wenn ich versuch das getthemail.sh script auszuführen kommt immer dieser fehler:
-------------------------------------------------
ba-mail01:/usr/local/bin# ./getthemail.sh
Configuration error: configuration file /home/bjoern/.getmail/getmailrc_mrgoofy911_gmx.net incorrect (arguments: incorrect format (invalid syntax (, line 1)))
ba-mail01:/usr/local/bin#

der Inhalt der datei sieht so aus:
-------------------------------------------------
ba-mail01:/usr/local/bin# cat getthemail.sh
#!/bin/sh
 LOCK_FILE="/var/lock/getthemail"
 if [ ! -f "${LOCK_FILE}".lock ];
 then
 lockfile-create "${LOCK_FILE}"
 lockfile-touch "${LOCK_FILE}" &amp;

 for getfile in $( find /home/*/.getmail/ -name getmailrc\* -print )
 do
 Y=`ls -l $getfile | awk '{print $3; }'`
 touch $getfile
 /usr/bin/getmail --getmaildir /home/$Y/.getmail/ --rcfile $getfile
 done
 lockfile-remove "${LOCK_FILE}"
 fi
exit 0;

Der Inhalt der getmailrc_xx_gmx.net sieht so aus:
--------------------------------------------------
ba-mail01:/home/bjoern/.getmail# cat getmailrc_xx_gmx.net
[options]
delete = false

[retriever]
type = SimplePOP3Retriever
server = pop.gmx.net
port = 110
username = xx@gmx.net
password = xx
use_apop = false
timeout = 180
delete_dup_msgids = false

[destination]
type = MDA_external
path = /usr/sbin/sendmail
arguments = ('-f', xx@gmx.net.de', '-oi', 'bjoern@ba-mail01)

[filter]
type = Filter_external
path = /usr/bin/spamc
arguments = ("-s", "250000", "-u", "bjoern", "-p", "783", )

also alles so, wie beschrieben, bekomme bei der installation auch keine fehler, nur wenn ich versuche, mit getmail die mails abzuholen..

noch ein Hinweis:
dovecot muss mit "dovecot-imapd" installiert werden, "dovecot-imap" gibt es nicht..

hoffe kannst mir dabei helfen, verzweifel hier so langsam..
Danke und Gruß