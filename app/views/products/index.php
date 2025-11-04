<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sản phẩm - JSHOP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include "../../public/header.php"; ?>

  <div class="container my-5">
    <h2 class="mb-4">Tất cả sản phẩm</h2>

    <!-- Tìm kiếm -->
    <form class="mb-4">
      <div class="input-group" style="max-width: 400px;">
        <input type="text" name="q" class="form-control" placeholder="Tìm sản phẩm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-primary">Tìm</button>
      </div>
    </form>

    <div class="row">
      <?php foreach ($products as $p): ?>
        <div class="col-md-3 mb-4">
          <div class="card h-100 shadow-sm">
            <img src="../public/images/<?= $p['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="text-danger fw-bold"><?= number_format($p['price']) ?>₫</p>
              <a href="#" class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>