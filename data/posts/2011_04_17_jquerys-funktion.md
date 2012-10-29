---
date: 2011-04-17 20:14:49
title: jQuerys $-Funktion
tags: Webtechnik
slug: jquerys-funktion

last_updated: 2012-01-07 21:35:52
---

Wie wir alle wissen, werden Variablen in JavaScript **nicht** mit einem vorangestellten Dollarzeichen kenntlich gemacht. Insofern ist das Dollarzeichen für Javascript einfach nur das 36. Zeichen der ASCII-Tabelle. Und zudem ein gültiges Zeichen für Funktionsnamen (selbiges gilt neben allen Buchstaben und Zahlen auch für den Unterstrich).

    function Framework(id) {
        return document.getElementById(id);
    }
     
    var $ = Framework;

Jetzt ist `$()` ein Alias für die Funktion Framework(). Benutzt werden kann das jetzt schon wie bei jQuery:

    var nav = $('nav');

Um es Framework zu nennen, ist es wohl noch etwas dünn. Erweitern wir unser <em>Framework</em> also um eine handliche Datumsfunktion. <a href="http://api.jquery.com/jQuery.now/">Analog zu jQuery</a>, gibt diese Funktion die aktuelle Zeit zurück.

    Framework.now = function() {
        return (new Date).getTime();
    }
     
    $.now(); // 1301227135 (unix timestamp)

Das ist immer noch recht lasch! Weiter geht's: Mit <a href="http://www.javascriptkit.com/javatutors/proto.shtml">Javascripts Prototype Object</a> ist es möglich einer Klasse eine gewisse Eigenschaft/Methode zu geben, die automatisch alle Instanzen dieses Objektes ebenfalls haben. Damit kann man z.B. jedem JS-Array eine <a href="http://api.jquery.com/each/">each()-Methode</a> verpassen, oder alle HTML-Elemente um Funktionen erweitern. Letzteres nehmen wir mal als Beispiel:

    Element.prototype.addClass = function(className) {
        this.className += ' ' + className;
    }
    $('myDiv').addClass('foo');

Das Chaining von Methoden hat jQuery bekannt gemacht. Nichts leichter als das! Dazu muss die jeweilige Methode nur das Objekt selbst zurückgeben. Wir passen also unsere "addClass" Methode an und erstellen eine weitere um das Chaining zu testen.

    Element.prototype.addClass = function(className) {
        this.className += ' ' + className;
        <strong>return this;</strong>
    }
    Element.prototype.content = function(content) {
        this.innerHTML = content;
        return this;
    }
    $('myDiv').addClass('foo').content('bar');

Das ist jetzt natürlich alles nicht elegant gelöst und bestimmt auch nicht Cross-Browser tauglich (ich rede mit dir, IE!), verdeutlicht aber was so prinzipiell hinter einen Javascript-Framework steckt. [Hier](files/2011/jsframework.html) nochmal am Stück und mit <span style="text-decoration: line-through;">gehighlightetem</span> hervorgehobenem Syntax.

Merke: jQuery ist nur ein JavaScript-Framework. Mit jQuery kann man also auf keinen Fall mehr Dinge tun, als mit reinem Javascript selbst. Mit jQuery geht das meist nur einfacher und vor allem konsistenter zwischen verschiedenen Browser-Versionen.