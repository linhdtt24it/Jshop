<?php
// Jshop/app/controllers/CheckoutController.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../core/Controller.php';

class CheckoutController extends Controller {

    private $cartModel;
    private $orderModel;
    private $orderItemModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }
        $this->cartModel = $this->model('CartModel'); 
        $this->orderModel = $this->model('Order');
        $this->orderItemModel = $this->model('OrderItem');
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $cart_items = $this->cartModel->getCartItemsByUserId($user_id);
        if (empty($cart_items)) {
            header("Location: " . BASE_URL . "cart/index");
            exit;
        }
        $total = 0;
        foreach ($cart_items as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0); 
        }
        $data = [
            'page_title' => 'Thanh Toán',
            'cart_items' => $cart_items,
            'total_price' => $total,
            'user_info'  => [ 
                'name'    => $_SESSION['user_name'] ?? '',
                'phone'   => $_SESSION['user_phone'] ?? '', 
                'address' => $_SESSION['user_address'] ?? ''
            ]
        ];
        $this->view('checkout/index', $data);
    }
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $user_id = $_SESSION['user_id'];
        $payment_method = $_POST['payment_method'] ?? 'COD';
        $total_amount = (float)($_POST['total_amount'] ?? 0);

        $order_data = [
            'user_id'          => $user_id,
            'receiver_name'    => trim($_POST['receiver_name']),
            'receiver_phone'   => trim($_POST['receiver_phone']),
            'shipping_address' => trim($_POST['shipping_address']),
            'total_amount'     => $total_amount,
            'payment_method'   => $payment_method,
            'payment_status'   => 'pending', 
            'order_status'     => 'pending' 
        ];

        $order_id = $this->orderModel->createOrder($order_data);

        if ($order_id) {
            $cart_items = $this->cartModel->getCartItemsByUserId($user_id);
            $this->orderItemModel->addOrderItems($order_id, $cart_items);
            
            $this->cartModel->clearCartByUserId($user_id);
            $_SESSION['cart_count'] = 0;

            switch ($payment_method) {
                case 'BANK_TRANSFER':
                    header("Location: " . BASE_URL . "checkout/bankTransferInfo/$order_id");
                    break;
                case 'MOMO':
                    header("Location: " . BASE_URL . "checkout/momoRedirect/$order_id");
                    break;
                case 'ZALOPAY':
                    header("Location: " . BASE_URL . "checkout/zalopayRedirect/$order_id");
                    break;
                default:
                    $_SESSION['checkout_success_info'] = ['order_id' => $order_id, 'total' => $total_amount];
                    header("Location: " . BASE_URL . "checkout/success");
                    break;
            }
            exit;
        }
    }

    public function cancelOrder($order_id) {
        $order_id = (int)$order_id;
        $user_id = $_SESSION['user_id'];
        
        // 1. Lấy lại sản phẩm từ đơn hàng
        $items = $this->orderItemModel->getOrderItemsByOrderId($order_id);
        
        if (!empty($items)) {
            foreach ($items as $item) {
                // 2. Khôi phục lại giỏ hàng
                $p_id = (int)$item['product_id'];
                $qty = (int)$item['quantity'];
                $this->cartModel->addToCart($user_id, $p_id, $qty);
            }

            // 3. Cập nhật trạng thái đơn hàng (cancelled)
            $db = (new Database())->connect();
            $sql = "UPDATE orders SET order_status = 'cancelled', payment_status = 'cancelled' WHERE order_id = ? AND user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$order_id, $user_id]);

            // 4. Đồng bộ lại số lượng giỏ hàng trên Header
            $cart_items = $this->cartModel->getCartItemsByUserId($user_id);
            $_SESSION['cart_count'] = count($cart_items);
            
            $_SESSION['success'] = "Đã hủy đơn hàng #$order_id thành công.";
        }

        header("Location: " . BASE_URL . "cart/index");
        exit;
    }

    public function checkStatus($order_id) {
        header('Content-Type: application/json');
        $order = $this->orderModel->getOrderById($order_id);
        if ($order && $order['payment_status'] == 'paid') {
            $_SESSION['checkout_success_info'] = ['order_id' => $order['order_id'], 'total' => $order['total_amount']];
            echo json_encode(['status' => 'paid']);
        } else {
            echo json_encode(['status' => 'pending']);
        }
        exit;
    }

    public function bankTransferInfo($order_id) {
        $order = $this->orderModel->getOrderById($order_id);
        $this->view('checkout/bank_info', ['order' => $order, 'order_id' => $order_id]);
    }

    public function momoRedirect($order_id) {
        $this->view('checkout/mock_redirect', ['order_id' => $order_id, 'method' => 'MOMO', 'page_title' => 'Thanh toán MoMo']);
    }

    public function zalopayRedirect($order_id) {
        $this->view('checkout/mock_redirect', ['order_id' => $order_id, 'method' => 'ZALOPAY', 'page_title' => 'Thanh toán ZaloPay']);
    }

    public function success() {
        if (!isset($_SESSION['checkout_success_info'])) { 
            header("Location: " . BASE_URL); 
            exit; 
        }
        $data = ['page_title' => 'Thành công', 'order' => $_SESSION['checkout_success_info']];
        unset($_SESSION['checkout_success_info']);
        $this->view('checkout/success', $data);
    }
}