<?php
require_once __DIR__ . '/../core/Model.php';

class Category extends Model {

    public function getAllCategories() {
        return $this->selectAll("SELECT * FROM categories ORDER BY category_id ASC");
    }

    public function getCategoryById($id) {
        return $this->selectOne("SELECT * FROM categories WHERE category_id = ?", [$id]);
    }
}
