<?php
// Jshop/app/controllers/CheckoutController.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/CartModel.php'; 
require_once __DIR__ . '/../models/Order.php'; 
require_once __DIR__ . '/../models/OrderItem.php'; 

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
            'user_id' => $user_id,
            // Giả định bạn có thể lấy thông tin người dùng từ $_SESSION hoặc UserModel
            'user_info' => [ 
                'name' => $_SESSION['user_name'] ?? 'Khách hàng',
                'email' => $_SESSION['user_email'] ?? '',
                // Thêm các trường địa chỉ/phone nếu đã lưu
                'phone' => '', 
                'address' => ''
            ]
        ];

        $this->view('checkout/index', $data);
    }
    
    // Hàm xử lý việc tạo đơn hàng và chuyển hướng thanh toán
    public function process() {
        // 1. Lấy và xác thực dữ liệu POST
        $user_id = $_SESSION['user_id'];
        $receiver_name = trim($_POST['receiver_name'] ?? '');
        $receiver_phone = trim($_POST['receiver_phone'] ?? '');
        $shipping_address = trim($_POST['shipping_address'] ?? '');
        $payment_method = $_POST['payment_method'] ?? 'COD';
        $total_amount = (float)($_POST['total_amount'] ?? 0);

        if (empty($receiver_name) || empty($receiver_phone) || empty($shipping_address) || $total_amount <= 0) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin nhận hàng và tổng tiền hợp lệ.';
            header("Location: " . BASE_URL . "checkout/index");
            exit;
        }

        $cart_items = $this->cartModel->getCartItemsByUserId($user_id);
        if (empty($cart_items)) {
            $_SESSION['error'] = 'Giỏ hàng trống. Vui lòng thêm sản phẩm.';
            header("Location: " . BASE_URL . "cart/index");
            exit;
        }
        
        // 2. Tạo đơn hàng
        $order_data = [
            'user_id' => $user_id,
            'receiver_name' => $receiver_name,
            'receiver_phone' => $receiver_phone,
            'shipping_address' => $shipping_address,
            'total_amount' => $total_amount,
            'payment_method' => $payment_method,
            // Trạng thái ban đầu: đang chờ thanh toán
            'payment_status' => 'pending', 
            // Trạng thái đơn hàng: đang chờ xử lý
            'order_status' => ($payment_method == 'COD') ? 'processing' : 'pending' 
        ];

        $order_id = $this->orderModel->createOrder($order_data);

        if (!$order_id) {
            $_SESSION['error'] = 'Lỗi hệ thống khi tạo đơn hàng.';
            header("Location: " . BASE_URL . "checkout/index");
            exit;
        }

        // 3. Thêm chi tiết đơn hàng (order items)
        $items_added = $this->orderItemModel->addOrderItems($order_id, $cart_items);

        if (!$items_added) {
            // Xử lý rollback (xóa order nếu item không thêm được) - Tùy thuộc vào Order Model
            $_SESSION['error'] = 'Lỗi khi thêm chi tiết sản phẩm. Vui lòng thử lại.';
            header("Location: " . BASE_URL . "checkout/index");
            exit;
        }

        // 4. Xóa giỏ hàng sau khi tạo đơn hàng thành công
        $this->cartModel->clearCartByUserId($user_id);
        
        // 5. Xử lý chuyển hướng thanh toán
        switch ($payment_method) {
            case 'BANK_TRANSFER':
                // Chuyển hướng đến trang thông báo chuyển khoản ngân hàng
                header("Location: " . BASE_URL . "checkout/bankTransferInfo/$order_id");
                exit;
            case 'MOMO':
                // Tích hợp API MoMo (MOCK/Demo)
                // LÝ TƯỞNG: Gọi MoMo API để lấy payment URL, sau đó redirect
                $_SESSION['order_id'] = $order_id;
                header("Location: " . BASE_URL . "checkout/momoRedirect/$order_id");
                exit;
            case 'ZALOPAY':
                // Tích hợp API ZaloPay (MOCK/Demo)
                // LÝ TƯỞNG: Gọi ZaloPay API để lấy payment URL, sau đó redirect
                $_SESSION['order_id'] = $order_id;
                header("Location: " . BASE_URL . "checkout/zalopayRedirect/$order_id");
                exit;
            case 'COD':
            default:
                // Thanh toán khi nhận hàng (đã tạo order_status = 'processing')
                $_SESSION['success'] = 'Đặt hàng thành công. Chúng tôi sẽ sớm giao hàng.';
                header("Location: " . BASE_URL . "orders/detail/$order_id");
                exit;
        }
    }
    
    // MOCK: Trang thông báo chuyển khoản ngân hàng
    public function bankTransferInfo($order_id) {
        $order = $this->orderModel->getOrderById($order_id);
        $this->view('checkout/bank_info', ['order' => $order]);
    }
    
    // MOCK: Giả lập chuyển hướng MoMo (thực tế sẽ gọi API)
    public function momoRedirect($order_id) {
        // Trong môi trường thật, đây là nơi bạn gọi MoMo API
        $this->view('checkout/mock_redirect', ['order_id' => $order_id, 'method' => 'MOMO']);
    }
    
    // MOCK: Giả lập chuyển hướng ZaloPay (thực tế sẽ gọi API)
    public function zalopayRedirect($order_id) {
        // Trong môi trường thật, đây là nơi bạn gọi ZaloPay API
        $this->view('checkout/mock_redirect', ['order_id' => $order_id, 'method' => 'ZALOPAY']);
    }

    // MOCK: Endpoint xử lý callback/IPN từ cổng thanh toán (RẤT QUAN TRỌNG TRONG THỰC TẾ)
    public function paymentCallback() {
        // Logic nhận IPN từ MoMo/ZaloPay
        // Xác thực chữ ký, cập nhật payment_status = 'paid' và order_status = 'processing'
    }
}