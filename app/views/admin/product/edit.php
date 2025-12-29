<?php

$user = [
    'name' => $_SESSION['user_name'] ?? 'Admin', 
    'role' => 'Administrator',
    'avatar' => 'https://ui-avatars.com/api/?background=000&color=d4af37&name=' . urlencode($_SESSION['user_name'] ?? 'Admin')
];

$BASE_URL = '/Jshop/public/'; 
$ADMIN_URL = '/Jshop/app/controllers/AdminController.php'; 

$product = $data['product'] ?? null;
$categories = $data['categories'] ?? [];
$materials = $data['materials'] ?? [];
$collections = $data['collections'] ?? [];
$purposes = $data['purposes'] ?? [];
$fengshui_items = $data['fengshui_items'] ?? [];

$is_editing = !empty($product);
$page_title = $is_editing ? "Chỉnh sửa Sản phẩm #{$product['product_id']}" : "Thêm Sản phẩm mới";
$action_label = $is_editing ? "Cập nhật Sản phẩm" : "Thêm mới";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> • JSHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/admin-dashboard.css">
    <style>
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .btn-navy { background-color: #0f172a; color: white !important; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-navy:hover { background-color: #1e293b; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.4); }
        .image-preview { width: 100%; height: 250px; background: #f8f8f8; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 15px; }
        .image-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
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
                <h1><?= $page_title ?></h1>
                <p>Chi tiết sản phẩm và các thuộc tính liên quan</p>
            </div>
            <div class="user-info"><img src="<?= $user['avatar'] ?>" alt="Admin"></div>
        </header>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="form-container">
                        <form id="productForm">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?? '' ?>">
                            
                            <h4 class="mb-4 text-primary">Thông tin cơ bản</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Tên Sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= $product['price'] ?? 0 ?>" min="0" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                            </div>

                            <h4 class="mb-4 mt-4 text-primary">Thuộc tính & Phân loại</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="category_id" class="form-label fw-bold">Danh mục</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">-- Chọn Danh mục --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['category_id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="material_id" class="form-label fw-bold">Chất liệu chính</label>
                                    <select class="form-select" id="material_id" name="material_id">
                                        <option value="">-- Chọn Chất liệu --</option>
                                        <?php foreach ($materials as $mat): ?>
                                            <option value="<?= $mat['material_id'] ?>" <?= (isset($product['material_id']) && $product['material_id'] == $mat['material_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($mat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="collection_id" class="form-label fw-bold">Bộ sưu tập</label>
                                    <select class="form-select" id="collection_id" name="collection_id">
                                        <option value="">-- Chọn Bộ sưu tập --</option>
                                        <?php foreach ($collections as $coll): 
                                            $coll_name = $coll['name'] ?: (str_replace('-', ' ', $coll['slug']));
                                        ?>
                                            <option value="<?= $coll['collection_id'] ?>" <?= (isset($product['collection_id']) && $product['collection_id'] == $coll['collection_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($coll_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label fw-bold">Giới tính</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <?php $selected_gender = $product['gender'] ?? 'unisex'; ?>
                                        <option value="unisex" <?= ($selected_gender == 'unisex') ? 'selected' : '' ?>>Unisex</option>
                                        <option value="nam" <?= ($selected_gender == 'nam') ? 'selected' : '' ?>>Nam</option>
                                        <option value="nu" <?= ($selected_gender == 'nu') ? 'selected' : '' ?>>Nữ</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="stock" class="form-label fw-bold">Số lượng tồn kho</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock'] ?? 0 ?>" min="0" required>
                                </div>
                            </div>
                            
                            <h4 class="mb-4 mt-4 text-primary">Thuộc tính đặc biệt</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="purpose_id" class="form-label fw-bold">Mục đích sử dụng</label>
                                    <select class="form-select" id="purpose_id" name="purpose_id">
                                        <option value="">-- Chọn Mục đích --</option>
                                        <?php foreach ($purposes as $pur): ?>
                                            <option value="<?= $pur['purpose_id'] ?>" <?= (isset($product['purpose_id']) && $product['purpose_id'] == $pur['purpose_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($pur['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fengshui_id" class="form-label fw-bold">Phong thủy/Mệnh</label>
                                    <select class="form-select" id="fengshui_id" name="fengshui_id">
                                        <option value="">-- Chọn Phong thủy --</option>
                                        <?php foreach ($fengshui_items as $fs): ?>
                                            <option value="<?= $fs['fengshui_id'] ?>" <?= (isset($product['fengshui_id']) && $product['fengshui_id'] == $fs['fengshui_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($fs['name']) ?> (<?= htmlspecialchars($fs['element']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <h4 class="mb-4 mt-4 text-primary">Hình ảnh</h4>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="image" class="form-label fw-bold">URL Hình ảnh chính</label>
                                    <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($product['image'] ?? '') ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="image-preview" id="imagePreview">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Preview">
                                        <?php else: ?>
                                            <i class="fas fa-image text-muted fs-3"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <div class="text-end">
                                <a href="<?= $ADMIN_URL ?>?action=product_list" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn-navy">
                                    <i class="fas fa-save me-2"></i> <?= $action_label ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ADMIN_CONTROLLER_URL = '<?= $ADMIN_URL ?>';

    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const imageUrl = document.getElementById('image').value;
        const previewDiv = document.getElementById('imagePreview');
        previewDiv.innerHTML = imageUrl ? `<img src="${imageUrl}" alt="Preview">` : `<i class="fas fa-image text-muted fs-3"></i>`;
        fetch(ADMIN_CONTROLLER_URL + '?action=save_product_ajax', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert(res.message);
                window.location.href = ADMIN_CONTROLLER_URL + '?action=product_list';
            } else {
                alert('Lỗi khi lưu sản phẩm: ' + res.message);
            }
        })
        .catch(err => alert('Lỗi hệ thống: Không thể kết nối đến server.'));
    });

    document.getElementById('image').addEventListener('input', function() {
        const url = this.value;
        const previewDiv = document.getElementById('imagePreview');
        previewDiv.innerHTML = url ? `<img src="${url}" alt="Preview">` : `<i class="fas fa-image text-muted fs-3"></i>`;
    });
</script>

</body>
</html>