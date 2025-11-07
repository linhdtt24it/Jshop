<?php
// public/products.php
require_once "../config/constants.php";
require_once "../app/controllers/ProductController.php";

// === ROUTE ===
$action = $_GET['action'] ?? 'index';
$controller = new ProductController();

if ($action === 'detail' && !empty($_GET['id'])) {
    $controller->detail($_GET['id']);
} else {
    $controller->index();
}