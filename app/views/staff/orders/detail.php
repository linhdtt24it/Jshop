<?php
$ROOT_URL = str_replace('public/', '', BASE_URL ?? '/Jshop/public/');
$user_name = $_SESSION['user_name'] ?? 'Nhân viên';
$orders_total_pending = $data['orders_total_pending'] ?? 0;
$new_messages_count = $data['new_messages_count'] ?? 0;
$user = [
    'full_name' => $user_name, 
    'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['page_title'] ?? 'Chi tiết đơn hàng' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css">
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <i class="fas fa-gem brand-icon"></i>
                <div>
                    <h2>JSHOP</h2>
                    <span>STAFF PORTAL</span>
                </div>
            </a>
        </div>

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

        <div class="sidebar-footer">
            <a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </aside>

    <div class="main-panel">
        <header class="top-bar">
            <h4><?= $data['page_title'] ?></h4>
            <div class="user-profile"><img src="<?= $user['avatar'] ?>" alt="Staff"></div>
        </header>

        <main class="content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending">Đơn hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết #<?= $data['order']['order_id'] ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Chi tiết đơn hàng #<?= $data['order']['order_id'] ?></h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">In hóa đơn</button>
                    </div>
                    <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            Danh sách sản phẩm
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">SL</th>
                                            <th class="text-end">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_calc = 0;
                                        foreach ($data['items'] as $item): 
                                            $price = $item['price'] ?? 0;
                                            $quantity = $item['quantity'] ?? 0;
                                            $subtotal = $price * $quantity;
                                            $total_calc += $subtotal;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm') ?></div>
                                                <small class="text-muted">ID: <?= $item['product_id'] ?? 'N/A' ?></small>
                                            </td>
                                            <td class="text-center"><?= $quantity ?></td>
                                            <td class="text-end"><?= number_format($price) ?>₫</td>
                                            <td class="text-end"><?= number_format($subtotal) ?>₫</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Tổng tiền hàng:</td>
                                            <td class="text-end fw-bold"><?= number_format($total_calc) ?>₫</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold text-danger">TỔNG CỘNG:</td>
                                            <td class="text-end fw-bold text-danger fs-5"><?= number_format($data['order']['total_amount']) ?>₫</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            Cập nhật trạng thái
                        </div>
                        <div class="card-body">
                            <form action="<?= BASE_URL ?>../app/controllers/StaffController.php?action=update_order_status" method="POST">
                                <input type="hidden" name="order_id" value="<?= $data['order']['order_id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái hiện tại:</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" <?= $data['order']['order_status'] == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                        <option value="processing" <?= $data['order']['order_status'] == 'processing' ? 'selected' : '' ?>>Đang đóng gói</option>
                                        <option value="completed" <?= $data['order']['order_status'] == 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                        <option value="cancelled" <?= $data['order']['order_status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            Thông tin khách hàng
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Họ tên:</strong><br> <?= htmlspecialchars($data['order']['receiver_name']) ?></p>
                            <p class="mb-2"><strong>Số điện thoại:</strong><br> <?= htmlspecialchars($data['order']['receiver_phone']) ?></p>
                            <p class="mb-2"><strong>Địa chỉ:</strong><br> <?= htmlspecialchars($data['order']['shipping_address']) ?></p>
                            <hr>
                            <p class="mb-2"><strong>Phương thức thanh toán:</strong><br> <?= htmlspecialchars($data['order']['payment_method']) ?></p>
                            <p class="mb-0"><strong>Ngày đặt:</strong><br> <?= date('d/m/Y H:i', strtotime($data['order']['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>