<?php
require_once __DIR__ . '/../core/Model.php';

class Collection extends Model {

    public function getAllCollections() {
        return $this->selectAll("SELECT * FROM collections ORDER BY collection_id ASC");
    }

    public function getCollectionByIdOrSlug($identifier) {
        if (is_numeric($identifier)) {
            $sql = "SELECT * FROM collections WHERE collection_id = ?";
        } else {
            $sql = "SELECT * FROM collections WHERE slug = ?";
        }
        
        return $this->selectOne($sql, [$identifier]);
    }
    
    public function getLatestCollections($limit = 3) {
        $sql = "SELECT * FROM collections ORDER BY collection_id DESC LIMIT ?";
        return $this->selectAll($sql, [$limit]);
    }
    
    public function getCollectionById($id) {
        return $this->selectOne(
            "SELECT * FROM collections WHERE collection_id = ?", 
            [$id]
        );
    }

    public function createCollection($name, $slug, $description = null, $image = null) {
        $sql = "INSERT INTO collections (name, slug, description, image) VALUES (?, ?, ?, ?)";
        return $this->execute($sql, [$name, $slug, $description, $image]);
    }

    public function updateCollection($id, $name, $slug, $description = null, $image = null) {
        $sql = "UPDATE collections SET name = ?, slug = ?, description = ?, image = ? WHERE collection_id = ?";
        return $this->execute($sql, [$name, $slug, $description, $image, $id]);
    }

    public function deleteCollection($id) {
        $sql = "DELETE FROM collections WHERE collection_id = ?";
        return $this->execute($sql, [$id]);
    }
}