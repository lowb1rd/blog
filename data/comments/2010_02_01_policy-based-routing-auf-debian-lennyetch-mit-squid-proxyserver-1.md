---
date: 2012-06-21 13:51:22
name: JP
www: 
email: cysk@gmx.de
---

Interessant wäre auch die Variante, dass die Router nicht externe Geräte sind, sondern die externen Connects über Interfaces auf der gleichen Maschine vorhanden sind. Um es dann noch interessanter zu machen; ein externes Interface mit einem statischen IP-Netz und eines mit einem dynamischen IP-Netz (z.B. ein Kabel-Modem).

Das versuche ich gerade hinzubekommen, daher Frage ich :-)

Es gibt also ein internes Interface eth0, dass ein internes Netz bereitstellt, und auf IP 192.168.0.1 lauscht, sowie ein virtuelles Interface eth0:1 das auf 192.168.0.2 lauscht.

Außerdem die beiden externen Interfaces eth1 (Provider 1, statisches IP-Setup), sowie eth2 (Provider 2, IP via DHCP).

Ziel: Wird 192.168.0.1 als Gateway verwendet, dann Routing über Provider 1. Wenn 192.168.0.2 angefragt wird, dann Routing über Provider 2.

Frage:
Wie bekommt man es hin, dynamisch die Route über eth2 (Uplink mit dynamischer IP) zu setzen, für Anfragen die über das Interface mit der IP 192.168.0.2 rein kommen, ohne dass man iptables bemühen muss?

Folgendes dürfte also nicht funktionieren, da ich die IP-Adresse des Gateways an dieser Stelle nicht kenne.

auto eth0:1
iface eth0:1 inet static
        address 192.168.0.2
        netmask 255.255.255.0
        network 192.168.0.0
        broadcast 192.168.0.255
        post-up ip route add default via UNBEKANNTE_IP_WEIL_DHCP dev eth0:1 table provider2
        post-up ip rule add from 192.168.0.2 table provider2
        pre-down ip route del default via UNBEKANNTE_IP_WEIL_DHCP dev eth0:1 table provider2
        pre-down ip rule del from 192.168.0.2 table provider2


Lässt sich dies nur mit einem bash-script lösen, dass bei "post-up" oder "pre-down" entsprechend den per DHCP bereitgestellten Gateway aus der ip-config des Interfaces evaluiert?
Schön wäre es das einfach mit Board-Mitteln von iproute hinzubekommen.

Ne Idee?