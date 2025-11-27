<?php 
$page_title = $product['name'] ?? 'Chi tiết sản phẩm'; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">
    
    <div class="col-lg-6">
      <div class="position-sticky" style="top: 2rem;">

        <img src="<?= $product['image'] ?>" 
             class="img-fluid rounded shadow mb-3" 
             id="mainImage"
             alt="<?= htmlspecialchars($product['name']) ?>">

      </div>
    </div>

    <div class="col-lg-6">
      <h1 class="display-5 fw-bold"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-muted mb-1">
        Danh mục: <strong><?= htmlspecialchars($product['cat_name']) ?></strong>
      </p>

      <p class="text-danger fs-3 fw-bold mb-3">
        <?= number_format($product['price']) ?>₫
      </p>

      <div class="bg-light p-4 rounded mb-4">
        <h5>Mô tả sản phẩm</h5>
        <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả.')) ?></p>
      </div>

      <div class="d-flex gap-3">
        <a href="<?= BASE_URL ?>cart.php?action=add&id=<?= $product['product_id'] ?>" 
           class="btn btn-success btn-lg px-5">
          <i class="bi bi-cart-plus"></i> Thêm vào giỏ
        </a>

        <a href="<?= BASE_URL ?>home.php" 
           class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-arrow-left"></i> Tiếp tục mua
        </a>
      </div>

      <div class="mt-4 small text-muted">
        <p><strong>Mã SP:</strong> #<?= $product['product_id'] ?></p>
        <p><strong>Thương hiệu:</strong> JSHOP Premium</p>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
