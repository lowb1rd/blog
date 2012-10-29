---
date: 2011-01-24 18:03:39
name: neunzehn83
www: 
email: mail@neunzehn83.de
---

/usr/sbin/sendmail kommt von postfix und ist eine sendmail kompatible binary
"sudo -u $Y" habe ich dazu benutzt, um den getmail-Prozess unter dem jeweiligen User der Mailadresse auszuführen. Kann man denke ich auch weglassen und getmail direkt als root ausführen.

Ansonsten stimmt wohl was mit deiner getmailrc_-File nicht. In Zeile 1 sollte eigentlich nur "[options]" stehen...