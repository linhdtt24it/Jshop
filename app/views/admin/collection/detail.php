<?php 
// Jshop/app/views/admin/collection/detail.php

// Dữ liệu được truyền từ AdminController thông qua $data['collection'] và $data['products']
$collection = $data['collection'] ?? [];
$products = $data['products'] ?? [];

// Lấy user info cho header
$user_name = $_SESSION['user_name'] ?? 'Admin';
$user = [
    'full_name' => $user_name, 
    'role' => 'Administrator',
    'avatar' => 'https://ui-avatars.com/api/?background=000&color=d4af37&name=' . urlencode($user_name)
];

// Định nghĩa URL cơ sở (giống các file admin khác)
$BASE_URL = '/Jshop/public/';
$ADMIN_URL = '/Jshop/app/controllers/AdminController.php';

$collection_name = htmlspecialchars($collection['name'] ?? 'Bộ sưu tập không rõ');
$collection_id = htmlspecialchars($collection['collection_id'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm Bộ sưu tập: <?= $collection_name ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/admin-dashboard.css">
    
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .main-content { flex-grow: 1; padding: 20px; }
        .header-info { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .header-info h2 { color: #1e3a8a; margin-bottom: 5px; font-weight: 700; }
        .header-info p { color: #6b7280; font-size: 0.95rem; }
        
        .product-img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; }
        .table-custom-product th { background-color: #f3f4f6; color: #4b5563; font-weight: 600; }
        .table-custom-product td { vertical-align: middle; }
        .card-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 5px; color: white; margin: 0 3px; border: none; cursor: pointer; text-decoration: none; }
        .btn-edit { background: #3b82f6; } 
        .btn-delete { background: #ef4444; } 
        .btn-products { background: #1e3a8a; }
        .total-badge { background-color: #10b981; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand">
            <i class="fas fa-gem"></i>
            <div class="brand-text"><h2>JSHOP</h2><span>ADMINISTRATOR</span></div>
        </div>
        <ul class="menu">
            <li class="label">QUẢN TRỊ</li>
            <li><a href="<?= $ADMIN_URL ?>?action=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="label">CỬA HÀNG</li>
            <li><a href="<?= $ADMIN_URL ?>?action=product_list"><i class="fas fa-ring"></i> Quản lý Sản phẩm</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=inventory_list"><i class="fas fa-box-open"></i> Quản lý Kho hàng</a></li>
            <li><a href="<?= $ADMIN_URL ?>?action=collections_list" class="active"><i class="fas fa-layer-group"></i> Quản lý Bộ sưu tập</a></li>
        </ul>
        <div class="logout">
             <a href="/Jshop/app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Sản phẩm Bộ sưu tập</h1>
                <p>Chi tiết các sản phẩm thuộc **<?= $collection_name ?>**</p>
            </div>
            <div class="user-info">
                <img src="<?= $user['avatar'] ?>" alt="Admin">
            </div>
        </header>

        <div class="header-info row g-3">
            <div class="col-md-9">
                <h2><?= $collection_name ?> (ID: <?= $collection_id ?>)</h2>
                <p>
                    <i class="fas fa-info-circle me-1"></i> 
                    Mô tả: <?= htmlspecialchars($collection['description'] ?? 'Không có mô tả.') ?>
                </p>
                <p>
                    <i class="fas fa-tag me-1"></i> 
                    Slug: `<?= htmlspecialchars($collection['slug'] ?? 'N/A') ?>`
                </p>
                <a href="<?= $ADMIN_URL ?>?action=collections_list" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại Danh sách Bộ sưu tập
                </a>
            </div>
            <div class="col-md-3 text-end">
                <p class="mb-1 text-muted">Tổng Sản phẩm:</p>
                <span class="total-badge fs-4"><?= count($products) ?></span>
                <a href="<?= $ADMIN_URL ?>?action=edit_product&collection_id=<?= $collection_id ?>" class="btn btn-primary mt-3 w-100">
                    <i class="fas fa-plus"></i> Thêm Sản phẩm
                </a>
            </div>
        </div>

        <div class="card-container">
            <h4 class="mb-4 text-secondary">DANH SÁCH SẢN PHẨM</h4>
            <table class="table table-striped table-custom-product">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Tên Sản phẩm</th>
                        <th>Giá (VNĐ)</th>
                        <th>Tồn kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có sản phẩm nào thuộc bộ sưu tập này.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($product['product_id']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($product['image'] ?? $BASE_URL.'images/placeholder.jpg') ?>" 
                                     alt="Image" class="product-img-thumb">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                            </td>
                            <td><?= number_format($product['price'] ?? 0, 0, ',', '.') ?> VNĐ</td>
                            <td>
                                <span class="badge bg-<?= ($product['stock'] ?? 0) > 0 ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars($product['stock'] ?? 0) ?>
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="<?= $ADMIN_URL ?>?action=edit_product&id=<?= $product['product_id'] ?>" 
                                   class="btn-action btn-edit" title="Chỉnh sửa sản phẩm"><i class="fas fa-edit"></i></a>
                                
                                <button class="btn-action btn-delete delete-product-btn" 
                                        data-id="<?= $product['product_id'] ?>" title="Xóa sản phẩm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $BASE_URL ?>assets/js/admin-product.js"></script> 
</body>
</html>