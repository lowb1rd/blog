---
date: 2011-05-09 17:57:44
title: reCAPTCHA Tricks ;)
tags: Allgemein
slug: recaptcha-tricks

last_updated: 2012-01-07 21:35:52
---

![recaptcha](images/2011/recaptcha.jpg)

[reCAPTCHA](//www.google.com/recaptcha) ist ein sehr verbreiteter Captcha-Service der von Google betrieben wird. Viele Webseiten zeigen via reCAPTCHA-Webservice diese Captchas an, um Ihre menschlichen Besucher von Bots zu unterscheiden. Das Besondere: mit reCAPTCHA werden Bücher digitalisiert, bzw. OCR-Ergebnisse überprüft. Zur Lösung muss der Benutzer zwei Wörter eingeben, wovon aber nur eines bei reCAPTCHA bekannt ist. Beide Wörter stammen aus einem Buch-Scan ([Google Books](//books.google.de/) - aber auch andere). Das bekannte Wort wurde bereits durch reCAPTCHA-Benutzer bestätigt oder hat grundsätzlich eine hohe OCR-Genaugigkeit - das unbekannte hat eine niedrige Genauigkeit.

Beide Wörter werden zusätzlich verformt. Früher wurden auch verschiedene Objekte oder Striche über die Texte gelegt. Zur erfolgreichen Lösung reicht die Übereinstimmung des bekannten Wortes. Die Eingaben zum anderen Wort werden lediglich erfasst und bei entsprechender Häufung dazu verwendet das OCR-Ergebnis zu korrigieren bzw. zu bestätigen.

Das erklärt auch, warum bei reCAPTCHA teilweise wirre Worte erscheinen.

![recaptcha](images/2011/recaptcha.png)

In diesem Fall genügt es einfach das zweite Wort einzugeben. Das erste kann frei erfunden werden. Captcha gelöst! Wer also nicht beim Digitalisieren behilflich sein will, der hält sich nicht lange mit dem entziffern auf, sondern gibt irgendetwas ein - das bekannte Wort (und nur darauf kommt es an) ist *meist* ohne viel Rätseln lesbar.