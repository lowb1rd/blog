---
date: 2012-02-06 21:54:08
title: Neuerungen in PHP 5.4
tags: Webtechnik
slug: neuerungen-in-php-5-4

last_updated: 2012-01-07 21:35:52
---

PHP 5.4 ist [schon bei RC7](https://svn.php.net/repository/php/php-src/tags/php_5_4_0RC7/NEWS), steht also sozusagen vor der Türe und schafft es wohl auch in das nächste Debian stable alias Wheezy. Wie auch schon bei 5.2->5.3 kommen einige nette und über Jahre in den PHP-RFC diskutierten Features hinzu. Die meisten davon sind eigentlich nur Abkürzungen, sparen also die ein oder andere Zeile Code ein. Dennoch gehören diese zu den meistgelobten Änderungen von PHP 5.4. Ich finde, zurecht! Als da wären:

Short Array Syntax
------------------
Javascript-Style Array notation

    #!php@0
    <?php
    $a = [1, 2, 3];
    $b = ['foo' => 'orange', 'bar' => 'apple'];
    ?>

Function Array Dereferencing
----------------------------
Ist der Rückgabewert einer Funktion ein Array, so kann direkt mit eckiger Klammer auf die Elemente zugegriffen werden.

    #!php@0
    <?php
    function fruits() {
      return ['apple', 'banana', 'orange'];
    }
    echo fruits()[0]; // Outputs: apple
    ?>

Short Open Tag echo
-------------------
`<?=`  ist jetzt immer, also auch bei ausgeschalteten short_open_tags verfügbar. Yay!

Instance Method Call
--------------------
In PHP 5.4 ist es möglich, direkt nach Erzeugung eines Objektes mit dem `new` Keyword zu chainen. Hier hat man bei PHP5.3 zunächst das Objekt in einer Variable speichern müssen und konnte dann von dort aus chainen.

    #!php@0
    <?php
    $obj = (new foo)->bar();
    ?>

Außerdem: [Traits](http://php.net/traits), schneller(er) @-Operator, default charset UTF-8, kein safe_mode/register_globals/magic_quotes mehr. 