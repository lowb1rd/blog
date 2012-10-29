<?php

class ePDOStatement extends PDOStatement {
    private $pdo;

    protected function __construct(ePDO $pdo) {
        $this->pdo = $pdo;
    }

    public function execute($params = array()) {
        $this->pdo->increaseQueryCount();
        return parent::execute($params);
    }
}

?>