---
date: 2011-01-05 14:35:27
name: neunzehn83
www: 
email: mail@neunzehn83.de
---

Mit PHP wird auf die Webserver-Umgebung zugegriffen. Das ist immer möglich. Die Frage ist, ob man Umgebungsvariablen z.B. mit einer .htaccess setzen kann.

Und ob das beim Webhostinganbieter funktioniert oder nicht, ist eigentlich egal. Die ENV-Variable soll ja nur in der lokalen Entwicklungsumgebung gesetzt werden. Und das sollte logischerweise immer möglich sein.

Das Beispiel mit .htaccess ist eigentlich ein schlechtes. Besser ist es, die ENV-Variable in der httd.conf festzulegen. So ist man auf Dateiebene vollkommen unabhängig. Wird die ENV in einer .htaccess festgelegt, und der online Webspace unterstützt das Setzen von Umgebungsvariablen ebenfalls, darf man diese .htaccess-Datei nicht hochladen, da sonst auf einmal die Online-Umgebung als Develop erkannt wird.