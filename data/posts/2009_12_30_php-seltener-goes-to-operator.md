---
date: 2009-12-30 20:02:19
title: PHP: seltener "goes to" Operator ( -  - > )
tags: Webtechnik
slug: php-seltener-goes-to-operator
excerpt: PHP hat einen "goes to"-Operator. Oder doch nicht?

last_updated: 2012-01-07 21:35:25
---

PHP hat einen "goes to"-Operator. Die Integer-Variable $x geht dabei schrittweise Richtung Null. Beispiel:

    #!php@1
    <?php
    $x = 10;
    while ( $x --> 0 ) {
        echo $x . ' ';
    }
    ?>

Ausgabe:
    
    9 8 7 6 5 4 3 2 1 0

Sensationell. Im PHP-Manual wird dieser Operator nicht erwähnt. Warum? Weil es in Wirklichkeit zwei Operatoren sind. Geparst wird das ganze nämlich so:

    #!php@1
    <?php
    $x = 10;
    while ( $x-- > 0 ) {
        echo $x . ' ';
    }
    ?>

Und jetzt ist die Bedingung in der While-Schleife ein ganz normaler Post-Dekrement gefolgt von einem Vergleich. Das Ganze funktioniert also nur in die absteigende Richtung. Für die andere Richtung müsste man **++<** schreiben - sieht dann aber nicht mehr so schick aus.