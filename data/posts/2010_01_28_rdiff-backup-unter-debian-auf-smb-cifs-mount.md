---
date: 2010-01-28 22:35:05
title: Rdiff-backup unter Debian auf SMB/CIFS-Mount
tags: Webtechnik
slug: rdiff-backup-unter-debian-auf-smb-cifs-mount

last_updated: 2012-01-07 21:35:53
---

Für inkrementelle Backups unter <em>Debian Lenny</em> kommt man an <a href="http://rdiff-backup.nongnu.org/"><strong>rdiff-backup</strong></a> wohl nicht vorbei. Es erstellt Rückwärts-Inkremente -- der aktuelle (neuste) Backup-Stand liegt also jederzeit als einfache Datei vor. Vorherige Backup-Stände kann man aus dem aktuellen Stand und den Rückwärts-Deltas wiederherstellen. Das funktioniert entweder über die Kommandozeile oder mit einem Web-Interface wie z.B. <a href="http://www.rdiffweb.org/wiki/index.php?title=Main_Page">rdiff-web</a>.

Das Ganze funktioniert prinzipiell auch auf per SMB oder CIFS gemounteten Shares, zum Beispiel auf eine NAS-Box. rdiff-Backup kümmert sich sogar um das Quoten von Dateinamen, wenn das gemountete Filesystem im Gegensatz zum ext3-Filesystem des Lenny-Servers <strong>nicht</strong> case-sensitive ist. Allerdings funktioniert das erst so halbwegs reibungslos ab rdiff-backup 1.2.5 - für Etch ist also selber kompilieren angesagt.

In meinem Fall führte ein Backup auf die per SMBFS gemountete NAS aber zu einem komischen Quoting-Schema (<em>"A-Z-"*/:&lt;&gt;?\\|</em><em>;"</em>). Das resultierte dann in langsamen Backups und rdiff-web wollte auch nicht mehr funktionieren.
<h2>Loopback Tricks</h2>
<a href="http://nst.sourceforge.net/nst/docs/user/ch04s04.html">Diese Anleitung</a> brachte mich dann auf eine Idee, mit der man die ganzen SMB-Eigenheiten umgehen kann: Man erstellt eine große Datei auf der NAS-Box, erstellt in dieser Datei ein ext3-Dateisystem und mountet es als Loopback-Device. Das Backup erfolgt dann direkt auf diesen ext3-mount. So geht's:
<pre>dd if=/dev/zero of=/mnt/nas/backup.ext3 bs=1M count=100
mkfs.ext3 /mnt/nas/backup.ext3
mount /mnt/nas/backup.ext3 -t ext3 -o,sync,loop,rw,noatime /mnt/backup</pre>

Unter <strong>/mnt/nas</strong> ist die NAS-Box gemountet. Darauf erstellen wir die Datei <strong>backup.ext3</strong> (100MB in diesem Beispiel). Das ext3-Dateisystem in dieser Datei mounten wir nach <strong>/mnt/backup</strong>.

Der Performance hilft das jetzt nicht unbedingt...
<h2>Wir messen nach:</h2>
<h3>Davor:</h3>
<pre>--------------[ Session statistics ]--------------
 StartTime 1232652246.00 (Thu Jan 22 20:24:06 2009)
 EndTime 1232652365.80 (Thu Jan 22 20:26:05 2009)
 <strong>ElapsedTime 119.80 (1 minute 59.80 seconds)</strong>
 SourceFiles 4670
 SourceFileSize 16749169 (16.0 MB)
 ...
--------------------------------------------------</pre>
<h3>Danach:</h3>
<pre>--------------[ Session statistics ]--------------
 StartTime 1232652246.00 (Thu Jan 22 20:24:06 2009)
 EndTime 1232652365.80 (Thu Jan 22 20:26:05 2009)
<strong> ElapsedTime 48.20 (48.20 seconds)</strong>
 SourceFiles 4670
 SourceFileSize 16749169 (16.0 MB)
 ...
 --------------------------------------------------</pre>

Das war ein initiales Backup einer Dokuwiki-Installation. Ohne Quoting ist das ganze mehr als doppelt so schnell. Kann natürlich je nach Größe des Backup-Verzeichnisses, inkrementellen Backups oder Restores abweichen :)
<h2>Unterm Strich ---------</h2>
<em>Unterm Strich</em> ist das Loopback-Device für mich die eindeutig bessere Lösung: Das Backup-Repo bleibt schön sauber, da nichts gequotet werden muss und alle SMB-Bugs in rdiff-backup sind ein für alle Mal Geschichte. Hardlink-Support gibts obendrein dazu. Leider kann man den letzten Backup-Stand nicht mehr direkt auf der NAS (von einem Windows-Rechner per Freigabe) einsehen. Hierzu muss man das gemountete ext3-Filesystem erneut mit Samba sharen. Außerdem muss man den Backup-Platz vorher belegen und die Performance ist wohl alles andere als optimal - aber irgendwas ist ja immer!