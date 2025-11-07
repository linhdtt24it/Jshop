<?php
// config/constants.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ĐỊNH NGHĨA BASE_URL 1 LẦN DUY NHẤT
define('BASE_URL', 'http://localhost/Jshop/public/');
?>