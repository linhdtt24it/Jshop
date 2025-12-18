<?php
// Jshop/app/controllers/OrderController.php

public function checkout() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $cartModel = $this->model('CartModel');
        $orderModel = $this->model('Order');
        
        // 1. Lấy sản phẩm từ giỏ hàng để tính tổng tiền
        $items = $cartModel->getCartItemsByUserId($user_id);
        if (empty($items)) {
            die("Giỏ hàng trống");
        }

        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // 2. Chuẩn bị dữ liệu đơn hàng từ Form
        $orderData = [
            'user_id'          => $user_id,
            'receiver_name'    => $_POST['full_name'],
            'receiver_phone'   => $_POST['phone'],
            'shipping_address' => $_POST['address'],
            'total_amount'     => $total_amount,
            'payment_method'   => $_POST['payment_method'] ?? 'COD',
            'payment_status'   => 'pending',
            'order_status'     => 'pending'
        ];

        // 3. Lưu vào bảng orders
        $order_id = $orderModel->createOrder($orderData);

        if ($order_id) {
            // 4. Lưu chi tiết từng sản phẩm vào bảng order_items
            foreach ($items as $item) {
                // Bạn cần gọi OrderItem model ở đây
                $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)")
                         ->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }

            // 5. Xóa giỏ hàng sau khi đặt thành công
            $cartModel->clearCartByUserId($user_id);
            $_SESSION['cart_count'] = 0;

            // 6. Hiển thị thông báo thành công
            $this->view('checkout/success', ['order_id' => $order_id]);
        }
    }
}