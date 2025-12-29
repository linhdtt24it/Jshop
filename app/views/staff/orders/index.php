<?php 
$orders = $data['orders'] ?? [];

$user_name = $_SESSION['user_name'] ?? 'Nhân viên';
$orders_total_pending = $data['orders_total_pending'] ?? 0; 
$new_messages_count = count(array_filter($data['messages'] ?? [], fn($m) => $m['status'] === 'new'));
$user = ['full_name' => $user_name, 'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)];

$ROOT_URL = str_replace('public/', '', BASE_URL ?? '/Jshop/public/'); 

function displayStatus($status) {
    switch ($status) {
       
        case 'pending': 
            $badge_class = 'white text-dark';
            $text = 'Chờ xác nhận';
            break;
        case 'processing': 
            $badge_class = 'pink';
            $text = 'Đang đóng gói'; 
            break;
        case 'shipped': 
            $badge_class = 'info'; 
            $text = 'Đang giao';
            break;
        case 'completed': 
            $badge_class = 'success';
            $text = 'Đã hoàn thành';
            break;
        case 'cancelled': 
            $badge_class = 'secondary'; 
            $text = 'Đã hủy';
            break;
        
        case 'paid':
            $badge_class = 'success';
            $text = 'Đã TT';
            break;
        case 'failed':
            $badge_class = 'danger';
            $text = 'Thất bại';
            break;
        
        case 'COD':
            $badge_class = 'dark';
            $text = 'COD';
            break;
        case 'BANK_TRANSFER':
            $badge_class = 'secondary';
            $text = 'CK Ngân hàng';
            break;
        case 'MOMO':
            $badge_class = 'pink';
            $text = 'Momo';
            break;
        case 'ZALOPAY':
            $badge_class = 'blue';
            $text = 'ZaloPay';
            break;
        
        default: 
            $badge_class = 'light';
            $text = $status;
    }
    return '<span class="badge bg-'. $badge_class .'">'. $text .'</span>';
}

function getRowClass($status) {
    return ''; 
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css">
    
    <style>
        #order-table thead th {
            border-bottom: 2px solid #a0aec0;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            font-weight: 700;
            color: #2d3748;
        }
        
        #order-table th,
        #order-table td {
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
            vertical-align: middle;
        }
        
        #order-table th:first-child,
        #order-table td:first-child {
            padding-left: 1rem !important;
        }
        
        #order-table td:last-child a.btn {
            white-space: nowrap;
        }
        .badge.bg-dark { background-color: #4a5568 !important; color: #fff; }
        .badge.bg-secondary { background-color: #a0aec0 !important; color: #fff; }
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
                <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending" class="active">
                    <i class="fas fa-clipboard-list"></i> 
                    <span>Đơn hàng chờ</span> 
                    <span class="badge"><?= $orders_total_pending ?></span>
                </a>
            </li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= $new_messages_count ?></span></a></li>
            <li><a href="#"><i class="fas fa-star"></i> <span>Đánh giá & KPI</span></a></li>
            
            <li class="menu-header">CÁ NHÂN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Lịch làm việc</span></a></li>
        </ul>
        <div class="sidebar-footer"><a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></div>
    </aside>

    <div class="main-panel">
        <header class="top-bar">
            <h4><?= $page_title ?></h4>
            <div class="user-profile"><img src="<?= $user['avatar'] ?>" alt="Staff"></div>
        </header>

        <main class="content">
            <div class="page-header">
                <div>
                    <h1><?= $page_title ?></h1>
                    <p style="color: var(--text-light);">Danh sách các đơn hàng cần xác nhận và xử lý.</p>
                </div>
                <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending" class="btn-primary"><i class="fas fa-redo"></i> Tải lại</a>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="card p-4"> 
                <div class="table-responsive">
                    <?php if (empty($orders)): ?>
                        <div style="padding: 16px 0; font-size: 13px; color: var(--text-light);">Hiện chưa có đơn hàng nào cần xử lý.</div>
                    <?php else: ?>
                        <table id="order-table" class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 15%;">Khách hàng</th>
                                    <th style="width: 10%;">Tổng tiền</th>
                                    <th style="width: 15%;">Địa chỉ</th> 
                                    <th style="width: 10%;">PT TT</th>
                                    <th style="width: 12%;">TT Thanh toán</th>
                                    <th style="width: 12%;">TT Xử lý</th>
                                    <th style="width: 12%;">Ngày tạo</th>
                                    <th style="width: 9%;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr class="<?= getRowClass($order['order_status']) ?>"> 
                                        <td>#<?= $order['order_id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></strong>
                                            <br><small class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($order['receiver_phone']) ?></small>
                                        </td>
                                        <td><?= number_format($order['total_amount']) ?>đ</td>
                                        <td><?= mb_strimwidth(htmlspecialchars($order['shipping_address']), 0, 30, "...") ?></td>
                                        <td><?= displayStatus($order['payment_method']) ?></td>
                                        <td><?= displayStatus($order['payment_status']) ?></td>
                                        <td><?= displayStatus($order['order_status']) ?></td>
                                        <td><?= date('H:i d/m', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=order_detail&id=<?= $order['order_id'] ?>" class="btn btn-sm btn-dark">
                                                Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>