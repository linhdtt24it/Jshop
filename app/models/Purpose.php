<?php
require_once __DIR__ . '/../core/Model.php';

class Purpose extends Model {
    public function getAllPurposes() {
        return $this->selectAll("SELECT * FROM purposes ORDER BY purpose_id ASC");
    }
}