<?php
$page_title = "Giỏ hàng";
include "../app/views/layouts/header.php";
?>

<div class="container my-5">
  <h2 class="mb-4">Giỏ hàng của bạn</h2>
  <?php if ($items): ?>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Hình ảnh</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): 
            $image = json_decode($item['images'])[0] ?? 'default.jpg';
          ?>
            <tr>
              <td><img src="<?= BASE_URL ?>images/<?= $image ?>" width="60" class="rounded"></td>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= number_format($item['price']) ?>₫</td>
              <td><?= $item['quantity'] ?></td>
              <td><?= number_format($item['price'] * $item['quantity']) ?>₫</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="text-end">
      <a href="<?= BASE_URL ?>checkout.php" class="btn btn-primary btn-lg">Thanh toán</a>
    </div>
  <?php else: ?>
    <div class="alert alert-info">
      Giỏ hàng trống. <a href="<?= BASE_URL ?>products.php">Mua sắm ngay</a>
    </div>
  <?php endif; ?>
</div>

<?php include "../app/views/layouts/footer.php"; ?>