---
date: 2010-02-15 17:59:46
title: SPAM im Griff mit Catch-All E-Mail-Adresse und Subdomain
tags: Allgemein
slug: spam-im-griff-mit-catch-all-e-mail-adresse-und-subdomain
featured: true

last_updated: 2012-01-07 21:35:53
---

Die meisten web2.0-Anwendungen verlangen eine Anmeldung, und somit auch eine E-Mail-Adresse, bevor man in den Genuss des kostenlosen Dienstes kommt. Eine <a href="http://mailinator.com">Wegwerf-E-Mail-Adresse</a> bietet sich hier nicht immer an. Schließlich will man später eventuell von Updates erfahren. So wird die verwendete E-Mail-Adresse früher oder später zugespammt. Doch welcher der vielen Anbieter, bei denen diese E-Mail-Adresse registriert ist, hat meine Adresse an Spammer verkauft? Oder anders gefragt:
<h2>Wer spammt mich hier eigentlich zu?</h2>
Wenn ein und dieselbe Adresse für mehrere Dienste benutzt wird, lässt sich das leider nicht herausfinden. Das Anlegen einzelner E-Mail-Adressen für jeden Dienst ist auch mehr als umständlich. Deshalb richten wir eine <a href="http://de.wikipedia.org/wiki/Catch-All">Catch-All</a> E-Mail-Adresse ein. Damit landen alle E-Mails einer (Sub-)Domain, die an einen nicht existierenden Account gerichtet sind, in einem bestimmten Postfach.

Catch-All auf der Hauptdomain ist generell eine schlechte Idee. Hier ist das Grundrauschen von vornherein viel höher. Deshalb richten wir den Catch-All nur auf einer speziellen Subdomain ein. Zum Beispiel <strong>mail.example.org</strong> mit <strong>catchall@mail.example.org</strong> als POP3/IMAP-Account.

Bei Anmeldung an einer Webseite verwenden wir den Namen der selbigen als <em>local-part</em> unser Mailadresse. Beispiel: <strong>facebook</strong>@mail.example.org oder <strong>myspace</strong>@mail.example.org. Dank Catch-All laden all diese Mails in unserem catchall@-Account.

Nun lässt sich ganz leicht feststellen, wo der Spam denn herkommt.  Das hilf einem jetzt vielleicht auch nicht unbedingt weiter, aber zumindest lässt sich nun der gesamte Spam anhand der Empfänger-Adresse blockieren. Zum Beispiel mit einer einfachen [Sieve-Filterregel](2009/12/07/sieve-mailfilter-unter-debian-lenny-mit-syscp-und-dovecot.html). So sollte das Konto auch über längere Zeit sauber bleiben.