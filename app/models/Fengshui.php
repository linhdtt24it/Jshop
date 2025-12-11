<?php
require_once __DIR__ . '/../core/Model.php';

class Fengshui extends Model {
    public function getAllFengshuiItems() {
        return $this->selectAll("SELECT * FROM fengshui ORDER BY fengshui_id ASC");
    }
}