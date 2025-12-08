<?php
require_once __DIR__ . '/../core/Model.php'; 
class FooterModel extends Model {
    public function getFooterLinks() {
        $sql = "SELECT * FROM footer_links ORDER BY group_name ASC";
        $rows = $this->selectAll($sql);

        foreach ($rows as &$r) {
            if (!empty($r['url'])) {
                $r['url'] = BASE_URL . ltrim($r['url'], '/');
            }
        }
        return $rows;
    }
}
