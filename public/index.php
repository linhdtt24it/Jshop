<?php
// public/index.php
session_start(); // ← BẮT BUỘC: cho login, giỏ hàng
require_once "../config/constants.php";
require_once "../app/core/App.php";

// Khởi động ứng dụng
(new App())->run();