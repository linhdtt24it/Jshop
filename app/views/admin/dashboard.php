<?php

$ADMIN_URL = '/Jshop/app/controllers/AdminController.php';

$user = $user ?? [
    'name' => $_SESSION['user_name'] ?? 'Admin', 
    'role' => 'Administrator',
    'avatar' => 'https://ui-avatars.com/api/?background=000&color=d4af37&name=' . urlencode($_SESSION['user_name'] ?? 'Admin')
];
$stats = $stats ?? [
    'revenue' => '1.25 Tỷ', 
    'orders' => 1240, 
    'new_customers' => 85, 
    'inventory' => 3500
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control • JSHOP Luxury</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/Jshop/public/assets/css/admin-dashboard.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand">
            <i class="fas fa-gem"></i>
            <div class="brand-text">
                <h2>JSHOP</h2>
                <span>ADMINISTRATOR</span>
            </div>
        </div>

        <ul class="menu">
            <li class="label">QUẢN TRỊ</li>
            <li><a href="<?= $ADMIN_URL ?>?action=dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-chart-pie"></i> Báo cáo doanh thu</a></li>
            
            <li class="label">CỬA HÀNG</li>
            <li><a href="<?= $ADMIN_URL ?>?action=product_list"><i class="fas fa-ring"></i> Quản lý Sản phẩm</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=inventory_list"><i class="fas fa-box-open"></i> Quản lý Kho hàng</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=collections_list"><i class="fas fa-layer-group"></i> Quản lý Bộ sưu tập</a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng toàn hệ thống</a></li>

            <li class="label">NHÂN SỰ & CÀI ĐẶT</li>
            <li>
                <a href="<?= $ADMIN_URL ?>?action=staff_list">
                    <i class="fas fa-users-cog"></i> Quản lý Nhân viên
                </a>
            </li>
            <li><a href="#"><i class="fas fa-cogs"></i> Cấu hình chung</a></li>
        </ul>
        
        <div class="logout">
            <a href="/Jshop/app/controllers/AuthController.php?action=logout" onclick="return confirm('Sếp muốn đăng xuất hả?');">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Tổng Quan Kinh Doanh</h1>
                <p>Chào mừng trở lại, sếp <strong><?= $user['name'] ?></strong></p>
            </div>
            <div class="user-info">
                <img src="<?= $user['avatar'] ?>" alt="Admin">
            </div>
        </header>

        <div class="stats-grid">
            <div class="card stat-card gold-card">
                <div class="icon"><i class="fas fa-coins"></i></div>
                <div>
                    <h3><?= $stats['revenue'] ?></h3>
                    <p>Doanh thu tháng này</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <div>
                    <h3><?= number_format($stats['orders']) ?></h3>
                    <p>Tổng đơn hàng</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div>
                    <h3><?= number_format($stats['new_customers']) ?></h3>
                    <p>Khách hàng mới</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="icon"><i class="fas fa-gem"></i></div>
                <div>
                    <h3><?= number_format($stats['inventory']) ?></h3>
                    <p>Sản phẩm tồn kho</p>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card chart-box">
                <div class="card-head">
                    <h3>Biểu đồ lợi nhuận</h3>
                    <button class="btn-outline">Xuất báo cáo</button>
                </div>
                <div class="placeholder-chart">
                    <div class="bar h-40"></div>
                    <div class="bar h-60"></div>
                    <div class="bar h-80 active"></div>
                    <div class="bar h-50"></div>
                    <div class="bar h-70"></div>
                </div>
            </div>
            
            <div class="card staff-active">
                <div class="card-head">
                    <h3>Nhân viên đang online</h3>
                </div>
                <ul class="staff-list">
                    <li>
                        <div class="dot green"></div>
                        <span>Nhân viên A (Sales)</span>
                    </li>
                    <li>
                        <div class="dot green"></div>
                        <span>Nhân viên B (Kho)</span>
                    </li>
                    <li>
                        <div class="dot orange"></div>
                        <span>Nhân viên C (Đang nghỉ)</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>
</div>
</body>
</html>