<?php
// Jshop/app/controllers/CartController.php

require_once __DIR__ . '/../core/Controller.php';

class CartController extends Controller {
    private $cartModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel'); // Khởi tạo CartModel
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Hiển thị danh sách sản phẩm trong giỏ hàng
     */
    public function index() {
        $items = [];
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            // Lấy danh sách sản phẩm theo ID người dùng
            $items = $this->cartModel->getCartItemsByUserId($user_id);
        }

        // Truyền dữ liệu sang View cart/index.php
        $this->view('cart/index', ['items' => $items]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (Xử lý qua AJAX)
     */
    public function add() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $product_id = (int)($_GET['id'] ?? 0);
        $user_id = $_SESSION['user_id'];

        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
            exit;
        }       
        $result = $this->cartModel->addToCart($user_id, $product_id, 1);

        if ($result) {
            // Tính toán lại tổng số lượng để cập nhật badge trên giao diện
            $items = $this->cartModel->getCartItemsByUserId($user_id);
            $total_count = 0;
            foreach ($items as $item) {
                $total_count += $item['quantity'];
            }
            $_SESSION['cart_count'] = $total_count;

            echo json_encode(['success' => true, 'cart_count' => $total_count]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm vào giỏ']);
        }
    }

    
    public function update() {
        $user_id = $_SESSION['user_id'] ?? null;
        $product_id = (int)($_GET['id'] ?? 0);
        $new_qty = (int)($_GET['qty'] ?? 0);

        if ($user_id && $product_id > 0) {
            // Model sẽ tự động xóa nếu new_qty <= 0
            $this->cartModel->updateQuantity($user_id, $product_id, $new_qty);
            
            // Cập nhật lại cart_count trong session
            $items = $this->cartModel->getCartItemsByUserId($user_id);
            $total_count = 0;
            foreach ($items as $item) {
                $total_count += $item['quantity'];
            }
            $_SESSION['cart_count'] = $total_count;
        }

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    /**
     * Xóa hoàn toàn một sản phẩm khỏi giỏ hàng
     */
    public function removeItem() {
        $user_id = $_SESSION['user_id'] ?? null;
        $product_id = (int)($_GET['id'] ?? 0);

        if ($user_id && $product_id > 0) {
            $this->cartModel->removeItem($user_id, $product_id);
            
            // Cập nhật lại cart_count trong session
            $items = $this->cartModel->getCartItemsByUserId($user_id);
            $total_count = 0;
            foreach ($items as $item) {
                $total_count += $item['quantity'];
            }
            $_SESSION['cart_count'] = $total_count;
        }

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }
}