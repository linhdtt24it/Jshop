<?php
require_once __DIR__ . '/../core/Controller.php';

class CartController extends Controller {
    private $cartModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel');
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $items = [];
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $items = $this->cartModel->getCartItemsByUserId($user_id);
        }

        $this->view('cart/index', ['items' => $items]);
    }

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
            $this->cartModel->updateQuantity($user_id, $product_id, $new_qty);
            
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

    public function removeItem() {
        $user_id = $_SESSION['user_id'] ?? null;
        $product_id = (int)($_GET['id'] ?? 0);

        if ($user_id && $product_id > 0) {
            $this->cartModel->removeItem($user_id, $product_id);
            
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