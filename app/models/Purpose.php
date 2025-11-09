<?php
require_once __DIR__ . '/../../config/database.php';

class Purpose {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM purposes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
