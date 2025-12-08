<?php
// app/models/Order.php

require_once __DIR__ . '/../core/Model.php'; 

class Order extends Model { // Kế thừa Model
  
    public function create($user_id, $total_amount) {
        // Thay $this->conn bằng $this->db
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_amount]);
        return $this->db->lastInsertId();
    }
}