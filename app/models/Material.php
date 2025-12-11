<?php
require_once __DIR__ . '/../core/Model.php';

class Material extends Model {
    public function getAllMaterials() {
        return $this->selectAll("SELECT * FROM materials ORDER BY material_id ASC");
    }
}