<?php
require_once __DIR__ . '/../core/Model.php'; 
class FooterLink extends Model {
    protected $table = 'footer_links';

    public function getByGroup($group_id) {
        $stmt = $this->db->prepare("SELECT * FROM footer_links WHERE group_id = ? ORDER BY sort_order");
        $stmt->execute([$group_id]);
        return $stmt->fetchAll();
    }
}
