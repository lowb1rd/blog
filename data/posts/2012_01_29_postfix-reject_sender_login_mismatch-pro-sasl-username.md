---
date: 2012-01-29 21:25:31
title: Postfix: reject_sender_login_mismatch pro SASL username
tags: Linux
slug: postfix-reject_sender_login_mismatch-pro-sasl-username

last_updated: 2012-01-07 21:35:52
---

*Die Postfix-Konfiguration kann einem einige graue Haare verursachen, so wie bei dieser Problemstellung hier. Ich habe manchmal den Eindruck, dass Postfix gar nicht dazu entwickelt worden ist, E-Mails zu senden und zu empfangen, aber dennoch so flexibel ist, dass es sich irgendwie zu einem smtpd konfigurieren lässt..*

Prinzipiell erlaubt Postfix mit `permit_sasl_authenticated` *jedem* SASL-authentifizierten Benutzer beliebige **FROM** E-Mail-Adressen zu verwenden. Mit der Option `reject_sender_login_mismatch` wird dieses Verhalten verboten und überprüft ob der SASL-Username bzw. die damit verknüpften Mail-Adressen und -Aliase mit dem **FROM** der E-Mail übereinstimmt.

Wenn man die Option reject_sender_login_mismatch nun nur auf bestimmte SASL-Usernamen beschränken will, wird es kompliziert. Das lässt sich nämlich nicht so einfach in der Postfix-Konfiguration (main.cf) einstellen, da sämtliche Lookup-Tables nicht den SASL-Username als Index benutzen.

Was wir wollen
--------------
Der User **foo@example.org** soll ausschließlich E-Mails mit foo@example.org als Absender senden können.<br />
Der User **bar@example.org** soll hingegen beliebige Absenderadressen wählen können.

Das geht nur über einen [Policy Daemon](//www.postfix.org/SMTPD_POLICY_README.html): Hört sich kompliziert an, ist aber im einfachsten Fall ein simples Script, dem von Postfix unter anderem der SASL-Username übergeben wird. 


smtpd Policy Daemon mit PHP
===========================
Das funktioniert mit anderen Scriptsprachen wie Python oder Perl natürlich ähnlich, und wahrscheinlich sogar besser, wir machen das aber mal, weil wir es können, mit PHP!

Unser PHP Policy Daemon soll unter `/usr/local/bin/policyd` liegen (chmod +x nicht vergessen, chroot beachten). Die Parameter von Postfix kommen über STDIN. Das Ergebnis geben wir ganz normal über `echo` an Postfix zurück. Als Ergebnis kommt ACCEPT oder REJECT in Frage, oder aber jede bekannte Postfix UCE restriction, also auch reject_sender_login_mismatch! (Siehe dazu auch [access(5)](http://www.postfix.org/access.5.html))

**/usr/local/bin/policyd**

    #!php@1
    #!/usr/bin/php
    <?php
    $allow_relay = array(
        'bar@example.org',
    );
    
    $fp = fopen('php://stdin', 'r');
    while (true) {
        $line = fgets($fp, 512);
        if (strpos($line, '=')) {
            list($k, $v) = explode('=', trim($line));
            $env[$k] = $v;
        }  
        if($line == "\n") break;
    }
    fclose($fp);
    
    if ($env['sasl_username']) {
        if (!in_array($env['sasl_username'], $allow_relay)) {
            echo "action=reject_sender_login_mismatch\n\n";
            die();
        }
    }
    echo "action=DUNNO\n\n";

Simple String-Funktionen: Ist der sasl_username **nicht** in unserem Array mit erlaubten Relay-Logins, wird die reject_sender_login_mismatch UCE restriction zurück gegeben. Das FROM muss nun also mit sasl_username übereinstimmen. Im anderen Fall wird ein "DUNNO" zurückgegeben, was Postfix dazu veranlasst mit der nächsten Regel weiter zu machen. reject_sender_login_mismatch gilt dann für diesen sasl_username also nicht.

Wichtig ist hier, den STDIN mit `fgets` zeilenweise zu lesen und aufzuhören sobald eine leere Zeile empfangen wird. Versucht man den gesamten STDIN über file_get_contents zu holen, beträgt der Timeout 60 Sekunden bis das Script fortgeführt wird und Postfix das Ergebnis übergeben werden kann.

Neben dem sasl_username kommen über STDIN noch weitere Parameter, wie z.B. die Absender-Adresse, Empfänger-Adresse oder die Client-IP. Eine vollständige Übersicht gibt es hier: [http://www.postfix.org/SMTPD_POLICY_README.html#protocol](//www.postfix.org/SMTPD_POLICY_README.html#protocol)

Postfix Konfiguration
---------------------
**master.cf**

    policy  unix    -       n       n       -       0       spawn
        user=nobody argv=/usr/local/bin/policyd

**main.cf**
<pre><code>smtpd_sender_restrictions = permit_mynetworks,
    <strong>check_policy_service unix:private/policy,</strong>
    permit_sasl_authenticated,
    [..]   

~$ /etc/init.d/postfix restart
</code></pre>

Das wars!