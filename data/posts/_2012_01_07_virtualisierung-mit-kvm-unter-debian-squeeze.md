---
date: 2012-01-07 20:34:28
title: Virtualisierung mit KVM unter Debian Squeeze
tags: Linux
slug: 

last_updated: 2012-01-07 20:34:28
---

KVM ist der neue Star am Virtualisierungshimmel unter Linux. Die Begrifflichkeiten sind anfangs etwas verwirrend und der ein oder andere Stolperstein liegt auch herum. Deshalb dieser Blog-Eintrag als "Notiz an mich selbst".

KVM ist anders als XEN Bestandteil des Linux-Kernels. Das macht die Installation sowie spätere Updates so ziemlich frickelfrei. Genau das was wir wollen. 

Ich beschreibe hier die (wenigen) Schritte, die nötig sind, um ein Windows XP unter Debian Squeeze mit KVM zu virtualisieren. Das ganze funktioniert headless, also rein über die shell. Die grafische Windows-Installation machen wir über VNC, welches ebenfalls mit KVM kommt. Als Netzwerkschnittstelle verwenden wir einmal NAT sowie bridged networking.

Installation
--------------
Zunächst muss sichergestellt werden, dass die verwendete CPU Virtualisierung mit KVM unterstützt. Das ist der Fall, wenn der folgende Befehl etwas ausgibt:

    egrep '^flags.* (vmx|svm)' /proc/cpuinfo

Gegebenenfalls muss die Virtualisierung im BIOS aktiviert werden (das ist sie nämlich bei default BIOS-Einstellungen nicht).

Danach installieren wir die benötigten Pakete:

    aptitude install kvm libvirt-bin virtinst bridge-utils

`bridge-utils` brauchen wir nur für bridged networking


Bridged networking
------------------
Wollen wir in den Gästen bridged networking verwenden, muss die `/etc/network/interfaces` angepasst werden:

    auto  eth0
    iface eth0 inet manual

    auto br0
    iface br0 inet static
        bridge_ports eth0
        bridge_fd 9
        bridge_hello 2
        bridge_maxage 12
        bridge_stp off
        # ab hier wie gehabt
        address 192.168.0.1
        # usw.


Gast erstellen
--------------
Zunächst legen wir eine VM mit NAT an. Dazu muss das KVM-Netzwerk erst gestartet werden:

    sudo virsh
    net-start default
    STRG+D

Außerdem muss in der `/etc/sysctl.conf` folgender Eintrag aktiviert sein:

     net.ipv4.ip_forward = 1

Mit `virt-install` lässt sich nun ganz einfach eine neue Konfigurationsdatei anlegen: 

    virt-install --name WindowsXP --ram 512 --disk path=/tmp/winxp.img,size=6 --network network:default --vnc --os-variant winxp --cdrom /tmp/xpcd.img --boot cdrom


virt-install versucht auch gleich die VM zu starten was aber auf der Konsole nicht so ganz klappt.

    Cannot open display:

Mit STRG+C gelangen wir zur Konsole zurück.

Der VNC-Server lauscht nur auf localhost. Um das zu ändern muss die XML-Datei bearbeitet werden. Dies sollte nicht direkt sondern nur über virsh gemacht werden:

    sudo virsh
    shutdown WindowsXP
    edit WindowsXP

Folgende Zeile:

    <graphics type='vnc' port='-1' autoport='yes' keymap='en-us'/>

anpassen auf:

    <graphics type='vnc' port='5900' autoport='no' listen='192.168.0.1' keymap='de'/>

nach dem Speichern und Schließen der Datei (:wq) können wir die VM wieder starten

    start WindowsXP

Damit lauscht nach dem Start der VM auf 192.168.0.1:5900 ein VNC-Server (ohne Passwort!)

<a href="http://neunzehn83.de/blog/wp-content/uploads/2012/01/kvm-vnc-winxp.gif"><img src="http://neunzehn83.de/blog/wp-content/uploads/2012/01/kvm-vnc-winxp.gif" alt="" title="kvm-vnc-winxp" width="728" height="451" class="alignnone size-full wp-image-1042" /></a>


VM kontrollieren
---------------- 
Von einem Rechner mit grafischer Oberfläche kann nun mit einem beliebigen VNC-Client Die WindowsXP-Installation durchgeführt werden.

Mit `virsh` können wir unsere virtuellen Maschinen verwalten (Starten, Stoppen, Löschen, ..)

    sudo virsh
    list --all
    shutdown WindowsXP
    destroy WindowsXP

`shutdown` fährt die VM herunter, `destroy` schaltet sie sofort aus.

Bridged Networking
-----------------
Für bridged networking kann ein weiteres `<interface>` innerhalb von `<devices>` hinzugefügt werden, oder das vorhandene ersetzt werden:

<pre>
sudo virsh
edit WindowsXP
</pre>

<pre>
&lt;interface type='bridge'>
  &lt;mac address='52:54:00:d6:a9:60'/>
  &lt;source bridge='br0'/>
  &lt;address type='pci' domain='0x0000' bus='0x00' slot='0x03' function='0x0'/>
&lt;/interface>
</pre>

Windows Remotedesktop von außerhalb
-----------------------------------
Da die VM im NAT läuft kommen wir "von außen" nicht auf den RDP-Port 3389. Mit `rinetd` lässt sich der Port aber von außen leicht ins NAT weiterleiten:

    aptitude install rinetd
    vi /etc/rinetd.conf
    echo "192.168.0.1 3389 192.168.122.13 3389" >> /etc/rinetd.conf
    /etc/init.d/rinetd restar