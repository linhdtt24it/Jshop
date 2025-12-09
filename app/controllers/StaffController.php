<?php
// app/controllers/StaffController.php
session_start();

// 1. KIỂM TRA BẢO MẬT
if (!isset($_SESSION['user_id'])) {
    header("Location: /Jshop/public/login.php");
    exit;
}

// 2. XỬ LÝ LOGIC
$action = $_GET['action'] ?? 'dashboard';

if ($action == 'dashboard') {
    // --- CHUẨN BỊ DỮ LIỆU ---
    // QUAN TRỌNG: Tên biến phải là $user (không phải $user_display)
    $user = [
        'full_name' => $_SESSION['user_name'] ?? 'Nhân viên',
        'role'      => 'Sales Team', // Hoặc lấy từ session nếu có
        'avatar'    => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($_SESSION['user_name'] ?? 'Staff')
    ];

    // Số liệu KPI ví dụ
    $orders_pending = 5;
    $tasks_today = 12;
    $completed_orders = 28;

    // --- GỌI VIEW ---
    // File view sẽ dùng biến $user ở trên để hiển thị
    require_once __DIR__ . '/../views/staff/dashboard.php';
}
?>