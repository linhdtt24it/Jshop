<?php
require_once __DIR__ . '/../core/Model.php';

class Product extends Model {

    public function getProductsByIdRange($start, $end) {
        $sql = "SELECT * FROM products 
                WHERE product_id BETWEEN ? AND ?
                ORDER BY product_id ASC";

        return $this->selectAll($sql, [$start, $end]);
    }

    public function getProductsByCategory($cat_id) {
        return $this->selectAll(
            "SELECT * FROM products WHERE category_id = ?",
            [$cat_id]
        );
    }

    public function getMaleProducts() {
        $sql = "SELECT * FROM products 
                WHERE category_id = 1 
                ORDER BY product_id DESC";

        return $this->selectAll($sql);
    }

    public function getProductById($id) {
        return $this->selectOne(
            "SELECT * FROM products WHERE product_id = ?",
            [$id]
        );
    }

    public function getProductsByMaterial($material_id) {
        $sql = "SELECT * FROM products WHERE material_id = ?";
        return $this->selectAll($sql, [$material_id]);
    }
}
