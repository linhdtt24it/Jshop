<?php
require_once __DIR__ . '/../../config/database.php';

class Model extends Database {

    // LẤY NHIỀU DÒNG
    public function selectAll($sql, $params = []) {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LẤY 1 DÒNG
    public function selectOne($sql, $params = []) {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}