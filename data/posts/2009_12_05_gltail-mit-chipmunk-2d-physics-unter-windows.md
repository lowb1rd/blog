---
title: glTail mit Chipmunk 2D Physics unter Windows
tags: Webtechnik
date: 2009-12-05 01:44:00
slug: gltail-mit-chipmunk-2d-physics-unter-windows

last_updated: 2012-01-07 21:35:26
---

<a href="http://www.fudgie.org/">glTail</a> ist eine in Ruby geschriebene OpenGL-Anwendung zur Visualisierung von Logfiles in Echtzeit. So sieht das aus:

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="425" height="344" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="src" value="http://www.youtube.com/v/RCa2sjyrUdQ&amp;color1=0xb1b1b1&amp;color2=0xcfcfcf&amp;hl=en_US&amp;feature=player_embedded&amp;fs=1" /><param name="allowfullscreen" value="true" /><embed type="application/x-shockwave-flash" width="425" height="344" src="http://www.youtube.com/v/RCa2sjyrUdQ&amp;color1=0xb1b1b1&amp;color2=0xcfcfcf&amp;hl=en_US&amp;feature=player_embedded&amp;fs=1" allowscriptaccess="always" allowfullscreen="true"></embed></object>

Nett! Das funktioniert praktisch mit jeder Logdatei (Webserver, Mailserver usw.) dank SSH auch auf entfernten Servern. Die Installation unter Windows ist leider nicht dokumentiert. Genauer gesagt ist gar nichts dokumentiert.

Um glTail unter Windows zum Laufen zu bringen ist etwas Handarbeit nötig. Da es mich selbst viel Nerven und Zeit gekostet hat, habe ich hier mal alle benötigten Schritte aufgeschrieben:

<!--more-->
<h2>Installation</h2>
<ol>
	<li>
        <h3>glTail herunterladen: <a href="http://github.com/Fudge/gltail">http://github.com/Fudge/gltail</a></h3>
        <p markdown="1">oben auf Download klicken, aktuell ist die Version 0.1.7<br />
        Die zur Zeit von diesem Tutorial aktuelle Version gibt's hier: [Fudge-gltail-e5b252d](files/2009/Fudge-gltail-e5b252d.zip)</p>
    </li>
	<li>
        <h3>Ruby installieren: <a href="http://rubyforge.org/projects/rubyinstaller/">Ruby One-Click Installer</a></h3>
        <p><a href="http://rubyforge.org/frs/download.php/47082/ruby186-27_rc2.exe">1.8.6-27 Release Candidate 2</a> vom 19.11.2008. Version ist im Prinzip aber egal.<br />
        "Enable Rubygems" Option beim Installieren aktivieren</p>
    </li>
	<li>
        <h3>Ruby: ruby-opengl installieren</h3>
        <p>ruby-opengl lässt sich, warum auch immer, unter Windows nicht kompilieren. Deshalb brauchen wir eine bereits kompilierte Version. Die gibts hier: <a href="http://rubyforge.org/frs/?group_id=2103">http://rubyforge.org/frs/?group_id=2103</a>
        Konkret brauchen wir die <a href="http://rubyforge.org/frs/download.php/52686/ruby-opengl-0.60.1-x86-mswin32.gem">ruby-opengl-0.60.1-x86-mswin32.gem</a>
        Die Datei muss nach dem Download wieder in *.gem umbenannt werden (nicht entpacken!)</p>
        <h3>RubyGems Package Manager starten:</h3>
<pre>Start -&gt; Ruby -&gt; RubyGems -&gt; RubyGems Package Manager</pre>
<strong>Im Package Manager folgenden Befehl ausführen:</strong>
<pre>gem install ruby-opengl-0.60.1-x86-mswin32.gem</pre>
<p>Vorher ins Verzeichnis wechseln, wo die zuvor heruntergeladene .gem-Datei liegt oder den Pfad entsprechend anpassen.</p>
    </li>
	<li>
<h3>Ruby gem: net-ssh &amp; file-tail installieren</h3>
<pre>gem install net-ssh
gem install file-tail</pre>
</li>
	<li>
<h3 markdown="1">Chipmunk.so herunterladen: [klick](files/2009/chipmunk.zip)</h3>
    <p>in dieses Verzeichnis entpacken: <em>C:\ruby\lib\ruby\site_ruby\1.8\i386-msvcrt\</em></p></li>
	<li>
<h3>glTail Anpassungen</h3>
<p>Die Datei "<em>gl_tail</em>" im bin-Verzeichnis braucht die Endung "<em>.rb</em>"</p></li>
</ol>

