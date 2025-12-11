<?php
// Jshop/app/core/Model.php
require_once __DIR__ . '/../../config/database.php';
class Model {
    protected $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function selectAll($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectOne($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Phương thức thực thi câu lệnh CSDL (INSERT, UPDATE, DELETE).
     * Được giả định tồn tại để Model con có thể dùng.
     */
    public function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
// Đã xóa dấu đóng ngoặc nhọn thừa ở đây
?>