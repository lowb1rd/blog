---
date: 2010-02-06 12:25:59
title: PHP's PDO-Klasse erweitern
tags: Webtechnik
slug: phps-pdo-klasse-erweitern
featured: true

last_updated: 2012-01-07 21:35:53
---

<a href="http://php.net/manual/de/book.pdo.php">PDO</a> ist eine PHP-Erweiterung, die einen konsistenten Zugriff auf Datenbanken ermöglicht. Seit PHP 5.1.0 ist PDO fester Bestandteil von PHP - also eine native (in C geschriebene) PHP-Extension. Dennoch lässt sich PDO mit PHP erweitern. <strong>Wozu?</strong> Ein <em>Query-Counter</em>, also das simple Mitzählen der ausgeführten SQL-Queries, ist wohl das einfachste Beispiel. Nicht weit davon entfernt sind dann so nette Dinge wie <em>Query-Log</em> oder <em>Query-Zeitmessung</em>.

PDO kommt mit zwei Klassen: <a href="http://www.php.net/manual/de/class.pdo.php">PDO</a> und <a href="http://www.php.net/manual/de/class.pdostatement.php">PDOStatement</a><strong>*</strong>. Letztere kommt nur bei <a href="http://de.wikipedia.org/wiki/Prepared_Statement">Prepared Statements</a> ins Spiel. Wir beginnen also mit der PDO-Klasse. Diese muss ihre Eigenschaften an unsere eigene PDO-Klasse vererben. Das geht wie gewöhnlich mit dem Schlüsselwort "extends". Der Name unserer eigenen PDO-Klasse ist <strong>e</strong>PDO (e wie enhanced, extended, was auch immer..).

_[__*__ und PDOException, welche für dieses Beispiel aber irrelevant ist]_

    #!php@1
    class <strong>ePDO</strong> extends pdo {
        private $queryCount;
        public function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL) {
            $this->queryCount = 0;

            parent::__construct($dsn, $user, $pass, $options);

            $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('<strong>ePDOStatement</strong>', array($this)));
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }
    
Wichtig ist hier immer den parent-contructor aufzurufen. Die benötigten Parameter gibt's im <a href="http://www.php.net/manual/de/pdo.construct.php">Manual</a>. Außerdem legen wir das private Attribut $queryCount an, in welchem wir die Anzahl der ausgeführten SQL-Queries speichern. Weil wir auch die PDOStatement-Klasse erweitern wollen, teilen wir PDO mittels <strong>setAttribute</strong> mit, unsere eigene PDOStatement-Klasse zu verwenden. Diese hat sinnigerweise den Namen <strong>e</strong>PDOStatement (e wie .. ihr wisst schon ..). Der Konstruktor bietet sich übrigens auch dazu an, gewisse Standard-Optionen für PDO zu setzen. In diesem Beispiel die DEFAULT_FETCH_MODE.

Der Beispielcode ist so schon voll funktionsfähig - auch wenn er noch nicht viel macht. Beim Herstellen der Datenbank-Verbindung schreiben wir statt $pdo = new PDO(..) einfach
$pdo = new <strong>e</strong>PDO(..). Die Parameter, sowie auch alles andere, bleiben die selben.

Um nun die Anzahl der Queries mitzuzählen, müssen wir alle Methoden die Queries ausführen erweitern. Das sind bei der PDO-Klasse: exec() und query():

    #!php@1
    public function <strong>exec</strong>($sql) {
        $this->increaseQueryCount();
        return parent::exec($sql);
    }
    public function <strong>query</strong>($sql) {
        $this->increaseQueryCount();
        $args = func_get_args();
        return call_user_func_array(array($this, 'parent::query'), $args);
    }
    public function getQueryCount() {
        return $this->queryCount;
    }
    public function increaseQueryCount() {
        $this->queryCount++;
    }
    
getQueryCount() ist eine <a href="http://de.wikipedia.org/wiki/Zugriffsfunktion">getter-Methode</a> für das privat-Attribut $queryCount. Damit der Query-Count auch von unserer ePDOStatement-Klasse aus erhöht werden kann, packen wir die Logik dazu in die Public-Methode increaseQueryCount(). Da die query()-Methode variable Argumente (siehe <a href="http://www.php.net/manual/de/pdo.query.php">PHP-Manual</a>) hat, müssen wir alle übergebenen Argumente mit <a href="http://php.net/manual/de/function.call-user-func-array.php">call_user_func_array</a> an parent::query() übergeben.

Prepared Statements werden von der PDOStatement-Klasse ausgeführt, namentlich von der execute()-Methode. Um auch diese Queries zu zählen, müssen wir uns auch hier der Vererbung bedienen.

    #!php@1
    class ePDOStatement extends PDOStatement {
        private $pdo;

        protected function __construct(ePDO $pdo) {
            $this->pdo = $pdo
       }

        public function execute($params = array()) {
            $this->pdo->increaseQueryCount();
            return parent::execute($params);
        }
    }
    
Der Konstruktor bekommt das ePDO-Objekt übergeben, in dem unser aktueller Query-Count steht. Wir speichern es als private Eigenschaft ab. In der execute()-Methode rufen wir die increaseQueryCount()-Methode des ePDO-Objektes auf, um unseren Query-Counter zu erhöhen.

Die fertigen Klassen gibt's es hier nochmal zur Übersicht zum <strong>Download</strong>: <a href="http://neunzehn83.de/blog/wp-content/uploads/2010/02/epdo.class.phps">[ePDO](files/2010/epdo.class.phps) und [ePDOStatement](files/2010/epdostatement.class.phps).

So oder so ähnlich wird das dann benutzt:

    #!php@1
    $pdo = new ePDO("mysql:host=127.0.0.1;dbname=mydb", 'myuser', 'mypass');

    $pdo->query("SELECT 1");
    $pdo->exec("SELECT 1");

    $stmt = $pdo->prepare("SELECT 1");
    $stmt->execute();

    echo $pdo->getQueryCount(); // 3
    