<?php 
$page_title = $product['name'] ?? 'Chi tiết sản phẩm'; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">
    <!-- CỘT ẢNH -->
    <div class="col-lg-6">
      <div class="position-sticky" style="top: 2rem;">
        <!-- Ảnh chính -->
        <img src="<?= BASE_URL.'images/'.$product['images'][0] ?? 'images/no-image.jpg' ?>" 
             class="img-fluid rounded shadow mb-3" id="mainImage" alt="<?= htmlspecialchars($product['name']) ?>">

        <!-- Ảnh phụ (thumbnail) -->
        <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
        <div class="row g-2">
          <?php foreach ($product['images'] as $img): ?>
            <div class="col-3">
              <img src="<?= BASE_URL.'images/'.$img ?>" 
                   class="img-fluid rounded border thumbnail-img" 
                   onclick="document.getElementById('mainImage').src=this.src"
                   style="cursor:pointer; height:80px; object-fit:cover;">
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CỘT THÔNG TIN -->
    <div class="col-lg-6">
      <h1 class="display-5 fw-bold"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-muted mb-1">Danh mục: <strong><?= htmlspecialchars($product['cat_name']) ?></strong></p>
      <p class="text-danger fs-3 fw-bold mb-3"><?= number_format($product['price']) ?>₫</p>

      <div class="bg-light p-4 rounded mb-4">
        <h5>Mô tả sản phẩm</h5>
        <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả.')) ?></p>
      </div>

      <!-- NÚT THÊM GIỎ HÀNG -->
      <div class="d-flex gap-3">
        <a href="<?= BASE_URL ?>cart.php?action=add&id=<?= $product['product_id'] ?>" 
           class="btn btn-success btn-lg px-5">
          <i class="bi bi-cart-plus"></i> Thêm vào giỏ
        </a>
        <a href="<?= BASE_URL ?>home.php" class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-arrow-left"></i> Tiếp tục mua
        </a>
      </div>

      <!-- THÔNG TIN BỔ SUNG -->
      <div class="mt-4 small text-muted">
        <p><strong>Mã SP:</strong> #<?= $product['product_id'] ?></p>
        <p><strong>Thương hiệu:</strong> JSHOP Premium</p>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>