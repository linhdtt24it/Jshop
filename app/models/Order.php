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
    public function createOrderWithItemsAndStockUpdate($order_data, $cart_items) {
        $this->db->beginTransaction();

        try {
            // 1. Check stock for all items
            $sql_check_stock = "SELECT name, stock FROM products WHERE product_id = ? FOR UPDATE";
            $stmt_check_stock = $this->db->prepare($sql_check_stock);

            foreach ($cart_items as $item) {
                $stmt_check_stock->execute([$item['product_id']]);
                $product = $stmt_check_stock->fetch(PDO::FETCH_ASSOC);
                
                if (!$product || $product['stock'] < $item['quantity']) {
                    $this->db->rollBack();
                    $_SESSION['flash_message'] = "Sản phẩm '{$item['name']}' không đủ hàng. Trong kho chỉ còn " . ($product['stock'] ?? 0) . " sản phẩm.";
                    return false; 
                }
            }

            // 2. Create the order record
            $sql_create_order = "INSERT INTO orders (user_id, receiver_name, receiver_phone, shipping_address, total_amount, payment_method, payment_status, order_status) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_create_order = $this->db->prepare($sql_create_order);
            $stmt_create_order->execute([
                $order_data['user_id'], $order_data['receiver_name'], $order_data['receiver_phone'], $order_data['shipping_address'],
                $order_data['total_amount'], $order_data['payment_method'], $order_data['payment_status'], $order_data['order_status']
            ]);
            $order_id = $this->db->lastInsertId();
            if (!$order_id) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            // 3. Add order items and update stock
            $sql_add_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_add_item = $this->db->prepare($sql_add_item);

            $sql_update_stock = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
            $stmt_update_stock = $this->db->prepare($sql_update_stock);

            foreach ($cart_items as $item) {
                // Add item
                if (!$stmt_add_item->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']])) {
                     throw new Exception("Không thể thêm sản phẩm vào đơn hàng.");
                }
                // Update stock
                if (!$stmt_update_stock->execute([$item['quantity'], $item['product_id']])) {
                    throw new Exception("Không thể cập nhật kho.");
                }
            }

            $this->db->commit();
            return $order_id;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi giao dịch tạo đơn hàng: " . $e->getMessage());
            if (!isset($_SESSION['flash_message'])) {
                $_SESSION['flash_message'] = 'Đã có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.';
            }
            return false;
        }
    }
    
}