---
date: 2009-12-10 16:49:02
title: .tel-Domains
tags: Allgemein
slug: tel-domains

last_updated: 2012-01-07 21:35:53
---

Neue Top-Level-Domains gibt es immer mal wieder. <em>.tel</em> ist die jüngste, <em>.post</em> wird wohl die nächste werden. Die .tel-Domain unterscheidet sich aber von allen bisherigen generischen TLDs.

Alle Informationen, die einer .tel-Domain zugeordnet sind, werden nicht auf einem Webspace gespeichert, sondern direkt im DNS. Es gibt bei .tel-Domains also kein Speicherplatz ("Web-Content") und damit auch kein individuelles Layout. Außerdem sind keine E-Mail-Adressen möglich. Die Daten werden über ein einheitliches Webinterface verwaltet. Die URL zum Webinterface und die Zugangsdaten bekommt der Domaininhaber vom Registrar automatisch per E-Mail.

Suchmaschinen können die im DNS abgelegten, strukturierten Daten sehr gut indizieren. Deswegen kommen .tel-Domains auch recht oft unter die ersten Suchergebnisse.

So sieht eine reine DNS-Abfrage/Antwort mit dig oder nslookup aus:

<pre>$ dig any emma.tel
emma.tel.  3600    IN      SOA     d0.cth.dns.nic.tel. cth-support.support.nic.tel. 15 10800 3600 2592000 60
emma.tel.  86400   IN      A       194.77.54.2
emma.tel.  3600    IN      NS      s0.cth.dns.nic.tel.
emma.tel.  3600    IN      NS      t0.cth.dns.nic.tel.
emma.tel.  3600    IN      NS      a0.cth.dns.nic.tel.
emma.tel.  3600    IN      NS      n0.cth.dns.nic.tel.
emma.tel.  3600    IN      NS      d0.cth.dns.nic.tel.
emma.tel.  60      IN      TXT     "Emma Davis. Here is my new address:\010100 5th Avenue, New York, NY 10011."
emma.tel.  60      IN      TXT     ".tkw" "1" "bi" "" "jt" "Graphic Designer"
emma.tel.  60      IN      TXT     ".tkw" "1" "hi" "Salsa Dancing"
emma.tel.  60      IN      TXT     ".tkw" "1" "nl" "" "fn" "Emma" "ln" " Davis"
emma.tel.  60      IN      TXT     ".tsm" "1" "pddx" "1"
emma.tel.  60      IN      LOC     51 25 35.812 N 0 7 54.610 W 0.00m 10m 2m 2m
emma.tel.  60      IN      NAPTR   100 100 "u" "E2U+voice:tel+x-mobile" "!^.*$!<strong>tel:+16468889999</strong>!" .
emma.tel.  60      IN      NAPTR   100 101 "u" "E2U+voice:tel+x-work" "!^.*$!<strong>tel:+12125551234</strong>!" .
emma.tel.  60      IN      NAPTR   100 102 "u" "E2U+email:mailto" "!^.*$!mailto:<strong>emma@aol.com</strong>!" .
emma.tel.  60      IN      NAPTR   100 103 "u" "E2U+x-voice:skype" "!^.*$!skype:emma123!" .
emma.tel.  60      IN      NAPTR   100 104 "u" "E2U+web:http" "!^.*$!<strong>http://myspace.com/emadavis</strong>!" .</pre>