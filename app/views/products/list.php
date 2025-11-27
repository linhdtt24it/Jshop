<?php 
$page_title = $category['name'] ?? "Sản phẩm"; 
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container my-5">
  <div class="row">

    <!-- SIDEBAR -->
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

              <!-- ✔️ CHÈN 3 FILTER VÀO ĐÂY — KHÔNG XÓA GÌ -->

              <select name="gender" class="form-control mb-3">
                <option value="">Giới tính</option>
                <option value="nam"     <?= ($_GET['gender'] ?? '') == 'nam' ? 'selected' : '' ?>>Nam</option>
                <option value="nu"      <?= ($_GET['gender'] ?? '') == 'nu' ? 'selected' : '' ?>>Nữ</option>
                <option value="unisex"  <?= ($_GET['gender'] ?? '') == 'unisex' ? 'selected' : '' ?>>Unisex</option>
              </select>

              <select name="material" class="form-control mb-3">
                <option value="">Chất liệu</option>
                <option value="vang"      <?= ($_GET['material'] ?? '') == 'vang' ? 'selected' : '' ?>>Vàng</option>
                <option value="bac"       <?= ($_GET['material'] ?? '') == 'bac' ? 'selected' : '' ?>>Bạc</option>
                <option value="kimcuong"  <?= ($_GET['material'] ?? '') == 'kimcuong' ? 'selected' : '' ?>>Kim cương</option>
                <option value="ngoc"      <?= ($_GET['material'] ?? '') == 'ngoc' ? 'selected' : '' ?>>Ngọc</option>
              </select>

              <select name="purpose" class="form-control mb-3">
                <option value="">Mục đích</option>
                <option value="cuoi"      <?= ($_GET['purpose'] ?? '') == 'cuoi' ? 'selected' : '' ?>>Trang sức cưới</option>
                <option value="phongthuy" <?= ($_GET['purpose'] ?? '') == 'phongthuy' ? 'selected' : '' ?>>Phong thủy</option>
                <option value="mayman"    <?= ($_GET['purpose'] ?? '') == 'mayman' ? 'selected' : '' ?>>May mắn</option>
                <option value="hoangdao"  <?= ($_GET['purpose'] ?? '') == 'hoangdao' ? 'selected' : '' ?>>Cung hoàng đạo</option>
              </select>

              <!-- ✔️ HẾT PHẦN CHÈN THÊM -->

              <button class="btn btn-primary w-100">Lọc</button>
          </form>

        </div>
      </div>
    </div>

    <!-- MAIN -->
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
