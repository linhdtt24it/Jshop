<?php
// Lấy dữ liệu user và khởi tạo ROOT_URL từ Controller (hoặc định nghĩa lại nếu Controller không truyền)

$user_name = $_SESSION['user_name'] ?? 'Nhân viên';
$orders_pending = $data['orders_pending'] ?? 0; // Đơn chờ xác nhận
$orders_processing = $data['orders_processing'] ?? 0; // Đang đóng gói
$completed_orders = $data['completed_orders'] ?? 0; // Đã hoàn thành
$orders_total_pending = $data['orders_total_pending'] ?? 0; // Tổng chờ + xử lý

$user = [
    'full_name' => $user_name, 
    'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name),
    'new_messages_count' => $data['new_messages_count'] ?? 0 
];

// KHẮC PHỤC LỖI BASE_URL TRỎ ĐẾN CONTROLLER BỊ SAI
$ROOT_URL = str_replace('public/', '', BASE_URL ?? '/Jshop/public/'); 

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
   <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css?v=<?= time() ?>">
</head>
<body>

<div class="wrapper">
    
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-gem brand-icon"></i>
            <div>
                <h2>JSHOP</h2>
                <span>STAFF PORTAL</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">CÔNG VIỆC CỦA BẠN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard" class="active"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
            <li>
                <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending">
                    <i class="fas fa-clipboard-list"></i> 
                    <span>Đơn hàng chờ</span> 
                    <span class="badge"><?= $orders_total_pending ?></span>
                </a>
            </li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= $user['new_messages_count'] ?></span></a></li>
            <li><a href="#"><i class="fas fa-star"></i> <span>Đánh giá & KPI</span></a></li>
            
            <li class="menu-header">CÁ NHÂN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Lịch làm việc</span></a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </aside>

    <div class="main-panel">
        
        <header class="top-bar">
            <div class="search-box">
                <h4 style="margin:0;">Xin chào, chúc ngày mới tốt lành! ☀️</h4>
            </div>

            <div class="user-area">
                <div class="notify-icon"><i class="far fa-bell"></i><span class="dot"></span></div>
                <div class="user-profile">
                    <img src="<?= $user['avatar'] ?>" alt="Staff"> 
                    <div>
                        <h4><?= $user['full_name'] ?></h4>
                        <small>STAFF</small>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            
            <div class="page-header">
                <div>
                    <h1>Danh sách công việc</h1>
                    <p style="color: #64748b;">Bạn có <?= $orders_total_pending ?> đơn hàng cần xử lý hôm nay.</p>
                </div>
                <button class="btn-primary"><i class="fas fa-plus"></i> Tạo đơn tại quầy</button>
            </div>

            <div class="stats-container">
                <div class="card stat-card">
                    <div class="stat-icon bg-primary-light"><i class="fas fa-clipboard-check"></i></div>
                    <div class="stat-info">
                        <h3><?= sprintf('%02d', $orders_pending) ?></h3>
                        <p>Đơn chờ xác nhận</p>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon bg-blue-light"><i class="fas fa-box-open"></i></div>
                    <div class="stat-info">
                        <h3><?= sprintf('%02d', $orders_processing) ?></h3>
                        <p>Đang đóng gói</p>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon bg-green-light"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= sprintf('%02d', $completed_orders) ?></h3>
                        <p>Đã hoàn thành</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Đơn hàng cần xử lý gấp</h3>
                    <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending">Xem tất cả</a>
                </div>
                <table class="table-minimal">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#DH0921</strong></td>
                            <td>Nguyễn Thị A <br><small style="color:#999">0987***123</small></td>
                            <td>Nhẫn Bạc PNJ...</td>
                            <td><span class="status-badge status-pending">Chờ duyệt</span></td>
                            <td><a href="#" style="color: var(--color-primary); font-weight:bold">Xử lý ngay</a></td>
                        </tr>
                        <tr>
                            <td><strong>#DH0922</strong></td>
                            <td>Trần Văn B <br><small style="color:#999">0912***456</small></td>
                            <td>Dây chuyền vàng...</td>
                            <td><span class="status-badge status-shipping">Đang giao</span></td>
                            <td><a href="#" style="color:#64748b">Chi tiết</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</body>
</html>