<?php
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
}
