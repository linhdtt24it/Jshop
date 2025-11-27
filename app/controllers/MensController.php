<?php
require_once __DIR__ . '/../../config/database.php';

class MensController {
    private $db;

    public function __construct() {
        $db = new Database();
        $this->db = $db->connect();
    }

    public function index() {
        $sql = "
            SELECT *
            FROM products
            WHERE category_id = 1
            ORDER BY product_id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/mens/index.php';
    }
}
