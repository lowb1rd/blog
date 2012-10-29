---
date: 2010-09-02 20:15:42
title: Regworld Domainrobot
tags: Webtechnik
slug: regworld-domainrobot

last_updated: 2012-01-07 21:35:53
---

![Regworld](images/2010/regworld-dns-robot.jpg)

Wer eigene (v)Server betreibt kann Domains unabhängig vom Provider direkt bei einem Domainrobot registrieren. Das hat zum einen den Vorteil, dass man Handles (Kontaktdaten) und IP-Adressen selbstständig verwalten kann, zum anderen gibt es die Domains zu unschlagbar günstigen Preisen.

[Wie ich bereits erwähnt habe](2009/12/11/die-technik-hinter-diesem-blog-hosting-fur-schwaben.html), habe ich alle meine privaten Domains über den <a href="https://www.regworld.com/domainrobot.html">Domainrobot</a> vom <a href="http://www.regworld.com/">Regworld</a> registriert. Anfangs war ich skeptisch, da man über diesen Anbieter wenig im Internet findet (Früher war Regworld unter dem Namen "<em>Silverbird Consulting</em>" unterwegs .. warum auch immer). Zudem macht die Webseite auch nicht den seriösesten Eindruck. Bei Regworld gibt es eine .de-Domain aber für schlappe 2,22€/Jahr. Für die Neuregistrierung kommen nochmals einmalig 2,22€ dazu. Auch CNO-Domains sind mit 8,88€ ab der ersten Domain wirklich günstig.

<h2>Der Preis?</h2>
<a href="http://www.denic.de/denic/mitglieder/mitgliederliste.html">Denic-Mitglieder</a> bezahlen für eine de-Domain an die Denic 1,92 Euro netto (<a href="http://www.nicdirect.de/pages/de/profil.php">vermutlich</a>). Das sind 2,28 Euro inkl. MwSt. Somit verkauft Regworld die de-Domains wohl unter dem Einkaufspreis bei der Denic. Eventuell genießt Regworld hier auch Sonderkonditionen bei der Denic - aber mit Kosten für Nameserver, Buchhaltung/Rechnungserstellung und Technik für den Robot dürfte da so oder so nichts hängen bleiben.

Alle anderen Domainendungen (.mobi, .tel und Länderendungen wie .tv) sind dafür bei Regworld im Vergleich zu <a href="http://www.inwx.de/angebote/preisliste.php">INWX</a> extrem teuer. Aber irgendwie müssen die ja auch Geld verdienen...

Die Preise für de-Domains scheinen übrigens kein Lockangebot zu sein. Ich kann nicht genau sagen, wie lange dieser Preis schon gilt, mindestens aber 18 Monate. Die Sicherheit, dass das so bleibt hat man natürlich nicht. Aber dank Robot sind Domains dann auch relativ leicht zu einem anderen Robot umgezogen (IP-Adressen bleiben ja die selben).
<h2>Die Technik</h2>
Regworld verwendet den Domainrobot <a href="http://www.robtex.com/dns/autodns2.de.html">AutoDNS2</a>. Seit einiger Zeit ist auch ein neues Webinterface mit AutoDNS3 verfügbar. Das Ganze funktioniert relativ fix - auch wenn es hier nicht wirklich auf Geschwindigkeit ankommt.

[![AutoDNS2 von Regworld](images/2010/autodns2-300x245.jpg)](images/2010/autodns2.jpg)
[![AutoDNS3 von Regworld](images/2010/autodns3-300x245.jpg)](images/2010/autodns3.jpg)

Nameserver gibt es für alle über Regworld registrierten Domains gratis. Einen Primary und einen Secondary. Einträge werden auf den Nameservern automatisch angelegt, wenn eine Domain registriert wird. Außerdem hat man selbstverständlich die Möglichkeit eigene Nameserver zu verwenden.

Die technischen Details habe ich bereits in einem [früheren Blogbeitrag](2009/12/13/domains-mit-einem-domainrobot-direkt-registrieren.html) beschrieben.

DNS-Namen der zwei Nameserver sind (bei mir) ns3.regworld.com und ns4.regworld.com. Alternativ auch <strong>ns1.bces.de</strong> und <strong>ns2.bces.de</strong>. Man kann selbstverständlich per <a href="http://en.wikipedia.org/wiki/Domain_Name_System#Circular_dependencies_and_glue_records">GLUE-Record</a> auch seine eigenen Domains als Regworld-Nameserver verwenden.
<h2>Support</h2>
Für 2,22€ pro de-Domain kann man nicht all zu viel erwarten. Dennoch ist der Support gut erreichbar - sowohl per E-Mail als auch telefonisch. Bei Problemen mit einem Nameserver konnte man mir aber nicht wirklich weiterhelfen. So ist es also offenbar nicht möglich einen eigenen primären Nameserver zu betreiben und den sekundären bei Regworld. Ärgerlich, da es sich so über den Robot konfigurieren lässt, aber einfach nicht funktioniert. Primären und sekundären bei Regworld sowie weitere eigene sekundäre funktioniert aber.
<h2>Fazit</h2>
Bei Domains finde ich einzig und allein den Preis ausschlaggebend. Die Inklusiv-Nameserver nehme ich gerne mit, weil sie gut funktionieren. Wenn diese nicht zuverlässig wären, würde ich eigene betreiben. Und dann kann von Regworld-Seite eigentlich fast nichts mehr schief gehen.

Wer exotische Endungen benötigt sollte sich die Preisliste genau anschauen - hier ist die ein oder andere Überraschung versteckt. Zu den anderen Services von Regworld (Webspace/Server) kann ich nichts sagen. Da es bei diesen Produkten aber eher auf den Support ankommt würde ich mal spontan davon abraten. Siehe auch die <a href="http://www.webhostlist.de/provider/meinungen/6515/Regworld-GmbH.html">Kommentare bei Webhostlist</a> (hier geht es nicht um den Robot).