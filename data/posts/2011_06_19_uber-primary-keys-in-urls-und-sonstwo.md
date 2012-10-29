---
date: 2011-06-19 15:58:02
title: Über Primary-Keys in URLs .. und sonstwo
tags: Webtechnik
slug: uber-primary-keys-in-urls-und-sonstwo
featured: true

last_updated: 2012-01-07 21:35:52
---

Ein Primary-Key identifiziert eindeutig ein Tupel (Zeile/Reihe/Row) in einer relationalen Datenbank. Im trivialen Fall ist das eine Integer-Spalte mit <em>auto_increment</em> - wenn wir von MySQL reden, wie so oft hier. Eine fortlaufende Nummer also. Diese Nummer wird in Webanwendungen oft direkt an den User weitergegeben. Zum Beispiel als Teil der URL oder als Datensatznummer (z.B. Bestellnummer in einem Online-Shop).

<h2>Beispiele</h2>
<h3>1. Online-Shop: $kunde erhält seine Bestellbestätigung per E-Mai mit der Bestellnummer 27.</h3>
<p style="padding-left: 30px;">Das wäre mir (als Shop-Betreiber) ein Dorn im Auge, da es Rückschlüsse auf die Anzahl der bisherigen Bestellungen zulässt. Das lässt sich noch relativ leicht beheben, indem man die auto_increment-Spalte nicht bei 1 sondern bei einer höheren Zahl beginnen lässt (<em>ALTER TABLE tbl AUTO_INCREMENT = 1000;</em>). Doch was, wenn der Kunde zwei Wochen später erneut bestellt, und eine nur wenig höhere Bestellnummer erhält? Dies lässt wiederum Rückschlüsse auf die Häufigkeit von Bestellungen im Shop zu. Daran ändert auch der Beginn bei höheren Zahlen nichts.</p>

<h3>2. URL: http://domain.com/user/123</h3>
<p style="padding-left: 30px;">Wir nehmen an, hinter dieser URL verbirgt sich ein öffentliches Benutzerprofil. Auch hier selbes Problem wie oben (Aussage über Quantität). Zudem kann man hier, aufgrund der fortlaufenden Nummer, die "Umgebung erkunden", andere Benutzerprofile erraten oder per Script sämtliche Userprofile crawlen (for i=1; i&lt;999;i++). Hier bräuchte man also im Idealfall eine zufällig fortlaufende Zahlen/Nummernkombination, mit genügend "Lücken" um das direkte erraten anderer Profile zu <span style="text-decoration: line-through;">verhindern</span> erschweren. Dennoch sollte die User-ID natürlich so kurz wie möglich sein.</p>
Das dürfte ja alles soweit bekannt sein. In den meisten Szenarien spielt das auch überhaupt keine Rolle. In einem Blog oder Forum z.B. dürfen die Post-IDs gerne den Primary-Keys entsprechen. Was aber, wenn der paranoide Webmaster Rückschlüsse nicht (oder sagen wir besser: nur erschwert) zulassen will?
<h2>Anforderungen an eine ID-Verschleierung</h2>
- wenig Overhead (nicht länger als nötig)
- leicht reversibel (ohne Datenbankabfrage, ohne Speicherung der verschleierten ID in extra DB-Spalte)
- Erhöhung des PK um 1 soll große Änderung der verschleierten ID bewirken
- kollisionsfrei, zu 100%
- optional: Spielraum/Lücken um das Erraten von weiteren IDs zu erschweren
<h2>Mögliche Lösungen zum Verbergen der ID</h2>
<h3>GUID</h3>
<a href="http://de.wikipedia.org/wiki/Globally_Unique_Identifier">GUID</a>s haben mit Sicherheit Ihre Daseinsberechtigung. In den meisten Fällen sind sie aber einfach nur des Guten zu viel. Von der Performance auf Datenbankseite möchte ich jetzt gar nicht reden. Auch nicht über die Tatsache, dass GUID in URLs schrecklich aussehen. Das Ziel muss sein, so wenig wie möglich Overhead zu erzeugen. GUID: no-go!
<h3>Hashen des PKs</h3>
Ein Hash resultiert immer in einem String mit fester Länge. Selbst bei sehr kleinen Primary-Keys, wäre der Hash immer gleich lang. Welch' Verschwendung! Außerdem sind Hashs nicht kollisionsfrei*, und sie lassen sich auch nicht zurückrechnen. D.h. man müsste den Hash zusätzlich zum Primary-Key speichern (noch mehr Overhead).

