<?php
$user = $data['user'] ?? [];
$orders_total_pending = $data['orders_total_pending'] ?? 0;
$new_messages_count = $data['new_messages_count'] ?? 0;
$ROOT_URL = str_replace('public/', '', BASE_URL ?? '/Jshop/public/');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Chi tiết đơn hàng') ?></title>
    <!-- Bootstrap cho layout nội dung đơn hàng -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Dashboard -->
    <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css?v=<?= time() ?>">
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
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
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
            <li>
                <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=orders_pending">
                    <i class="fas fa-clipboard-list"></i> 
                    <span>Đơn hàng chờ</span> 
                    <span class="badge"><?= $orders_total_pending ?></span>
                </a>
            </li>
            <!-- Mục mới: Chi tiết đơn hàng (Active) -->
            <li class="active">
                <a href="#">
                    <i class="fas fa-edit"></i> 
                    <span>Chi tiết đơn hàng</span>
                </a>
            </li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= $new_messages_count ?></span></a></li>
            
            <li class="menu-header">CÁ NHÂN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </aside>

    <div class="main-panel">
        <header class="top-bar">
            <div class="search-box">
                <h4 style="margin:0;">Chi tiết đơn hàng #<?= htmlspecialchars($order['order_id']) ?></h4>
            </div>
            <div class="user-area">
                <div class="user-profile">
                    <img src="<?= $user['avatar'] ?? '' ?>" alt="Staff"> 
                    <div>
                        <h4><?= $user['full_name'] ?? 'Staff' ?></h4>
                        <small>STAFF</small>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="StaffController.php?action=orders_pending" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
            </div>

            <div class="row">
                <!-- Thông tin người nhận -->
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-user me-2"></i> Thông tin khách hàng
                        </div>
                        <div class="card-body">
                            <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['receiver_name'] ?? 'N/A') ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['receiver_phone'] ?? 'N/A') ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_address'] ?? 'N/A') ?></p>
                            <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['created_at'] ?? '') ?></p>
                            <p><strong>Trạng thái:</strong> 
                                <?php 
                                    $status = strtolower($order['order_status'] ?? '');
                                    $badge_class = 'bg-secondary';
                                    $status_text = $order['order_status'];

                                    if ($status == 'pending') { $status_text = 'Chờ xác nhận'; $badge_class = 'bg-white text-dark'; }
                                    elseif ($status == 'processing') { $status_text = 'Đang đóng gói'; $badge_class = 'bg-primary'; }
                                    elseif ($status == 'completed') { $status_text = 'Đã hoàn thành'; $badge_class = 'bg-success'; }
                                    elseif ($status == 'cancelled') { $status_text = 'Đã hủy'; $badge_class = 'bg-danger'; }
                                ?>
                                <span class="badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($status_text) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Thông tin thanh toán / Ghi chú -->
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-info-circle me-2"></i> Thông tin khác
                        </div>
                        <div class="card-body">
                            <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></p>
                            <p><strong>Ghi chú:</strong> <?= htmlspecialchars($order['note'] ?? 'Không có') ?></p>
                            <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($order['total_amount'] ?? 0) ?> VNĐ</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <i class="fas fa-box me-2"></i> Danh sách sản phẩm & Cập nhật số lượng
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Tồn kho</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($order_items as $item): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($item['name'] ?? $item['product_name'] ?? 'Sản phẩm') ?></td>
                                <td>
                                    <?php 
                                        $img = $item['image'] ?? $item['product_image'] ?? '';
                                        if (!empty($img)): 
                                    ?>
                                        <img src="<?= BASE_URL . 'uploads/' . $img ?>" alt="Img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $stock = (int)($item['stock'] ?? 0);
                                        if ($stock <= 0) {
                                            echo '<span class="badge bg-danger">Hết hàng</span>';
                                        } elseif ($stock < 10) {
                                            echo '<span class="badge bg-warning text-dark">Cảnh báo: ' . $stock . '</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Sẵn có: ' . $stock . '</span>';
                                        }
                                    ?>
                                </td>
                                <td><?= number_format($item['price'] ?? 0) ?> VNĐ</td>
                                <td>
                                    <form action="StaffController.php?action=update_item_quantity" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?? 1 ?>" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-primary" title="Cập nhật"><i class="fas fa-sync-alt"></i></button>
                                    </form>
                                </td>
                                <td class="fw-bold"><?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) ?> VNĐ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Nút hành động (Duyệt / Hủy) -->
            <div class="mt-4 text-end">
                <?php $stt = strtolower($order['order_status'] ?? ''); ?>
                <?php if ($stt == 'pending'): ?>
                    <a href="StaffController.php?action=order_approve&id=<?= $order['order_id'] ?>" class="btn btn-success btn-lg" onclick="return confirm('Duyệt đơn hàng này?')"><i class="fas fa-check"></i> Duyệt đơn hàng</a>
                    <a href="StaffController.php?action=order_cancel&id=<?= $order['order_id'] ?>" class="btn btn-danger btn-lg" onclick="return confirm('Hủy đơn hàng này?')"><i class="fas fa-times"></i> Hủy đơn hàng</a>
                <?php elseif ($stt == 'processing'): ?>
                    <a href="StaffController.php?action=order_complete&id=<?= $order['order_id'] ?>" class="btn btn-primary btn-lg" onclick="return confirm('Xác nhận hoàn thành đơn hàng?')"><i class="fas fa-check-double"></i> Hoàn thành đơn</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>