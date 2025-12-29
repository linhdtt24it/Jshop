<?php
require_once __DIR__ . '/../models/FooterGroup.php'; 
require_once __DIR__ . '/../models/FooterLink.php';
require_once __DIR__ . '/../models/FooterAsset.php';
require_once __DIR__ . '/Model.php'; 

class Controller {

    protected function model($model) {
        require_once __DIR__ . "/../models/$model.php";
        return new $model;
    }
    private function getFooterData() {
        $groupModel = $this->model("FooterGroup");
        $linkModel  = $this->model("FooterLink");
        $assetModel = $this->model("FooterAsset");

        $footer_groups = $groupModel->getAll();  
        
        foreach ($footer_groups as &$g) {
            $g['links'] = $linkModel->getByGroup($g['id']); 
        }
        $footer_assets = $assetModel->getAll(); 
        unset($g); 
        
        return [
            'footer_groups' => $footer_groups,
            'footer_assets' => $footer_assets
        ];
    }

    protected function view($view, $data = []) {
        
        $footerData = $this->getFooterData();
        $data = array_merge($data, $footerData);

        extract($data);

        $path = __DIR__ . "/../views/$view.php";
        if (file_exists($path)) {
            require_once $path;
        } else {
            die("View not found: $view");
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}