<?php
// app/models/Collection.php
class Collection {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // LẤY TẤT CẢ BỘ SƯU TẬP + SỐ SẢN PHẨM
    public function getAllWithProductCount() {
        $sql = "
            SELECT 
                c.collection_id, c.name, c.slug, c.description, c.image, c.created_at,
                COUNT(p.product_id) as product_count
            FROM collections c
            LEFT JOIN products p ON p.collection_id = c.collection_id
            GROUP BY c.collection_id
            ORDER BY c.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LẤY BỘ SƯU TẬP THEO SLUG
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM collections WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // LẤY SẢN PHẨM CỦA BỘ SƯU TẬP
    public function getProductsByCollectionId($collection_id) {
        $stmt = $this->db->prepare("
            SELECT p.* FROM products p 
            WHERE p.collection_id = ? 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$collection_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}