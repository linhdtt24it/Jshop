<?php
require_once __DIR__ . '/../../config/database.php';

class Order {
    private $conn;
    public function __construct() {
        $this->conn = Database::connect();
    }

    public function create($user_id, $total_amount) {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_amount]);
        return $this->conn->lastInsertId();
    }
}
