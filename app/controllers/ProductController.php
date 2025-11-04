<?php
require_once "../config/database.php";

class ProductController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index() {
        $search = $_GET['q'] ?? '';
        $sql = "SELECT * FROM products";
        if ($search) {
            $sql .= " WHERE name LIKE :search";
        }
        $sql .= " ORDER BY id DESC LIMIT 12";

        $stmt = $this->db->prepare($sql);
        if ($search) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once "../app/views/products/index.php";
    }
}

$controller = new ProductController();
$controller->index();
?>