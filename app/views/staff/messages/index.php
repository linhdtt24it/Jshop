<?php
$user_name = $_SESSION['user_name'] ?? 'Nhân viên';
$user = ['full_name' => $user_name, 'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)];

// KHẮC PHỤC LỖI BASE_URL TRỎ ĐẾN CONTROLLER BỊ SAI
// BASE_URL được định nghĩa trong Controller trước khi load view.
$ROOT_URL = str_replace('public/', '', BASE_URL);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Tin nhắn • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css">
    
    <style>
        /* Tăng padding và đường viền dày hơn cho hàng tiêu đề */
        #message-table thead th {
            border-bottom: 2px solid #a0aec0; /* Đường phân cách dày hơn */
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            font-weight: 700;
            color: #2d3748; /* Màu tiêu đề đậm hơn */
        }
        
        /* Tăng padding ngang cho tất cả ô trong bảng */
        #message-table th,
        #message-table td {
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
            vertical-align: middle; /* Căn giữa theo chiều dọc */
        }
        
        #message-table th:first-child,
        #message-table td:first-child {
            padding-left: 1rem !important;
        }
        
        #message-table td:last-child a.btn {
            white-space: nowrap;
        }
    </style>
    </head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-gem brand-icon"></i><div><h2>JSHOP</h2><span>STAFF PORTAL</span></div></div>
        <ul class="sidebar-menu">
            <li class="menu-header">CÔNG VIỆC CỦA BẠN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
             <li>
                <a href="#">
                    <i class="fas fa-clipboard-list"></i> 
                    <span>Đơn hàng chờ</span> 
                    <span class="badge">5</span>
                </a>
            </li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages" class="active"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= count(array_filter($messages, fn($m) => $m['status'] === 'new')) ?></span></a></li>
            <li><a href="#"><i class="fas fa-star"></i> <span>Đánh giá & KPI</span></a></li>
            
            <li class="menu-header">CÁ NHÂN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Lịch làm việc</span></a></li>
        </ul>
        <div class="sidebar-footer"><a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></div>
    </aside>

    <div class="main-panel">
        <header class="top-bar">
            <h4>Quản lý Tin nhắn khách hàng</h4>
            <div class="user-profile"><img src="<?= $user['avatar'] ?>" alt="Staff"></div>
        </header>

        <main class="content">
            <div class="card p-4">
                <table id="message-table" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Tóm tắt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                        <tr class="<?= $msg['status'] === 'new' ? 'table-warning' : ($msg['status'] === 'in_progress' ? 'table-info' : 'table-light') ?>">
                            <td>#<?= $msg['message_id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($msg['full_name']) ?></strong>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($msg['email']) ?></small>
                            </td>
                            <td><?= date('H:i d/m', strtotime($msg['created_at'])) ?></td>
                            <td>
                                <?php 
                                    $badge = ['new' => 'danger', 'in_progress' => 'primary', 'closed' => 'success'][$msg['status']];
                                    $text = ['new' => 'Mới', 'in_progress' => 'Đang xử lý', 'closed' => 'Đã đóng'][$msg['status']];
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $text ?></span>
                            </td>
                            <td><?= mb_strimwidth(strip_tags($msg['message']), 0, 50, "...") ?></td>
                            <td>
                                <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=message_detail&id=<?= $msg['message_id'] ?>" class="btn btn-sm btn-dark">
                                    Chi tiết & Trả lời
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>   
                </table>
            </div>  
        </main>
    </div>
</div>