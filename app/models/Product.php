<?php
require_once __DIR__ . '/../../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }


    public function getMenProducts($material_id = null, $purpose_id = null)
    {
        $query = "SELECT p.*, m.name AS material_name, pu.name AS purpose_name,
                         (SELECT image_url FROM product_images WHERE product_id = p.product_id LIMIT 1) AS image
                  FROM products p
                  LEFT JOIN materials m ON p.material_id = m.material_id
                  LEFT JOIN purposes pu ON p.purpose_id = pu.purpose_id
                  WHERE p.category_id = 1";

        $params = [];

        if (!empty($material_id)) {
            $query .= " AND p.material_id = :material_id";
            $params['material_id'] = $material_id;
        }

        if (!empty($purpose_id)) {
            $query .= " AND p.purpose_id = :purpose_id";
            $params['purpose_id'] = $purpose_id;
        }

        $query .= " ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
