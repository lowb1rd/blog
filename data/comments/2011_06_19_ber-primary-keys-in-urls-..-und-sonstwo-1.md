---
date: 2011-10-22 17:57:34
name: Sebastian
www: http://www.sebastian-eiweleit.de
email: sebastian@sebastian-eiweleit.de
---

Wieso nicht mit uniqid eine ID erstellen. Damit wir aber weiter mit der normalen Datenbank arbeiten könnten, erstelle wir eine Aliastabelle wo wir der uniqid eine ID (Bestellung, Userid etc) zuordnen. Mit einem kleinen Query erfragen wir die eigentliche ID (die eben mit uniqid verknüpft worden ist). 

Vorteil: Man muss nicht die ganze Datenbankstruktur verändern.