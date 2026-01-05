<?php

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
        $cart_items = $this->cartModel->getCartItemsByUserId($user_id);

        if (empty($cart_items)) {
            header("Location: " . BASE_URL . "cart");
            exit;
        }

        $payment_method = $_POST['payment_method'] ?? 'COD';
        $total_amount = (float)($_POST['total_amount'] ?? 0);

        $order_status = 'pending';
        if ($payment_method === 'MOMO_QR') {
            $order_status = 'on-hold';
        }

        $order_data = [
            'user_id'          => $user_id,
            'receiver_name'    => trim($_POST['receiver_name']),
            'receiver_phone'   => trim($_POST['receiver_phone']),
            'shipping_address' => trim($_POST['shipping_address']),
            'total_amount'     => $total_amount,
            'payment_method'   => $payment_method,
            'payment_status'   => 'pending', 
            'order_status'     => $order_status
        ];

        $order_id = $this->orderModel->createOrderWithItemsAndStockUpdate($order_data, $cart_items);

        if ($order_id) {
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
                case 'MOMO_QR':
                    header("Location: " . BASE_URL . "checkout/momoQrInfo/$order_id");
                    break;
                default:
                    $_SESSION['checkout_success_info'] = ['order_id' => $order_id, 'total' => $total_amount];
                    header("Location: " . BASE_URL . "checkout/success");
                    break;
            }
            exit;
        } else {
            // Failure, redirect back to cart where flash message will be shown
            header("Location: " . BASE_URL . "cart");
            exit;
        }
    }

    public function momoQrInfo($order_id) {
        $order = $this->orderModel->getOrderById($order_id);
        $user = $this->model('User')->getUserById($order['user_id']);
        $this->view('checkout/momo_qr_info', ['order' => $order, 'user' => $user, 'order_id' => $order_id]);
    }

    public function cancelOrder($order_id) {
        $order_id = (int)$order_id;
        $user_id = $_SESSION['user_id'];
        
        $items = $this->orderItemModel->getOrderItemsByOrderId($order_id);
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $p_id = (int)$item['product_id'];
                $qty = (int)$item['quantity'];
                $this->cartModel->addToCart($user_id, $p_id, $qty);
            }

            $db = (new Database())->connect();
            $sql = "UPDATE orders SET order_status = 'cancelled', payment_status = 'cancelled' WHERE order_id = ? AND user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$order_id, $user_id]);

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
        $this->view('checkout/bank_info', ['order' => $order, 'order_id' => $order_id, 'method' => 'BANK_TRANSFER']);
    }

    public function momoRedirect($order_id) {
        require_once __DIR__ . '/../../momo/loader.php';
        $momo_config = require __DIR__ . '/../../config/momo.php';

        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            // Handle order not found
            header("Location: " . BASE_URL . "cart");
            exit;
        }

        $amount = (string)$order['total_amount'];
        $orderId = (string)$order['order_id'];
        $orderInfo = "Thanh toán đơn hàng #" . $orderId;
        $requestId = time() . "";
        $notifyUrl = BASE_URL . "checkout/momo_ipn";
        $returnUrl = BASE_URL . "checkout/momo_return";

        $partnerInfo = new \MService\Payment\Shared\SharedModels\PartnerInfo($momo_config['partnerCode'], $momo_config['accessKey'], $momo_config['secretKey']);
        $env = new \MService\Payment\Shared\SharedModels\Environment($momo_config['endpoint'], $partnerInfo, 'development');

        $response = \MService\Payment\AllInOne\Processors\CaptureMoMo::process($env, $orderId, $orderInfo, $amount, '', $requestId, $returnUrl, $notifyUrl);

        if (isset($response['payUrl'])) {
            header("Location: " . $response['payUrl']);
            exit;
        } else {
            // Handle error
            $_SESSION['flash_message'] = "Không thể tạo yêu cầu thanh toán MoMo. Vui lòng thử lại.";
            header("Location: " . BASE_URL . "cart");
            exit;
        }
    }

    public function momo_ipn() {
        require_once __DIR__ . '/../../momo/loader.php';
        $momo_config = require __DIR__ . '/../../config/momo.php';

        $partnerInfo = new \MService\Payment\Shared\SharedModels\PartnerInfo($momo_config['partnerCode'], $momo_config['accessKey'], $momo_config['secretKey']);
        $env = new \MService\Payment\Shared\SharedModels\Environment($momo_config['endpoint'], $partnerInfo, 'development');

        try {
            $response = \MService\Payment\AllInOne\Processors\CaptureIPN::process($env, file_get_contents('php://input'));

            if ($response['errorCode'] == 0) {
                $order_id = $response['orderId'];
                $this->orderModel->updateOrderStatus($order_id, 'paid', 'processing');
            }
        } catch (Exception $e) {
            // Log error
            error_log("Momo IPN Error: " . $e->getMessage());
        }
    }
    
    public function momo_return() {
        if (isset($_GET['errorCode']) && $_GET['errorCode'] == 0) {
            $order_id = $_GET['orderId'];
            $order = $this->orderModel->getOrderById($order_id);
            $_SESSION['checkout_success_info'] = ['order_id' => $order['order_id'], 'total' => $order['total_amount']];
            header("Location: " . BASE_URL . "checkout/success");
        } else {
            $_SESSION['flash_message'] = "Thanh toán MoMo thất bại. Vui lòng thử lại.";
            header("Location: " . BASE_URL . "cart");
        }
        exit;
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