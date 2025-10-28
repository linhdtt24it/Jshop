<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';

class OrderController {
    public function checkout($user_id, $cart) {
        $orderModel = new Order();
        $itemModel = new OrderItem();

        // tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // tạo đơn
        $order_id = $orderModel->create($user_id, $total);

        // lưu chi tiết sản phẩm
        foreach ($cart as $item) {
            $itemModel->addItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
        }

        return $order_id;
    }
}
