<?php
require_once __DIR__ . '/../core/Model.php';

class News extends Model {
    protected $table = 'news';

    public function getLatestNews($limit = 3) {
        $limit = (int)$limit; 
        $sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT {$limit}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}