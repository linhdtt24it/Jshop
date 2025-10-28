<?php
// File: index.php

// Tự động load file controller, model khi cần
spl_autoload_register(function ($class) {
    $paths = [
        'app/controllers/' . $class . '.php',
        'app/models/' . $class . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Khởi tạo controller
switch ($controller) {
    case 'product':
        $ctrl = new ProductController();
        break;
    default:
        $ctrl = new HomeController();
        break;
}

// Gọi action
if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    echo "404 - Không tìm thấy trang!";
}
