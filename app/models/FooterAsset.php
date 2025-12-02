<?php
class FooterAsset extends Model {
    protected $table = 'footer_assets';

    public function getAll() {
        return $this->db->query("SELECT * FROM footer_assets ORDER BY sort_order")->fetchAll();
    }
}