<h2>Konfiguration</h2>
glTail wird über YAML-Dateien konfiguriert. Das sind reine Textdateien. Wichtig ist hier nur die Einrückungen genau einzuhalten. glTail kann mit verschiedenen Configdateien benutzt werden. Die Configdatei wird immer beim Start von glTail mit übergeben. Die Beispieldatei "config.yaml" enthält bereits ein Beispiel für einen Apache-Webserver. Hier muss man nur Host/User/Passwort und den Pfad zur Logdatei anpassen.
<h3>SSH-Keys</h3>
Wenn man sich zu einem entfernten Server verbinden will, so empfiehlt es sich, dies über Public Key Authentication  zu tun. Auch das kann glTail von Hause aus. Statt:
<pre>password: topsecret</pre>
schreiben wir einfach:
<pre>keys: keys/key_server1</pre>
Dann erstellen wir im glTail-Root einen Ordner "keys". Darin sollte sich der Private-Key "key_server1" UND der Public-Key "key_server1.pub" befinden. Wichtig ist das beide Keys den selben Dateinamen haben und nur der Public-Key die Dateiendung ".pub" besitzt. In der Konfigdatei darf keine Dateiendung angegeben werden.
<h3>Tweaks</h3>
In der Configdatei können die minimalen und maximalen Kugelgrößen über folgende Parameter angepasst werden:
<pre>min_blob_size: 0.004
max_blob_size: 0.04</pre>
Ausprobieren ist angesagt..

Außerdem lässt sich das Abprallen der Kugeln am Rand und an einem Trichter einschalten
<pre>bounce: true</pre>
den "Bounce" kann man während glTail läuft auch mit der Leertaste an- und ausschalten.
<h2>glTail starten</h2>
Jetzt wird's Zeit glTail zu starten. Dazu öffnen wir die Windows CMD, wechseln ins glTail Verzeichnis und tippen:
<pre>bin\gl_tail config.yaml</pre>
<h2>glTail Fehlermeldungen / patches</h2>
<pre>undefined method `ord' for 32:Fixnum (NoMethodError)</pre>
Wenn diese oder ähnliche Fehlermeldungen erscheinen, müssen folgende Änderungen an glTail gemacht werden:
<ol>
	<li>
<h3>Datei "lib\gltail\font_store.rb" öffnen</h3>
Das ".ord" am Zeilenende in Zeile 38 - 40 muss entfernt werden. Vorher:
<pre>font_data[offset + y*256*3 + x*3 +0] = @font[c][y*8*3 + x*3 + 0].ord
font_data[offset + y*256*3 + x*3 +1] = @font[c][y*8*3 + x*3 + 1].ord
font_data[offset + y*256*3 + x*3 +2] = @font[c][y*8*3 + x*3 + 2].ord</pre>
nachher:
<pre>font_data[offset + y*256*3 + x*3 +0] = @font[c][y*8*3 + x*3 + 0]
font_data[offset + y*256*3 + x*3 +1] = @font[c][y*8*3 + x*3 + 1]
font_data[offset + y*256*3 + x*3 +2] = @font[c][y*8*3 + x*3 + 2]</pre>
</li>
	<li>
<h3>Datei "lib\gltail\engine.rb" öffnen</h3>
Zeile 144 ".ord" <strong>entfernen</strong>:
<pre>143 def key(k, x, y)
144    case k<strong>.ord</strong>
145        when 27 # Escape</pre>
Zeile 174 ".ord" <strong>entfernen</strong>:
<pre>173 end
174 puts "Keypress: #{k<strong>.ord</strong>}"
175 glutPostRedisplay()</pre>
</li>
</ol>
Danach sollte glTail ohne Probleme zum Server verbinden und die Log-Datei live visualisieren.
<h2>Logstalgia</h2>
Eine weitere Möglichkeit, Logfiles zu visualisieren bietet <strong><a href="http://code.google.com/p/logstalgia/">Logstalgia</a></strong>. Vorteil hier: Man kann es mit einer Log-Datei füttern, welche dann abgespielt wird. Zudem gibts ein fertiges Win32 binary. Kein gepatche notwendig.

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="425" height="344" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="src" value="http://www.youtube.com/v/BYYX-h4-dpM&amp;hl=de_DE&amp;fs=1&amp;" /><param name="allowfullscreen" value="true" /><embed type="application/x-shockwave-flash" width="425" height="344" src="http://www.youtube.com/v/BYYX-h4-dpM&amp;hl=de_DE&amp;fs=1&amp;" allowscriptaccess="always" allowfullscreen="true"></embed></object>

Auch ganz nett!