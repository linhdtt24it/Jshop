<?php
// Jshop/app/models/OrderItem.php

require_once __DIR__ . '/../core/Model.php'; 

class OrderItem extends Model { 

    public function getOrderItemsByOrderId($order_id) {
    try {
        // Sử dụng dấu hỏi ? và truyền tham số kiểu mảng để PDO tự xử lý
        $sql = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi: " . $e->getMessage());
        return [];
    }
}

    /**
     * Thêm hàng loạt sản phẩm từ giỏ hàng vào chi tiết đơn hàng
     */
    public function addOrderItems($order_id, $cart_items) {
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            foreach ($cart_items as $item) {
                $price = $item['price'] ?? 0;
                $stmt->execute([
                    $order_id, 
                    $item['product_id'], 
                    $item['quantity'], 
                    $price
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Lỗi Add Order Items: " . $e->getMessage());
            return false;
        }
    }
}