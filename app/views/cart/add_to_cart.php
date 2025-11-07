<?php
// public/add_to_cart.php
require_once "../config/constants.php";
require_once "../app/controllers/CartController.php";

session_start(); // ĐẢM BẢO SESSION ĐƯỢC BẮT ĐẦU

// NẾU CHƯA ĐĂNG NHẬP → BẮT ĐĂNG NHẬP
if (!isset($_SESSION['user'])) {
    echo json_encode(['login_required' => true]);
    exit;
}

// ĐÃ LOGIN → TIẾP TỤC THÊM GIỎ
$controller = new CartController();
$controller->add();