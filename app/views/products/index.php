<?php
$page_title = "Giỏ hàng";
include __DIR__ . "/../../layouts/header.php";
?>

<div class="container my-5">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <h2 class="mb-4 text-center">Giỏ hàng của bạn</h2>

      <?php if (empty($items)): ?>
        <div class="text-center p-5 bg-light rounded shadow-sm">
          <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
          <h4 class="text-muted">
            <?php if (!isset($_SESSION['user'])): ?>
              Bạn chưa đăng nhập
            <?php else: ?>
              Giỏ hàng trống
            <?php endif; ?>
          </h4>
          <p class="text-muted mb-4">
            <?php if (!isset($_SESSION['user'])): ?>
              Đăng nhập để xem và quản lý giỏ hàng của bạn.
            <?php else: ?>
              Hãy thêm sản phẩm để bắt đầu mua sắm!
            <?php endif; ?>
          </p>

          <div class="d-flex justify-content-center gap-3">
            <?php if (!isset($_SESSION['user'])): ?>
              <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-person"></i> Đăng nhập
              </button>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>products.php" class="btn btn-outline-success btn-lg">
              <i class="bi bi-shop"></i> Tiếp tục mua sắm
            </a>
          </div>
        </div>

      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>Hình ảnh</th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th>Xóa</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $grand_total = 0;
              foreach ($items as $item): 
                $image = json_decode($item['images'], true)[0] ?? 'no-image.jpg';
                $total = $item['price'] * $item['quantity'];
                $grand_total += $total;
              ?>
                <tr>
                  <td>
                    <img src="<?= BASE_URL ?>images/<?= $image ?>" width="60" class="rounded" alt="<?= htmlspecialchars($item['name']) ?>">
                  </td>
                  <td class="fw-bold"><?= htmlspecialchars($item['name']) ?></td>
                  <td><?= number_format($item['price']) ?>₫</td>
                  <td><?= $item['quantity'] ?></td>
                  <td class="text-danger fw-bold"><?= number_format($total) ?>₫</td>
                  <td>
                    <a href="<?= BASE_URL ?>remove_from_cart.php?id=<?= $item['cart_id'] ?>" 
                       class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-end fw-bold fs-5">Tổng cộng:</td>
                <td class="text-danger fw-bold fs-5"><?= number_format($grand_total) ?>₫</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="text-end mt-4">
          <a href="<?= BASE_URL ?>checkout.php" class="btn btn-success btn-lg px-5">
            <i class="bi bi-credit-card"></i> Thanh toán
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../../layouts/footer.php"; ?>