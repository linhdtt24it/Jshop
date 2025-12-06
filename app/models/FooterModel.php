<?php

class FooterModel extends Model {
    public function getFooterLinks() {
        $sql = "SELECT * FROM footer_links ORDER BY group_name ASC";
        return $this->selectAll($sql);
    }
}
