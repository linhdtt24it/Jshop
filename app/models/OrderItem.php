<?php
// app/models/OrderItem.php

require_once __DIR__ . '/../core/Model.php'; 

class OrderItem extends Model { // Kế thừa Model
    // Bỏ private $conn;
    
    // Xóa hàm __construct()
    // public function __construct() {
    //     $this->conn = Database::connect();
    // }

    public function addItem($order_id, $product_id, $quantity, $price) {
        // Thay $this->conn bằng $this->db
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }
}