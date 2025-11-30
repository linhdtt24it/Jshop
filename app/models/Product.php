<?php
require_once __DIR__ . '/../core/Model.php';

class Product extends Model {

    public function getProductsByIdRange($start, $end) {
        $sql = "SELECT * FROM products 
                WHERE product_id BETWEEN ? AND ?
                ORDER BY product_id ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($cat_id) {
        return $this->selectAll(
            "SELECT * FROM products WHERE category_id = ?",
            [$cat_id]
        );
    }

    public function getMaleProducts() {
        $stmt = $this->connect()->prepare(
            "SELECT * FROM products WHERE category_id = 1 ORDER BY product_id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        return $this->selectOne(
            "SELECT * FROM products WHERE product_id = ?",
            [$id]
        );
    }

    // ✔ SỬA HÀM NÀY - dùng connect() thay vì $db
    public function getProductsByMaterial($material_id) {
    $sql = "SELECT * FROM products WHERE material_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$material_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
