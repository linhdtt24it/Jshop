<?php
require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../core/Controller.php'; 

class ProductController extends Controller {
    private $db;
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
   }


    public function index() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['q'] ?? '');
        $cat = (int)($_GET['cat'] ?? 0);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];
        if ($search !== '') { $where[] = "p.name LIKE ?"; $params[] = "%$search%"; }
        if ($cat > 0) { $where[] = "p.category_id = ?"; $params[] = $cat; }
        $gender = $_GET['gender'] ?? '';
        $material = $_GET['material'] ?? '';
        $purpose = $_GET['purpose'] ?? '';
        if ($gender !== '') { $where[] = "p.gender = ?"; $params[] = $gender; }
        if ($material !== '') { $where[] = "p.material = ?"; $params[] = $material; }
        if ($purpose !== '') { $where[] = "p.purpose = ?"; $params[] = $purpose; }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countSql = "SELECT COUNT(*) FROM products p $whereSql";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        $sql = "
            SELECT p.*, c.name AS cat_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            $whereSql
            ORDER BY p.product_id DESC
            LIMIT $perPage OFFSET $offset
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $catStmt = $this->db->query("SELECT category_id, name FROM categories ORDER BY name");
        $cats = $catStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $data = [
            'products' => $products,
            'cats' => $cats,
            'total' => $total,
            'totalPages' => $totalPages,
        ];
        
        $this->view('products/list', $data);
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

        if (!$product) {
            die("Không tìm thấy sản phẩm");
        }
        $product['images'] = [$product['image']]; 
        
        $data = [
            'product' => $product,
        ];
        
        $this->view('products/detail', $data);
    }

}