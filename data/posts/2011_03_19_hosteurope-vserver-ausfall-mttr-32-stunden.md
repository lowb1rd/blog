---
date: 2011-03-19 13:45:26
title: Hosteurope vServer Ausfall! MTTR: 32 Stunden..
tags: Allgemein
slug: hosteurope-vserver-ausfall-mttr-32-stunden

last_updated: 2012-01-07 21:35:52
---

Ein Festplattencrash im Wirtssystem eines <a href="http://www.hosteurope.de/produkt/Virtual-Server-Linux-XL">vServers Linux XL 4.0</a> von <a href="http://www.hosteurope.de">Hosteurope</a> führte zu einer Downtime von Sage und Schreibe <strong>32 Stunden</strong>. Beim Zurückspielen der von Hosteurope angelegten Backups gab es offensichtlich Schwierigkeiten. Während den Restore-Versuchen seitens Hosteurope war es nicht möglich, einfach einen frischen vServer unter der selben IP zu erhalten - ich habe mich, nachdem der Server bereits über <strong>24 Stunden DOWN</strong> war, ja schon längst damit abgefunden, das Ding neu aufzusetzten und eigene Backups einzuspielen. Es blieb also nur: ausharren! Der Telefonsupport hat mir immer schön freundlich und verständnisvoll die Statusmeldungen vorgelesen - wirklich Helfen kann da einem aber offensichtlich keiner.

Backups macht man übrigens <strong>NUR</strong> um die Daten auch tatsächlich wiederherzustellen. Bei Hosteurope könnte man meinen, dass die Wiederherstellung eines vServers das ein oder andere Mal probiert wurde. Offenbar nicht so wirklich.
<h2><a href="http://de.wikipedia.org/wiki/Disaster_Recovery">Disaster Recovery</a> bei Hosteurope vServer: Ein Desaster!</h2>
Das <a href="http://www.hosteurope.de/produkte/Support-Leistungen-SLA">SLA von Hosteurope</a> liest sich ja ganz gut, ist aber im Ernstfall Schall und Rauch. Dort wird eine MTTR (mean time to repair, durchschnittliche Reparaturdauer) für einen Hardwaredefekt mit 4 (typisch: 2) Stunden angegeben! Bei Überschreitung der zugesicherten MTTR gibt es pro 30min. 1/30 der Monatsmiete zurück - maximal aber 50%. Für 32 Stunden Downtime gibt es jetzt also ganze <strong>8,50€ Entschädigung</strong> - sofern man diese per Fax oder Brief beantragt.

Eine DNS-Umstellung zu Beginn der Downtime wäre im Nachhinein natürlich die beste Entscheidung gewesen. Die Aussagen vom Support sowie die Statusmeldungen (was als Synonym zu verstehen ist) haben aber stets ein baldiges Ende der Restore-Arbeiten versprochen.

Das hier einiges außerplanmäßig schief gelaufen sein muss, ist klar. Dennoch Frage ich mich, was dann den Mehrpreis von Hosteurope gerechtfertigt. vServer mit dieser Spezifikation bekommt man woanders für weniger als die Hälfte. Ebenfalls mit Labeln wie <a href="http://www.hosteurope.de/content/Virtual-Server-110-Prozent-Performance">"<strong>100% Qualitätshardware</strong>" und "<strong>Professionelles Knowhow</strong>"</a>.

Bis gestern dachte ich, dass man bei Hosteurope tatsächlich mehr bekommt. In diesem Fall leider nicht. Dennoch sind die Probleme mit Hosteurope in der Gesamtheit wohl sehr gering. Zumindest war das immer mein Eindruck bei Recherchen im Internet. Als direkt Betroffener hilft das einem aber auch nicht weiter. Aber warten wir mal ab, ob sich Hosteurope dazu abschließend noch äußert.

[![Hosteurope SLA](images/2011/hosteurope-recovery-262x300.png)](images/2011/hosteurope-recovery.png)

Das ist die Chronologie der Statusmeldungen. Interessant: Die Störungsmeldung des parallel bereitgestellten vServers (leider mit anderer IP und somit unbrauchbar) und eine 6 Stunden Downtime während Wartungsarbeiten nur 4 Wochen zuvor. Laut <a href="http://www.pingdom.com/">Pingdom</a> macht das seit Beginn der Messungen (01.02.2011) eine Uptime von nur 96,81%.