<?php
// Jshop/app/models/CartModel.php

require_once __DIR__ . '/../core/Model.php';

class CartModel extends Model {
    
    protected $table = 'cart';

    public function getCartItemsByUserId($user_id) {
        $sql = "
            SELECT 
                c.user_id,
                c.product_id,
                c.quantity,
                p.name,
                p.price,
                p.image,
                p.stock
            FROM 
                cart c
            JOIN 
                products p ON c.product_id = p.product_id
            WHERE 
                c.user_id = :user_id
            ORDER BY 
                c.product_id
        ";
        return $this->selectAll($sql, [':user_id' => $user_id]);
    }

    public function getCartItemQuantity($user_id, $product_id) {
        $sql = "SELECT quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)($result['quantity']) : 0;
    }

    public function addToCart($user_id, $product_id, $quantity) {
        
        $sql = "
            INSERT INTO cart (user_id, product_id, quantity) 
            VALUES (:user_id, :product_id, :quantity)
            ON DUPLICATE KEY UPDATE 
                quantity = quantity + :quantity_update
        ";
        
        try {
            return $this->db->prepare($sql)->execute([
                ':user_id' => $user_id,
                ':product_id' => $product_id,
                ':quantity' => $quantity,
                ':quantity_update' => $quantity
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($user_id, $product_id);
        }
        
        $sql = "
            UPDATE cart 
            SET quantity = :quantity 
            WHERE user_id = :user_id AND product_id = :product_id
        ";
        
        try {
            return $this->db->prepare($sql)->execute([
                ':quantity' => $quantity,
                ':user_id' => $user_id,
                ':product_id' => $product_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeItem($user_id, $product_id) {
        $sql = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        
        try {
            return $this->db->prepare($sql)->execute([
                ':user_id' => $user_id,
                ':product_id' => $product_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function clearCartByUserId($user_id) {
        $sql = "DELETE FROM cart WHERE user_id = :user_id";
        
        try {
            return $this->db->prepare($sql)->execute([':user_id' => $user_id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa giỏ hàng: " . $e->getMessage());
            return false;
        }
    }
}