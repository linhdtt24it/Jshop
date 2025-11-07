<?php
// app/core/Controller.php
class Controller {
    public function view($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // View path
        $viewPath = "../app/views/{$view}.php";
        
        // Load view trực tiếp
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View không tồn tại: " . $viewPath);
        }
    }
}