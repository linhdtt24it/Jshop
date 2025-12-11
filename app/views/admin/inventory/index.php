<?php
// Jshop/app/views/admin/inventory/index.php

$user = [
    'name' => $_SESSION['user_name'] ?? 'Admin', 
    'role' => 'Administrator',
    'avatar' => 'https://ui-avatars.com/api/?background=000&color=d4af37&name=' . urlencode($_SESSION['user_name'] ?? 'Admin')
];

$BASE_URL = '/Jshop/public/'; 
$ADMIN_URL = '/Jshop/app/controllers/AdminController.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Kho hàng • JSHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/admin-dashboard.css">
    <style>
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-custom th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #666; font-weight: 700; }
        .table-custom td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .product-img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .stock-low { background-color: #fef2f2; color: #b91c1c; font-weight: 700; padding: 5px 10px; border-radius: 4px; }
        .stock-ok { background-color: #f0fdf4; color: #15803d; font-weight: 700; padding: 5px 10px; border-radius: 4px; }
        .btn-navy { background-color: #0f172a; color: white !important; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; }
        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 5px; color: white; margin: 0 3px; border: none; cursor: pointer; text-decoration: none; }
        .btn-sync { background: #10b981; } 
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand"><i class="fas fa-gem"></i><div class="brand-text"><h2>JSHOP</h2><span>ADMINISTRATOR</span></div></div>
        <ul class="menu">
            <li class="label">QUẢN TRỊ</li>
            <li><a href="<?= $ADMIN_URL ?>?action=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="label">CỬA HÀNG</li>
            <li><a href="<?= $ADMIN_URL ?>?action=product_list"><i class="fas fa-ring"></i> Quản lý Sản phẩm</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=inventory_list" class="active"><i class="fas fa-box-open"></i> Quản lý Kho hàng</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=collections_list"><i class="fas fa-layer-group"></i> Quản lý Bộ sưu tập</a></li>
        </ul>
        <div class="logout"><a href="/Jshop/app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Quản lý Kho hàng</h1>
                <p>Kiểm tra số lượng tồn kho và tình trạng cảnh báo</p>
            </div>
            <div class="user-info"><img src="<?= $user['avatar'] ?>" alt="Admin"></div>
        </header>

        <div class="content-grid" style="grid-template-columns: 1fr;">
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Tổng: <?= count($products) ?> SKU</h3>
                    <button class="btn-navy"><i class="fas fa-file-excel"></i> Xuất báo cáo</button>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>SL tồn</th>
                                <th style="text-align: center;">Tình trạng</th> 
                                <th style="text-align: center;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                            <?php $is_low_stock = $p['stock'] < 5 && $p['stock'] > 0; $is_out_of_stock = $p['stock'] == 0; ?>
                            <tr id="row-inventory-<?= $p['product_id'] ?>" class="<?= $is_out_of_stock ? 'table-danger' : ($is_low_stock ? 'table-warning' : '') ?>">
                                <td>#<?= $p['product_id'] ?></td>
                                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                                <td><?= number_format($p['price']) ?>₫</td>
                                <td><?= $p['stock'] ?></td>
                                <td style="text-align: center;">
                                    <?php if ($is_out_of_stock): ?>
                                        <span class="stock-low">Hết hàng</span>
                                    <?php elseif ($is_low_stock): ?>
                                        <span class="stock-low">Cảnh báo tồn kho</span>
                                    <?php else: ?>
                                        <span class="stock-ok">Còn hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn-action btn-sync" onclick="openStockModal(<?= $p['product_id'] ?>)" title="Cập nhật tồn kho"><i class="fas fa-sync-alt"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="inventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Cập nhật Tồn kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <input type="hidden" name="product_id" id="productId">
                    <div class="mb-3">
                        <p class="mb-1">Sản phẩm: <strong id="productName"></strong></p>
                        <p class="mb-3">Số lượng hiện tại: <span class="fw-bold text-primary" id="currentStock">0</span></p>
                    </div>
                    <div class="mb-3">
                        <label for="newStock" class="form-label fw-bold">Số lượng tồn kho MỚI</label>
                        <input type="number" class="form-control" name="stock" id="newStock" min="0" required>
                    </div>
                    <button type="submit" class="btn-navy w-100 justify-content-center"><i class="fas fa-save me-2"></i> Lưu cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ADMIN_CONTROLLER_URL = '<?= $ADMIN_URL ?>';
</script>
<script src="<?= $BASE_URL ?>assets/js/admin-inventory.js"></script> 
</body>
</html>