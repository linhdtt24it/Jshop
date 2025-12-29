<?php

public function checkout() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $cartModel = $this->model('CartModel');
        $orderModel = $this->model('Order');
        
        $items = $cartModel->getCartItemsByUserId($user_id);
        if (empty($items)) {
            die("Giỏ hàng trống");
        }

        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

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

        $order_id = $orderModel->createOrder($orderData);

        if ($order_id) {
            foreach ($items as $item) {
                $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)")
                         ->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }

            $cartModel->clearCartByUserId($user_id);
            $_SESSION['cart_count'] = 0;

            $this->view('checkout/success', ['order_id' => $order_id]);
        }
    }
}