---
date: 2010-03-14 21:26:57
title: Runde Ecken mit CSS und ohne Stress
tags: Webtechnik
slug: runde-ecken-mit-css-und-ohne-stress

last_updated: 2012-01-07 21:35:53
---

Es könnte alles so einfach sein. Runde Ecken sind mit der CSS3-Eigenschaft "border-radius" leicht zu realisieren. Das funktioniert in allen modernen Browsers (Opera, Firefox, Chrome und Safari). Nicht aber im Internet-Explorer.

Um auch dem IE runde Ecken beizubringen, habe ich bisher immer absolut positionierte Grafiken benutzt. Für einen einfachen Web-Entwickler wie mich stellt die Erstellung solcher "Eck-Grafiken" aber schon eine enorme Herausforderung dar. Gott sei Dank gibt es <a href="http://www.generateit.net/rounded-corner/">Generatoren</a> :). Außerdem sind solche Eck-Grafiken unflexibel, da sie bei jeder Farbänderung ausgetauscht werden müssen.

<a href="http://www.dillerdesign.com/experiment/DD_roundies/#lacking"><strong>DD_roundies</strong></a> nimmt sich diesem Problem an.  Es ist eine kleine (9KB) Javascript-Datei, die dem IE unter Zuhilfenahme von VML runde Ecken beibringt. Wenn man das Ganze dann per <a href="http://de.wikipedia.org/wiki/Conditional_Comments">IE-Conditional-Comment</a> einbindet erhöht sich die Ladezeit für die übrigen Browser nicht. Optimal!
<pre>&lt;!--[if IE]&gt;
&lt;script type="text/javascript" src="js/dd_roundies.js"&gt;&lt;/script&gt;

&lt;script type="text/javascript"&gt;
    DD_roundies.addRule('.border', 10, false);
    DD_roundies.addRule('.border-top', '10px 10px 0 0', false);
&lt;/script&gt;
&lt;![endif]--&gt;</pre>
Hier bekommen allen Elementen mit der Klasse "border" rundrum runde Ecken mit 10px Radius. Elemente mit der Klasse "border-top" bekommen nur oben links und oben rechts runde Ecken.

Für alle anderen Browser fügen wir die folgenden CSS3-Eigenschaften hinzu. Da CSS3 in manchen Rendering-Engines nur experimentell umgesetzt ist, müssen wir verschiedene Prefixe verwerden.

    #!css
    .border {
        border: 1px solid #f00;
        border-radius: 10px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        -khtml-border-radius: 10px;
    }
    .border-top {
        border: 1px solid #f00;
        border-top-left-radius: 10px;
        -moz-border-radius-topleft: 10px;
        -webkit-border-top-left-radius: 10px;
        -khtml-border-radius-topleft: 10px;
        border-top-right-radius: 10px;
        -moz-border-radius-topright: 10px;
        -webkit-border-top-right-radius: 10px;
        -khtml-border-radius-topright: 10px;
    }

Man beachte die vertauschte Reihenfolge bei -moz und -khtml im Vergleich zu -webkit und dem CSS3-Standard.

Ganz schön viel CSS für ein bisschen runde Ecken - aber immer noch bequemer als Grafiken!