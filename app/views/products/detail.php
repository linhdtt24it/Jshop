<?php 
$page_title = $product['name'] ?? 'Chi tiết sản phẩm'; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5" style="font-family: 'Inter', sans-serif;">
  <div class="row">
    
    <div class="col-lg-6">
      <div class="position-sticky" style="top: 2rem;">
        <img src="<?= $product['image'] ?>" 
             class="img-fluid rounded shadow-sm mb-3" 
             id="mainImage"
             alt="<?= htmlspecialchars($product['name']) ?>"
             style="width: 100%; object-fit: cover;">
      </div>
    </div>

    <div class="col-lg-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>product" class="text-decoration-none">Sản phẩm</a></li>
          <li class="breadcrumb-item active"><?= htmlspecialchars($product['cat_name']) ?></li>
        </ol>
      </nav>

      <h1 class="display-6 fw-bold" style="font-family: 'Playfair Display', serif;"><?= htmlspecialchars($product['name']) ?></h1>
      
      <p class="text-muted mb-3">
        Mã SP: <strong>#<?= $product['product_id'] ?></strong> | 
        Danh mục: <strong><?= htmlspecialchars($product['cat_name']) ?></strong>
      </p>

      <p class="text-danger fs-2 fw-bold mb-4">
        <?= number_format($product['price']) ?>₫
      </p>

      <div class="bg-light p-4 rounded mb-4 border-start border-4 border-dark">
        <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Mô tả sản phẩm</h5>
        <p class="mb-0 text-secondary" style="line-height: 1.6;">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Sản phẩm trang sức cao cấp thuộc bộ sưu tập mới nhất của JSHOP Luxury.')) ?>
        </p>
      </div>

      <div class="d-grid gap-3 d-md-flex">
        <button type="button" 
                class="btn btn-dark btn-lg px-5 py-3 shadow-none" 
                onclick="addToCart(<?= $product['product_id'] ?>)">
          <i class="bi bi-cart-plus me-2"></i> THÊM VÀO GIỎ HÀNG
        </button>

        <a href="<?= BASE_URL ?>product" 
           class="btn btn-outline-secondary btn-lg px-4 py-3">
          TIẾP TỤC MUA SẮM
        </a>
      </div>

      <div class="mt-5 p-3 border rounded">
        <div class="row text-center">
            <div class="col-4 border-end">
                <i class="bi bi-truck fs-3"></i>
                <p class="small mb-0">Giao nhanh 2h</p>
            </div>
            <div class="col-4 border-end">
                <i class="bi bi-shield-check fs-3"></i>
                <p class="small mb-0">Bảo hành trọn đời</p>
            </div>
            <div class="col-4">
                <i class="bi bi-arrow-repeat fs-3"></i>
                <p class="small mb-0">Đổi trả 7 ngày</p>
            </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>