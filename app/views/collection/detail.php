<?php 
// app/views/collection/detail.php
$page_title = $collection['name'] ?? "Bộ Sưu Tập"; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">
    
    <div class="col-12 text-center mb-4">
      <h1 class="display-5 fw-bold text-dark" style="font-family: 'Playfair Display', serif;"><?= htmlspecialchars($collection['name'] ?? 'Bộ Sưu Tập') ?></h1>
      <p class="lead text-muted"><?= htmlspecialchars($collection['description'] ?? 'Các sản phẩm nổi bật thuộc bộ sưu tập này.') ?></p>
      <hr>
    </div>

    <div class="col-12">
      <h3 class="mb-4">
        <?= count($products) ?> Sản phẩm
      </h3>

      <div class="row g-4">

        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm">

                <a href="<?= BASE_URL ?>product/detail/<?= $p['product_id'] ?>" class="text-decoration-none">
                    <img src="<?= !empty($p['image']) ? $p['image'] : BASE_URL.'images/no-image.jpg' ?>"
                        class="card-img-top"
                        style="height:220px; object-fit:cover;"
                        alt="<?= htmlspecialchars($p['name']) ?>">
                </a>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title" style="font-size: 1.1rem;"><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="text-danger fw-bold fs-5 mt-auto"><?= number_format($p['price']) ?>₫</p>

                    <a href="<?= BASE_URL ?>product/detail/<?= $p['product_id'] ?>"
                       class="btn btn-outline-dark mt-2">Xem chi tiết</a>
                </div>

                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Không có sản phẩm nào trong bộ sưu tập này.</p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>