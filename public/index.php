<?php
// public/index.php
session_start(); 

// ----------------------------------------------------------------------
// BƯỚC 1: ĐỊNH NGHĨA ROOT_PATH (Đường dẫn tuyệt đối đến thư mục Jshop)
// __DIR__ là thư mục public. dirname(__DIR__) đi ngược lên 1 cấp, chính là Jshop/
// ----------------------------------------------------------------------
define('ROOT_PATH', dirname(__DIR__)); 


require_once ROOT_PATH . "/config/constants.php"; // Sửa đường dẫn nếu cần
require_once ROOT_PATH . "/app/core/App.php";     // Sửa đường dẫn nếu cần

(new App())->run();