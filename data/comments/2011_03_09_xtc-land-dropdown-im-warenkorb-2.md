---
date: 2012-05-16 12:24:38
name: Gerhard
www: 
email: gl@jadrovino.de
---

Ich würde gerne die Erweiterung einbauen, scheitere aber schon beim Suchen

/create_account.php und /create_account_guest.php
Suche:
if (isset($_POST['country'])) {
  $selected = $_POST['country'];
} else {
  $selected = STORE_COUNTRY;
}


In meiner create_account.php sieht das so aus:
if (isset($_POST['country'])) {
  $country = (int)$_POST['country'];
} else {
  $country = STORE_COUNTRY;
}


Liegt es daran, dass ich xtcM v1.05 SP1bb nutze?

Danke
gerhard