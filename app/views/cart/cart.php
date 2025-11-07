<?php
// public/cart.php
require_once "../config/constants.php";
require_once "../app/controllers/CartController.php";

$controller = new CartController();
$controller->index(); // Gọi hàm hiển thị
?>