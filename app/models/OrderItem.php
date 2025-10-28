<?php
require_once __DIR__ . '/../../config/database.php';

class OrderItem {
    private $conn;
    public function __construct() {
        $this->conn = Database::connect();
    }

    public function addItem($order_id, $product_id, $quantity, $price) {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }
}
