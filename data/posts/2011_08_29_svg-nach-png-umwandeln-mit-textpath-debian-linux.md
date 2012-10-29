---
date: 2011-08-29 22:06:54
title: SVG nach PNG umwandeln mit Textpath [Debian Linux]
tags: Allgemein
slug: svg-nach-png-umwandeln-mit-textpath-debian-linux

last_updated: 2012-01-07 21:35:52
---

Kurz erwähnt, da es mich selbst zu viel Zeit gekostet hat dieses eigentlich einfache Problem zu lösen:

Möchte man ein SVG in PNG (oder sonstige Pixelgrafikformate) umwandeln, versucht man das wohl zunächst mit [**ImageMagick**][imagick]. Das funktioniert solange auch prima, bis im SVG ein &lt;textpath&gt; vorkommt. Dieser verschwindet nämlich bei der Umwandlung in ein PNG mit imagick. Selbes Problem tritt mit meiner zweiten Wahl, dem Tool **rsvg** (librsvg2-bin), auf.

Mit **[inkscape][]** funktioniert die Umwandlung wie gewünscht. Es ist mit Sicherheit weniger verbreitet wie convert (imagick), aber immerhin ist es als Debian-Paket ("inkscape") verfügbar. Die Windowsversion ist im Übrigen ebenfalls einen Blick wert: ein wirklich brauchbares, quelloffenes Vektorgrafikprogramm!

[imagick]: //www.imagemagick.org/script/index.php
[inkscape]: //inkscape.org/?lang=de
