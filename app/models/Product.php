<?php
// Jshop/app/models/Product.php
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
    
    public function getProductsByCollectionId($collection_id) {
        $sql = "SELECT * FROM products WHERE collection_id = ? ORDER BY created_at DESC";
        return $this->selectAll($sql, [$collection_id]);
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
    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM products";
        $row = $this->selectOne($sql);
        return $row['total'];
    }

    // ĐÃ SỬA LỖI SQL TẠI ĐÂY
    public function getAllProductsWithDetails() {
        $sql = "
            SELECT 
                p.*, 
                c.name AS category_name, 
                coll.name AS collection_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN collections coll ON p.collection_id = coll.collection_id -- Sửa lỗi: p->collection_id thành p.collection_id
            ORDER BY p.product_id DESC
        ";
        return $this->selectAll($sql);
    }
   
    public function deleteProduct($product_id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        return $this->execute($sql, [$product_id]);
    }

    public function updateProductStock($product_id, $stock) {
        $stock = max(0, (int)$stock); 
        $sql = "UPDATE products SET stock = ? WHERE product_id = ?";
        return $this->execute($sql, [$stock, $product_id]);
    }

    /**
     * Thêm mới hoặc cập nhật sản phẩm.
     */
    public function saveProduct($data) {
        $fields = [
            'name', 'description', 'category_id', 'material_id', 'collection_id', 
            'purpose_id', 'fengshui_id', 'price', 'stock', 'image', 'gender'
        ];
        
        $params = [];
        foreach ($fields as $field) {
            $value = $data[$field] ?? null; 
            
            // Chuyển chuỗi rỗng ("") thành NULL để tránh lỗi SQL cho các cột INT/FK
            if ($value === "") {
                $params[] = null;
            } else {
                $params[] = $value;
            }
        }

        if (isset($data['product_id']) && $data['product_id'] > 0) {
            // Update existing product
            $setClauses = implode('=?, ', $fields) . '=?';
            $sql = "UPDATE products SET {$setClauses} WHERE product_id=?";
            $params[] = $data['product_id'];
        } else {
            // Insert new product
            $placeholders = implode(', ', array_fill(0, count($fields), '?'));
            $fieldNames = implode(', ', $fields);
            $sql = "INSERT INTO products ({$fieldNames}) VALUES ({$placeholders})";
        }
        
        return $this->execute($sql, $params);
    }
}