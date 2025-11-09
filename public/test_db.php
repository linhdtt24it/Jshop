<?php
require_once "../config/database.php";

try {
    $db = (new Database())->connect();
    echo "Database connected successfully!";
    
    // Test users table
    $stmt = $db->query("SELECT * FROM users LIMIT 1");
    $user = $stmt->fetch();
    echo "<br>Users table exists!";
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
?>