<?php 
$page_title = "Sản phẩm"; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">
    <!-- Sidebar lọc -->
    <div class="col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Lọc sản phẩm</h5>
          <form method="GET">
            <div class="mb-3">
              <input type="text" name="q" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="mb-3">
              <select name="cat" class="form-select">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($cats as $id => $name): ?>
                  <option value="<?= $id ?>" <?= ($cat ?? 0) == $id ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Danh sách -->
    <div class="col-lg-9">
      <h3><?= $total ?> sản phẩm</h3>
      <div class="row g-4">
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $p): ?>
            <div class="col-md-4">
              <div class="card h-100 shadow-sm">
                <img src="<?= $p['thumb'] ? BASE_URL.'images/'.$p['thumb'] : BASE_URL.'images/no-image.jpg' ?>"
                     class="card-img-top" style="height:220px; object-fit:cover;" alt="<?= htmlspecialchars($p['name']) ?>">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                  <p class="text-muted small"><?= htmlspecialchars($p['cat_name']) ?></p>
                  <p class="text-danger fw-bold fs-5"><?= number_format($p['price']) ?>₫</p>
                  <a href="<?= BASE_URL ?>products.php?action=detail&id=<?= $p['product_id'] ?>"
                     class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
      </div>

      <!-- Phân trang -->
      <?php if ($totalPages > 1): ?>
      <nav class="mt-5">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search ?? '') ?>&cat=<?= $cat ?? '' ?>">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>