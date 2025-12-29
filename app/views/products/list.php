<?php 
$page_title = $category['name'] ?? "Sản phẩm"; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">
    <div class="col-lg-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">

          <h5 class="card-title mb-3">Lọc sản phẩm</h5>

          <form method="GET" action="">
              <input type="text" 
                     name="q" 
                     class="form-control mb-3" 
                     placeholder="Tìm kiếm..."
                     value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">

              <select name="cat" class="form-control mb-3">
                  <option value="0">Tất cả</option>
                  <?php foreach ($cats as $id => $name): ?>
                    <option value="<?= $id ?>" 
                      <?= (($_GET['cat'] ?? 0) == $id ? 'selected' : '') ?>>
                      <?= htmlspecialchars($name) ?>
                    </option>
                  <?php endforeach; ?> 
              </select>
              <select name="gender" class="form-control mb-3">
                <option value="">Giới tính</option>
                <option value="nam"     <?= ($_GET['gender'] ?? '') == 'nam' ? 'selected' : '' ?>>Nam</option>
                <option value="nu"      <?= ($_GET['gender'] ?? '') == 'nu' ? 'selected' : '' ?>>Nữ</option>
                <option value="unisex"  <?= ($_GET['gender'] ?? '') == 'unisex' ? 'selected' : '' ?>>Unisex</option>
              </select>
              <select name="material_id" class="form-control mb-3">
                  <option value="0">Chất liệu</option>
                  <?php foreach ($materials as $id => $name): ?>
                    <option value="<?= $id ?>" 
                      <?= (($_GET['material_id'] ?? 0) == $id ? 'selected' : '') ?>>
                      <?= htmlspecialchars($name) ?>
                    </option>
                  <?php endforeach; ?>
              </select>
              <select name="purpose_id" class="form-control mb-3">
                  <option value="0">Mục đích</option>
                  <?php foreach ($purposes as $id => $name): ?>
                    <option value="<?= $id ?>" 
                      <?= (($_GET['purpose_id'] ?? 0) == $id ? 'selected' : '') ?>>
                      <?= htmlspecialchars($name) ?>
                    </option>
                  <?php endforeach; ?>
              </select>
              <button class="btn btn-primary w-100">Lọc</button>
          </form>

        </div>
      </div>
    </div>
    <div class="col-lg-9">

      <h3>
        <?php if (!empty($category)): ?>
          <?= htmlspecialchars($category['name']) ?> (<?= count($products) ?> sản phẩm)
        <?php else: ?>
          Tất cả sản phẩm (<?= $total ?? count($products) ?>)
        <?php endif; ?>
      </h3>

      <div class="row g-4">

        <?php foreach ($products as $p): ?>
          <div class="col-md-4">
            <div class="card h-100 shadow-sm">

              <img src="<?= !empty($p['image']) ? $p['image'] : BASE_URL.'images/no-image.jpg' ?>"
                   class="card-img-top"
                   style="height:220px; object-fit:cover;"
                   alt="<?= htmlspecialchars($p['name']) ?>">

              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                <p class="text-danger fw-bold"><?= number_format($p['price']) ?>₫</p>

                <a href="<?= BASE_URL ?>product/detail/<?= $p['product_id'] ?>"
                   class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
              </div>

            </div>
          </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
          <p class="text-muted">Không có sản phẩm.</p>
        <?php endif; ?>

      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
