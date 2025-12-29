<?php

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
    <title>Quản lý Bộ sưu tập • JSHOP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/admin-dashboard.css">
    
    <style>
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-custom th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #666; font-weight: 700; }
        .table-custom td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .btn-navy { background-color: #0f172a; color: white !important; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-navy:hover { background-color: #1e293b; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.4); }
        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 5px; color: white; margin: 0 3px; border: none; cursor: pointer; text-decoration: none; }
        .btn-edit { background: #3b82f6; } 
        .btn-delete { background: #ef4444; } 
        .coll-img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
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
             <a href="/Jshop/app/controllers/AuthController.php?action=logout" onclick="return confirm('Sếp muốn đăng xuất hả?');">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Quản lý Bộ sưu tập</h1>
                <p>Thêm, sửa, xóa các bộ sưu tập trang sức</p>
            </div>
            <div class="user-info">
                <img src="<?= $user['avatar'] ?>" alt="Admin">
            </div>
        </header>

        <div class="content-grid" style="grid-template-columns: 1fr;">
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Tổng: <?= count($collections) ?> bộ sưu tập</h3>
                    <button class="btn-navy" onclick="openCollectionModal()"><i class="fas fa-plus"></i> Thêm mới</button>
                </div>

                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên Bộ sưu tập</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th style="text-align: center;">Hành động</th> 
                        </tr>
                    </thead>
                    <tbody id="collectionTableBody">
                        <?php foreach ($collections as $coll): ?>
                        <tr id="row-<?= $coll['collection_id'] ?>">
                            <td>#<?= $coll['collection_id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($coll['image'] ?? $BASE_URL.'images/no-image.jpg') ?>" alt="Thumb" class="coll-img-thumb">
                            </td>
                            <td><strong><?= htmlspecialchars($coll['name'] ?? 'N/A') ?></strong></td>
                            <td><small class="text-muted"><?= htmlspecialchars($coll['slug'] ?? 'N/A') ?></small></td>
                            <td><?= mb_strimwidth(htmlspecialchars($coll['description'] ?? ''), 0, 50, "...") ?></td>
                            <td style="text-align: center;">
                                <button class="btn-action btn-edit" onclick="editCollection(<?= $coll['collection_id'] ?>)"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete" onclick="deleteCollection(<?= $coll['collection_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="collectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Thêm Bộ sưu tập mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="collectionForm">
                    <input type="hidden" name="collection_id" id="collectionId">
                    <div class="mb-3"><label class="form-label fw-bold">Tên Bộ sưu tập</label><input type="text" class="form-control" name="name" id="name" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Slug (URL)</label><input type="text" class="form-control" name="slug" id="slug"></div>
                    <div class="mb-3"><label class="form-label fw-bold">URL Hình ảnh</label><input type="text" class="form-control" name="image" id="image"></div>
                    <div class="mb-3"><label class="form-label fw-bold">Mô tả</label><textarea class="form-control" name="description" id="description" rows="3"></textarea></div>
                    <button type="submit" class="btn-navy w-100 justify-content-center"><i class="fas fa-save me-2"></i> Lưu Bộ sưu tập</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ADMIN_CONTROLLER_URL = '<?= $ADMIN_URL ?>';
</script>
<script src="<?= $BASE_URL ?>assets/js/admin-collection.js"></script> 
</body>
</html>