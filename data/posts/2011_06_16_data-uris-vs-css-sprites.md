---
date: 2011-06-16 20:46:15
title: Data URIs vs. CSS-Sprites
tags: Webtechnik
slug: data-uris-vs-css-sprites

last_updated: 2012-01-07 21:35:52
---

![Google-Sprite](images/2011/google-sprite.png) 

<a href="http://css-tricks.com/css-sprites/">CSS-Sprites</a> sind eine elegante Möglichkeit um GET-Requests zu minimieren und daruch den Seitenaufbau zu beschleunigen. Dabei werden viele kleine Symbole in einer großen Grafik zusammengefasst und via CSS  immer nur der passende Ausschnitt der Grafik gezeigt. Eine andere Möglichkeit, GET-Requests zu sparen, besteht in der Verwendung des <a href="http://de.wikipedia.org/wiki/Data-URL">Data URI scheme</a>.

    #!html
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSU[...]FTkSuQmCC" alt="" />

Statt einer URL wird  direkt der Dateiinhalt Base64-Kodiert angegeben. Das Bild wird also mit dem HTML-Quelltext mitgeladen. Es ist kein separater GET-Request notwendig. Das funktioniert selbstverständlich analog in CSS-Dateien  und selbstverständlich nicht mit dem IE6/7. 

    #!css
    body { background: url('data:image/png;base64,iVBOR[..]QmCC'); }

Der fehlende IE6/7 Support mag ein Grund für die geringe Verbreitung des Data URI scheme sein. Erschwerend hinzu kommt die Tatsache, dass das Laden des (langen) Base64-Strings das weitere Rendering der Webseite zunächst blockiert. Die Base64-Darstellung ist im übrigen ca. 1/3 größer als die binäre und kann selbstverständlich nicht vom Browser gecached werden.