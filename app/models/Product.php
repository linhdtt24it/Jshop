<?php
require_once __DIR__ . '/../core/Model.php';

class Product extends Model {

    public function getProductsByCategory($cat_id) {
        return $this->selectAll(
            "SELECT * FROM products WHERE category_id = ?",
            [$cat_id]
        );
    }
    public function getMaleProducts() {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE category_id = 1 ORDER BY product_id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductById($id) {
    return $this->selectOne(
        "SELECT * FROM products WHERE product_id = ?",
        [$id]
    );
    }


}
