<?php

class Database {
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $dsn = 'mysql:host=localhost;dbname=sk;charset=utf8mb4';
        $user = 'root';
        $pass = '';

        try {
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}