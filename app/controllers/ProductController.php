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

        $category = null;
        if ($cat > 0) {
            $catStmt = $this->db->prepare("SELECT * FROM categories WHERE category_id = ?");
            $catStmt->execute([$cat]);
            $category = $catStmt->fetch(PDO::FETCH_ASSOC);
        }

        $where = [];
        $params = [];
        if ($search !== '') { $where[] = "p.name LIKE ?"; $params[] = "%$search%"; }
        if ($cat > 0) { $where[] = "p.category_id = ?"; $params[] = $cat; }
        
        $gender = $_GET['gender'] ?? '';
        if ($gender !== '') { $where[] = "p.gender = ?"; $params[] = $gender; }

        $material_id = (int)($_GET['material_id'] ?? 0);
        if ($material_id > 0) { $where[] = "p.material_id = ?"; $params[] = $material_id; }

        $purpose_id = (int)($_GET['purpose_id'] ?? 0);
        if ($purpose_id > 0) { $where[] = "p.purpose_id = ?"; $params[] = $purpose_id; }

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

        $materialsStmt = $this->db->query("SELECT material_id, name FROM materials ORDER BY name");
        $materials = $materialsStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $purposesStmt = $this->db->query("SELECT purpose_id, name FROM purposes ORDER BY name");
        $purposes = $purposesStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $data = [
            'products' => $products,
            'cats' => $cats,
            'materials' => $materials,
            'purposes' => $purposes,
            'total' => $total,
            'totalPages' => $totalPages,
            'category' => $category,
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