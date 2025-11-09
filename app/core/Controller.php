<?php
// app/core/Controller.php
class Controller {
    // Phương thức để load view
    protected function view($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Load view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: " . $view);
        }
    }
    
    // Phương thức để trả về JSON response
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>