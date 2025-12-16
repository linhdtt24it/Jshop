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

    /**
     * Lấy danh sách các đơn hàng đang chờ xác nhận hoặc đang xử lý.
     */
    public function getPendingOrders() {
        $sql = "
            SELECT 
                o.*, 
                u.full_name AS customer_name
            FROM 
                orders o
            JOIN 
                users u ON o.user_id = u.user_id
            WHERE 
                o.order_status IN ('pending', 'processing')
            ORDER BY 
                o.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số lượng đơn hàng theo trạng thái cụ thể.
     * @param string $status Trạng thái đơn hàng (pending, processing, completed, etc.)
     */
    public function countOrdersByStatus($status) {
        $sql = "SELECT COUNT(*) FROM orders WHERE order_status = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }
}