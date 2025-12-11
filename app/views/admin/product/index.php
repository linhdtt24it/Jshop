<?php
// Jshop/app/views/admin/product/index.php

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
    <title>Quản lý Sản phẩm • JSHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/admin-dashboard.css">
    <style>
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-custom th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #666; font-weight: 700; }
        .table-custom td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .product-img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .btn-navy { background-color: #0f172a; color: white !important; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; }
        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 5px; color: white; margin: 0 3px; border: none; cursor: pointer; text-decoration: none; }
        .btn-edit { background: #3b82f6; } 
        .btn-delete { background: #ef4444; } 
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
            <li><a href="<?= $ADMIN_URL ?>?action=product_list" class="active"><i class="fas fa-ring"></i> Quản lý Sản phẩm</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=inventory_list"><i class="fas fa-box-open"></i> Quản lý Kho hàng</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=collections_list"><i class="fas fa-layer-group"></i> Quản lý Bộ sưu tập</a></li>
        </ul>
        <div class="logout"><a href="/Jshop/app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Danh sách Sản phẩm</h1>
                <p>Quản lý tất cả sản phẩm, tạo mới và sửa chi tiết</p>
            </div>
            <div class="user-info"><img src="<?= $user['avatar'] ?>" alt="Admin"></div>
        </header>

        <div class="content-grid" style="grid-template-columns: 1fr;">
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Tổng: <?= count($products) ?> sản phẩm</h3>
                    <a href="<?= $ADMIN_URL ?>?action=add_product" class="btn-navy"><i class="fas fa-plus"></i> Thêm sản phẩm mới</a>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>SL tồn</th>
                                <th>Danh mục</th>
                                <th>Bộ sưu tập</th>
                                <th style="text-align: center;">Hành động</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                            <tr id="row-product-<?= $p['product_id'] ?>">
                                <td>#<?= $p['product_id'] ?></td>
                                <td><img src="<?= htmlspecialchars($p['image'] ?? $BASE_URL.'images/no-image.jpg') ?>" alt="Thumb" class="product-img-thumb"></td>
                                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                                <td><?= number_format($p['price']) ?>₫</td>
                                <td><?= $p['stock'] ?></td>
                                <td><small class="text-muted"><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></small></td>
                                <td><small class="text-muted"><?= htmlspecialchars($p['collection_name'] ?? 'N/A') ?></small></td>
                                <td style="text-align: center;">
                                    <a href="<?= $ADMIN_URL ?>?action=edit_product&id=<?= $p['product_id'] ?>" class="btn-action btn-edit" title="Chỉnh sửa chi tiết"><i class="fas fa-edit"></i></a>
                                    <button class="btn-action btn-delete" onclick="deleteProduct(<?= $p['product_id'] ?>)" title="Xóa sản phẩm"><i class="fas fa-trash-alt"></i></button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ADMIN_CONTROLLER_URL = '<?= $ADMIN_URL ?>';
</script>
<script src="<?= $BASE_URL ?>assets/js/admin-product.js"></script> 
</body>
</html>