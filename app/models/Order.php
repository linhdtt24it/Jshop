<?php
require_once __DIR__ . '/../core/Model.php'; 

class Order extends Model { 
  
    public function createOrder($data) {
        $sql = "INSERT INTO orders (
                    user_id, 
                    receiver_name, 
                    receiver_phone, 
                    shipping_address, 
                    total_amount, 
                    payment_method, 
                    payment_status, 
                    order_status
                ) VALUES (
                    :user_id, 
                    :receiver_name, 
                    :receiver_phone, 
                    :shipping_address, 
                    :total_amount, 
                    :payment_method, 
                    :payment_status, 
                    :order_status
                )";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id'         => $data['user_id'],
                ':receiver_name'   => $data['receiver_name'],
                ':receiver_phone'  => $data['receiver_phone'],
                ':shipping_address'=> $data['shipping_address'],
                ':total_amount'    => $data['total_amount'],
                ':payment_method'  => $data['payment_method'],
                ':payment_status'  => $data['payment_status'],
                ':order_status'    => $data['order_status']
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi Create Order: " . $e->getMessage());
            return false;
        }
    }

    public function getOrderById($order_id) {
        $sql = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
    
    public function countOrdersByStatus($status) {
        $sql = "SELECT COUNT(*) FROM orders WHERE order_status = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }
    
}