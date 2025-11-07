<?php
require_once __DIR__ . '/../../config/database.php';

class ProductController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index() {
        // === THAM SỐ ===
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['q'] ?? '');
        $cat = (int)($_GET['cat'] ?? 0);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // === ĐIỀU KIỆN WHERE ===
        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = "p.name LIKE ?";
            $params[] = "%$search%";
        }
        if ($cat > 0) {
            $where[] = "p.category_id = ?";
            $params[] = $cat;
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // === ĐẾM TỔNG ===
        $countSql = "SELECT COUNT(*) FROM products p $whereSql";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        // === LẤY SẢN PHẨM – KHÔNG DÙNG ? CHO LIMIT/OFFSET ===
        $sql = "SELECT p.*, c.name AS cat_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.product_id LIMIT 1) AS thumb
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                $whereSql
                ORDER BY p.product_id DESC
                LIMIT $perPage OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params); // Chỉ truyền tham số cho WHERE
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // === LẤY DANH MỤC ===
        $catStmt = $this->db->query("SELECT category_id, name FROM categories ORDER BY name");
        $cats = $catStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // === GỬI DỮ LIỆU CHO VIEW ===
        require_once __DIR__ . '/../views/products/list.php';
    }

    public function detail($id) {
        $id = (int)$id;
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS cat_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?
        ");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $imgStmt = $this->db->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
        $imgStmt->execute([$id]);
        $product['images'] = $imgStmt->fetchAll(PDO::FETCH_COLUMN);

        require_once __DIR__ . '/../views/products/detail.php';
    }
}

// === ROUTE ===
$action = $_GET['action'] ?? 'index';
$controller = new ProductController();

if ($action === 'detail' && !empty($_GET['id'])) {
    $controller->detail($_GET['id']);
} else {
    $controller->index();
}