<?php
// app/core/Controller.php
class Controller {

    // Load model
    protected function model($model) {
        require_once __DIR__ . "/../models/$model.php";
        return new $model;
    }

    // Load view
    protected function view($view, $data = []) {
        extract($data);

        $path = __DIR__ . "/../views/$view.php";
        if (file_exists($path)) {
            require_once $path;
        } else {
            die("View not found: $view");
        }
    }

    // JSON response
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
