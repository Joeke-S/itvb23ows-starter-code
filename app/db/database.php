<?php

namespace Main;

use mysqli;

class Database {
    private $db;

    public function __construct() {
        $this->db = new mysqli('db', 'root', '', 'hive');
    }

    public function getcon() {
        return $this->db;
    }
}
