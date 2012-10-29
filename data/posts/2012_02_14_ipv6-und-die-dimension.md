---
date: 2012-02-14 22:54:06
title: IPv6 und die Dimension
tags: Allgemein
slug: ipv6-und-die-dimension

last_updated: 2012-01-07 21:35:52
---

Ja, so ganz durch ist das Thema IPv6 hier noch nicht - aber bald, versprochen! Also, einen noch:

IPv4-Adressen werden knapp. So wie das Öl. Das weiß jeder. Bei Hetzner ist für Zusatz-Subnetze jetzt schon ein monatlicher Beitrag fällig und Hosteurope stellt unangenehme Fragen bei der Beantragung:

    Sehr geehrte Kundin,
    sehr geehrter Kunde,

    Ihren Antrag auf weitere IP-Adressen müssen wir zurückweisen.

    Die Verknappung an IPv4-Adressen erfordert es, dass die verbleibenden Adressen sparsam zugewiesen werden. 

    Sie verfügen bereits über X IP-Adressen, die nach derzeitigem Stand nicht voll genutzt werden. Zumindest lassen die Trafficstatistiken und der Content dies vermuten.

Dabei ist die Situation hier in Europa noch völlig entspannt, da "unsere" Vergabestelle, die RIPE, im Gegensatz zur APNIC noch einige /8 in petto hat.

Es gibt insgesamt knapp über 4 Milliarden IPv4-Adressen, aber noch lange nicht so viele Hosts im Internet. Vielmehr ist im Moment das Problem, dass [die wenigen "Großen" von damals](//en.wikipedia.org/wiki/List_of_assigned_/8_IPv4_address_blocks), großzügig wie man war, ganze /8 Netze zugeteilt bekommen haben. Ein /8, auch Class A Netz genannt, sind über 16 Millionen IP-Adressen - nur die wenigsten haben oder werden jemals so viele IP-Adressen brauchen. Das ist auch der Grund, warum der ein oder andere bereit ist, [ein paar IP-Adressen abzugeben](//diepresse.com/home/techscience/hightech/microsoft/644749/IPAdressen-knapp_Microsoft-kauft-Nachschub-ein).

Wie dem auch sei, IPv4 Adressen werden früher oder später ausgehen und IPv6 wird kommen. Der Weg dorthin ist lang und die Fortschritte hier in Deutschland eher zäh. So ist mir noch kein ISP bekannt, der seinen Kunden IPv6 anbietet. Selbst große Hoster wie Hosteurope bieten noch keinen IPv6-Support bei dezidierten Servern an. In 2011 betrug der Anteil an IPv6-Traffic gerade einmal 0.3%.

Damit man in wenigen Jahren nicht noch einmal so blöd dasteht, haben sich die schlauen Köpfe, die IPv6 erfunden haben, gedacht, wir machen die Adressen gleich 4 mal so lang, also statt 32 Bit ganze 128 Bit. Das hört sich jetzt nicht so sehr viel mehr an, ist es aber, wenn man bedenkt, dass sich mit jedem hinzugefügten Bit der vorhandene Adressraum **verdoppelt**. Das erinnert an das Schachbrett und die Reiskörner, und wir wissen alle wie diese Geschichte ausging..

Um die Anzahl der möglichen IPv6 Adressen zu nennen, empfiehlt sich aus Platzgründen die Exponentialschreibweise. Es sind: 3,4×10^38 Adressen, oder anders ausgedrückt: 22 Billiarden Adressen pro Quadratmillimeter Erde - Landmasse wohlgemerkt! Laut den [IPv6-Richtlinien](http://www.ripe.net/docs/ripe-512#assignment_size) ist das kleinste, dem Endkunden zuteilbare Netz ein /64, was immerhin stolze 18 Trillionen Adressen beinhaltet - das ist das gesamte v4-Internet im Quadrat!

Die Umstellung auf IPv6 wird Jahre dauern, im Moment sieht es eher nach Jahrzehnten aus. Der Enduser bekommt von dem allen nicht viel mit: IPv4 und IPv6 werden so lange in friedlicher Koexistenz leben. Schade nur, dass man die Vorteile von IPv6 während dieser *Übergangszeit* gar nicht ausspielen kann. Ich fürchte, ich muss für einen zusätzlichen SSL-vHost in 10 Jahren immer noch <em title="SNI mal außen vor">bei meinem Hoster um eine IPv4 betteln</em>, da man es sich lange Zeit nicht leisten kann, IPv6-only Webseiten zu betreiben. Ich wage sogar zu behaupten, dass ich den Tag an dem der letzte IPv4-Nameserver abgeschalten wird, nicht mehr erlebe. Das ist aber nur eine Momentaufnahme und die Entwicklung wird sich bei tatsächlicher Knappheit dramatisch beschleunigen. Hoffentlich.