[* zugegeben, in diesem Ganzzahlbereich um den es hier geht wohl schon]
<h3>Basis</h3>
Man kann den Primary-Key einfach in einer anderen Basis darstellen, Base32 od. 64 zum Beispiel. Wenn man darauf besteht, dass die ID nach wie vor aus Zahlen bestehen soll, hat man Pech. Auch doof ist, dass eine Erhöhung des PK um 1 nur eine sehr kleine Änderung der verschleierten ID bewirkt. Beispiel: 12345(base10) = C1P (base32), 12346(base10) = C1Q(base32)).
<h3>Random-ID</h3>
Die Idee dahinter ist, im Vorfeld einen Pool an Random-IDs zu erzeugen (in einer extra DB-Tabelle). Ein UNIQUE-Key verhindert Duplikate. Beim Anlegen eines neuen Datensatzes, wird aus der Kandidaten-Tabelle dann eine Zufallszahl geholt und mit dem PK verknüpft. Das Ganze geht auch ohne vorheriges Erstellen der Kandidaten-Tabelle. Endlos-Schleife und race conditions nicht ausgeschlossen.
<h3>ID Verschleiern</h3>
Alles "Bekannte" scheint hier nicht zum Erfolg zu führen. Es gibt unendlich viele Methoden, durch logische Verknüpfung (<a href="http://de.wikipedia.org/wiki/Kontravalenz">XOR</a> in erster Linie) und sonstige mathematische Funktionen, Zahlen zu verschleiern. Eine einfache, auf diese Problematik passende, möchte ich hier vorstellen.

Man nehme <strong>einmalig</strong> eine zufällige Zahl ("secret"), die mindestens so groß ist, wie die höchste Primary ID. Der längste PK kann, wenn wir ein MySQL SMALLINT unsigned annehmen, max. 65535 sein. Unsere Zufallszahl: 99565. Diese Zahl ist systemweit immer gleich - wird also für alle späteren Ver- und Entschleierungen verwendet.

Das eigentliche Prozedere ist einfach: Die binäre Präsentation des PK wird umgekehrt und mit der Zufallszahl mit XOR verknüpft. Von der Zufallszahl verwenden wir nur so viele binäre Stellen, wie unser PK hat. Da durch das Umkehren und XORen führende Nullen entstehen können, stellen wir immer eine binäre 1 dem  umgekehrten Binärstring voran - sonst kommt es zu Kollisionen. Beispiel:

    PK 12      =  1100
    Flip +1    = 10011
    99565      = _1100[0010011101101] (das ist unser Secret)
    XOR        = 11111 = 31

    PK 13      =  1101
    Flip +1    = 11011
    99565      = _1100[0010011101101] das ist unser Secret)
    XOR        = 10111 = 23

<h2>PHP-Code</h2>

    #!php@1
    function obfuscateID($pk, $secret, $margin = 5) {
        if ($margin) {
            $pk = base_convert($pk, 10, 10-$margin);
        }

        $pk = bindec(1 . strrev(decbin($pk)));
        $pk ^= bindec(substr(decbin($secret), 0, strlen(decbin($pk))-1));

        return $pk;
    }

    function deObfuscateID($pk, $secret, $margin = 5) {
        $pk ^= bindec(substr(decbin($secret), 0, strlen(decbin($pk))-1));
        $pk = bindec(substr(strrev(decbin($pk)), 0, -1));

        if ($margin) {
            $pk = base_convert($pk, 10-$margin, 10);
        }

        return $pk;
    }

Vorsicht mit bindec() und decbin() auf 32bit-Systemen: hier sind max. Umwandlungen bis zu 4,294,967,295 möglich. Wer auf 32bit mehr braucht, muss sich diese Funktionen auf Stringbasis selber basteln. Anregung dazu findet man wie so oft in den Kommentaren des PHP-Manuals.
<h2>Sicherheitsmarge</h2>
Um das Erraten von Nachbarn zu erschweren, können die Lücken zwischen den PKs vergrößert werden. Das passiert mit dem Parameter $margin. Dieser muss ebenso wie das $secret systemweit immer gleich sein. Beim Zurückwandeln muss also das selbe Margin angegeben werden, wie für die Verschleierung. $margin kann ein Wert zwischen 0 und 9 haben und basiert auf einer simplen Basisumwandlung (umgerechnet wird von Basis 10 in Basis 10-$margin).
<h2>Ausgabe</h2>
Bis hierhin war's ganz schön trocken. Höchste Zeit für ein wenig Praxis! Der folgende PHP-Schnipsel in Kombination mit den obigen Funktionen..

    #!php
    $secret = 99565;
    for ($i = 1; $i &lt; 1000; $i++) {
        $camo = obfuscateID($i, $secret);  
        echo "$i: $camo <br />";
    }

ergibt folgende Ausgabe:
<pre>[..]
524: 7960
525: 15439
526: 11343
527: 13391
528: 9295
[..]
991: 19678
992: 29406
993: 21214
994: 25310
995: 30430
[..]</pre>
Äußerst nett, wie ich finde!
<h2>Zahlen oder Alphanum?</h2>
Um Platz zu sparen, könnte man die nach wie vor aus Zahlen bestehende, verschleierte ID in eine höhere Basis (32, 64) umwandeln. Auch mit dieser Umwandlung kann man relativ einfach für (weitere) Lücken sorgen. Da dies aber gefühlt ohnehin schon mein längster Blogbeitrag ist (in jedem Fall was die investierte Zeit angeht), lassen wir das mal so offen stehen bzw. überlasse ich es dem Leser als Übung :)