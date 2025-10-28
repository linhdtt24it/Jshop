<?php
require_once "config/database.php";
$db = new Database();
$conn = $db->connect();

if ($conn) echo "✅ Kết nối CSDL jshop thành công!";
else echo "❌ Kết nối thất bại!";
?>
