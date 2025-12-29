<?php
require_once "../config/constants.php";
require_once "../app/controllers/CartController.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['login_required' => true]);
    exit;
}

$controller = new CartController();
$controller->add();