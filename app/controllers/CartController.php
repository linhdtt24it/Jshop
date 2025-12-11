<?php
require_once 'C:/Users/thaib/Jshop/config/database.php';
class CartController {

    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // === THÊM VÀO GIỎ (AJAX) ===

public function add() {
    $product_id = (int)($_GET['id'] ?? 0);
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
        exit;
    }

    $user_id = $_SESSION['user']['user_id'];

    $stmt = $this->db->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $new_qty = $row['quantity'] + 1;
        $stmt = $this->db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_qty, $user_id, $product_id]);
    } else {
        $stmt = $this->db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    // TÍNH TỔNG SỐ LƯỢNG
    $stmt = $this->db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total = $stmt->fetch()['total'] ?? 0;
    $_SESSION['cart_count'] = $total;

    echo json_encode(['success' => true, 'cart_count' => $total]);
}

    // === HIỂN THỊ GIỎ HÀNG (KHÔNG NHẢY HOME) ===
    public function index() {
        $items = [];

        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['user_id'];
            $stmt = $this->db->prepare("
                SELECT c.*, p.name, p.price, p.images 
                FROM cart c 
                JOIN products p ON c.product_id = p.product_id 
                WHERE c.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // GỌI VIEW
        require_once __DIR__ . '/../views/cart/index.php';
    }
}
?>