<?php

class FooterGroup extends Model {
    protected $table = 'footer_groups';

    public function getAll() {
        return $this->db->query("SELECT * FROM footer_groups ORDER BY sort_order")->fetchAll();
    }
}
