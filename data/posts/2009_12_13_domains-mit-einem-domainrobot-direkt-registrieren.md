---
date: 2009-12-13 17:04:30
title: Domains mit einem Domainrobot direkt registrieren
tags: Webtechnik
slug: domains-mit-einem-domainrobot-direkt-registrieren

last_updated: 2012-01-07 21:35:26
---

Domains gibt es überall. Meist direkt vom Server-Anbieter. Diese sind aber oft teurer als nötig und zudem ziemlich unflexibel zu verwalten. Wenn man mit dem Server zu einem anderen Anbieter umzieht, muss man alle Domains mitnehmen.

Das geht auch einfacher und billiger: mit einem Domainrobot. Voraussetzung hierfür ist zwingend ein Server mit Root-Rechten oder ein Provider, der externe Domains erlaubt.

<!--more-->
<h2>Domainrobot</h2>
Mit einem Domainrobot kann man Anfragen direkt an eine Domain-Registrierungsstelle (z.B. die DENIC) stellen. Dazu gibt's ein Webinterface und meistens auch eine E-Mail-XML-Schnittstelle.
<h2>Preise</h2>
Durch die eigene Verwaltung sind die Domainpreise mit Abstand am niedrigsten. .de-Domains gibt es z.B. bei <a href="http://www.regworld.com">Regworld</a> für 2,22€, CNO für 8,88€. Auch <a href="http://www.inwx.de">InternetworX</a> hat gute Preise. Zudem ist InternetworX der bekanntere Anbieter mit gutem  Support - wenn man ihn denn braucht. Weitere Anbieter findet man auf der <a href="http://www.webhostlist.de">Webhostlist</a>.
<h2>Domainregistrierung</h2>
Alle Screenshots sind aus AutoDNS3 von Regworld. Aber auch andere Anbieter, wie z.B. InternetX, arbeiten mit dieser Weboberfläche.
<h3>Handles</h3>
Zu jeder Domain werden verschiedene Kontaktdaten gespeichert. In den sog. "Handles" sind Informationen über eine Person oder Organisation gespeichert, sozusagen ein Adresseintrag zu einer Domain. Jeder Domain müssen vier unterschiedliche Handles zugewiesen werden:
<ul>
	<li> Domaininhaber</li>
	<li>Administrativer Ansprechpartner (admin-c)</li>
	<li>Technischer Ansprechpartner (tech-c)</li>
	<li>Zonenverwalter (zone-c)</li>
</ul>
Die ersten zwei dürften soweit bekannt sein. Registriert man seine Domains über einen Webhoster wie z.B. 1&amp;1, so sind die letzten beiden Handles nicht änderbar, sondern fest auf 1&amp;1 festgelegt. Mit einem Domainrobot können auch diese Einträge frei gewählt und jederzeit bearbeitet werden. Beim tech-c und zone-c Handle muss zwingend eine Telefon- und Faxnummer angegeben werden.

<a href="http://neunzehn83.de/blog/wp-content/uploads/2009/12/autodns2_handles.jpg"><img class="size-medium wp-image-102 alignnone" title="autodns2_handles" src="http://neunzehn83.de/wp/wp-content/uploads/2009/12/autodns2_handles-285x300.jpg" alt="autodns2_handles" width="285" height="300" /></a>
<h4>Handle-Typen</h4>
Es gibt drei verschiedene Handle-Typen: Person, Org (Firma) und Role.  Der admin-c-Handle muss immer vom Typ Person sein.

Zudem können tech-c, zone-c und der Domaininhaber auch Handles von Typ "Role" sein. Hier steht dann keine Person hinter dem Handle sondern eine "Rolle" bzw. Funktion, wie z.B. "Hostmaster" oder "DNS Admin".

Bevor eine Domain über einen Robot registriert werden kann, muss mindestens ein Handle angelegt werden. Dieses Handle kann für alle oben genannten Domain-Handles benutzt werden. Vorausgesetzt es besitzt eine Telefon- und Faxnummer.
<h3>Nameserver</h3>
Die meisten Domainrobot-Anbieter stellen auch gleich mind. zwei kostenlose Nameserver zur Verfügung. Auf diesen Nameservern darf man alle Domains, die man über diesen Robot registriert, anlegen.

Jede Domain braucht mind. zwei Nameserver-Einträge. Es empfiehlt sich diese vor der Domainregistrierung anzulegen. Die meisten Domainrobot-Weboberflächen bieten hierzu Formulare an. Wenns hart auf hart kommt, muss man das Zonefile eben selbst anlegen.

Wichtig ist für den Anfang eigentlich nur die richtigen NS-Einträge und ein A Record. Das ist bei den meisten Robots direkt über ein Formular einstellbar.

[![Mailinator](images/2009/autodns2_dns-285x300.jpg)](images/2009/autodns2_dns.jpg)

Für jede Domainendung gibt es gewisse Richtlinien für Nameserver. So schreibt die DENIC mindestens zwei (maximal fünf) Nameserver pro Domain vor. Die IP-Adressen der Nameserver dürfen nicht im selben /24 Subnetz sein - d.h. die ersten drei Bytes der IP-Adresse muss sich von mindestens einem der anderen Nameserver unterscheiden.
<h2>Die Registrierung</h2>
Wenn die Vorbereitungen soweit getroffen wurden, ist die eigentliche Domainregistrierung recht einfach. Auch hierfür gibt es im Robot ein Formular, in das man nur noch die zuvor angelegten Handles und Nameserver eintragen muss.

[![Mailinator](images/2009/autodns2_domain-285x300.jpg)](images/2009/autodns2_domain.jpg)

Bevor der Auftrag an die Registrierungsstelle geschickt wird, werden die Nameserver-Einträge meistens automatisch überprüft. Sollte etwas nicht stimmen, kommt eine Fehlermeldung, die Domain wird nicht registriert und man hat die Möglichkeit nachzubessern. Alles in allem also ein todsicheres Ding!
<h2>Domain-Konnektierung</h2>
Jetzt haben wir uns also unsere Wunschdomain zum super Dumpingpreis registriert. Dank der angelegten Nameserver-Einträge, löst die Domain auch in die IP-Adresse unseres Servers auf. Jetzt muss man dem Webserver diese Domain nur noch bekanntmachen, sodass er die entsprechende Webseite ausliefern kann. <strong>Name-based Virtual Hosts</strong> ist hier das Stichwort. Entweder von Hand in die conf-Datei des Apachen oder eben durch eine Verwaltungssoftware wie <strong><a href="http://syscp.org">Syscp</a></strong> (TIPP! :)), Confixx oder Plesk anlegen lassen.

Wer keinen root-Zugriff hat, zum Beispiel bei einem Managed-Server, der muss die Domain als "externe Domain" über das Kunden-Webinterface anlegen. Viele Anbieter verlangen aber für eine externe Domain Gebühren, weshalb sich die Registrierung über einen Robot dann nicht mehr wirklich lohnt.