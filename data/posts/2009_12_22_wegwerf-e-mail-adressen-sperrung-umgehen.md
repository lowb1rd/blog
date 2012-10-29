---
date: 2009-12-22 21:58:16
title: Wegwerf-E-Mail-Adressen: Sperrung umgehen
tags: Allgemein
slug: wegwerf-e-mail-adressen-sperrung-umgehen

last_updated: 2012-01-07 21:35:26
---

Anbieter für Wegwerf-E-Mail-Adressen gibt es viele. Ich persönlich bin <a href="http://www.mailinator.com/">Mailinator</a>-Nutzer der ersten Stunde. Mailinator war immer zuverlässig und tut genau das was es soll: temporäre E-Mail-Postfächer bereitstellen.

Mailinator gehört zu den größten/bekanntesten Anbietern - und genau das ist auch das Problem. Viele Seiten, die eine Anmeldung mit gültiger E-Mail-Adresse fordern, blockieren die bekannten Wegwerf-E-Mail-Anbieter.

Aber es gibt Abhilfe. Statt <em>@mailinator.com</em> kann man auch jede andere Domain nehmen, deren MX-DNS-Eintrag auf den selben Mailserver zeigt, wie der von <em>@mailinator.com</em>. Doch wie ist die IP-Adresse des Mailinator-Mailservers?
<pre>$ <strong>dig MX mailinator.com</strong>
; &lt;&lt;&gt;&gt; DiG 9.5.1-P3 &lt;&lt;&gt;&gt; MX mailinator.com
;; global options:  printcmd
;; Got answer:
;; -&gt;&gt;HEADER&lt;&lt;- opcode: QUERY, status: NOERROR, id: 54319
;; flags: qr rd ra; QUERY: 1, ANSWER: 0, AUTHORITY: 0, ADDITIONAL: 0
;; QUESTION SECTION:
;mailinator.com.                        IN      MX</pre>
Wie wir sehen, sehen wir nichts. Hat eine Domain keinen MX-Record, so wird automatisch der A-Record als Mailserver angenommen:
<pre>$ <strong>dig A mailinator.com</strong>
; &lt;&lt;&gt;&gt; DiG 9.5.1-P3 &lt;&lt;&gt;&gt; A mailinator.com
;; global options:  printcmd
;; Got answer:
;; -&gt;&gt;HEADER&lt;&lt;- opcode: QUERY, status: NOERROR, id: 7356
;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 0
;; QUESTION SECTION:
;mailinator.com.                        IN      A
;; ANSWER SECTION:
mailinator.com.         77483   IN      A       <strong>66.135.60.177</strong></pre>
Blöd nur, wenn man mal eben keine Domain <em>(Subdomain reicht auch)</em> zur Hand hat, deren MX-Eintrag man auf die Mailinator-IP ändern kann. Zum Glück kann man bei <a href="https://www.dyndns.com/">DynDNS</a> auch freie MX-Records vergeben:

[![Mailinator](images/2009/mailinator-300x258.jpg)](images/2009/mailinator.jpg)

<span title="Easy Money">Isi manni</span>! Bei <strong>"MX-Hostname"</strong> entweder mailinator.com, mail.mailinator.com (das war früher mal der MX von mailinator.com) oder direkt die IP (66.135.60.177) eintragen.

Statt <em>irgendwas@mailinator.com</em> kann man jetzt einfach <em>irgendwas@dyndnsacc.kicks-ass.org</em> verwenden. Somit umgeht man die Sperre zu 99%. Die Mails landen trotzdem direkt in der Mailinator-Inbox. Man könnte natürlich direkt den MX-Record prüfen und sperren, ist mir aber bisher auf noch keine Seite passiert. Let them eat spam!