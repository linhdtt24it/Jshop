<?php
require_once __DIR__ . '/../../../config/constants.php';
?>

<footer class="footer">
  <div class="container footer-top">

    <div class="footer-col company-info">
      <img src="<?= BASE_URL ?>images/logo.png" alt="JSHOP" class="footer-logo">
      <p>© 2025 Công Ty Trang Sức JSHOP</p>
      <p>Nguồn hình ảnh: Tác giả tự sưu tầm và tổng hợp từ các nguồn trực tuyến.</p>
      <p>ĐT: <a href="tel:097XXXXXXX">097 XXXX XXXX</a> - Fax: <a href="tel:036XXXXXXX">036 XXXX XXXX</a></p>
      <p>Giấy CNĐKDN: <a href="#">09XXXXXXXX</a></p>
      <p>Email: <a href="mailto:cskh@jshop.vn">cskh@jshop.vn</a></p>
      <p>Tổng đài hỗ trợ (08:00 - 21:00, miễn phí):</p>
      <p><strong>Gọi mua: <a href="tel:1900XXXX">1900 XXXX</a></strong></p>
      <p><strong>Khiếu nại: <a href="tel:1900XXXX">1900 XXXX</a></strong></p>
    </div>

<?php foreach ($footer_groups ?? [] as $g): ?>
  <?php if (!empty($g['title']) && $g['title'] === 'VỀ JSHOP'): ?>
    <div class="footer-col">
      <h5><?= $g['title'] ?></h5>
      <ul>
        <?php foreach ($g['links'] ?? [] as $l): ?>
          <li><a href="<?= $l['url'] ?? '#' ?>"><?= $l['label'] ?? '' ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
<?php endforeach; ?>

<div class="footer-col">
  <?php foreach ($footer_groups ?? [] as $g): ?>
    <?php if (!empty($g['title']) && $g['title'] === 'DỊCH VỤ KHÁCH HÀNG'): ?>
      <h5><?= $g['title'] ?></h5>
      <ul>
        <?php foreach ($g['links'] ?? [] as $l): ?>
          <li><a href="<?= $l['url'] ?? '#' ?>"><?= $l['label'] ?? '' ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php foreach ($footer_groups ?? [] as $g): ?>
    <?php if (!empty($g['title']) && $g['title'] === 'TỔNG HỢP CHÍNH SÁCH'): ?>
      <h5><?= $g['title'] ?></h5>
      <ul>
        <?php foreach ($g['links'] ?? [] as $l): ?>
          <li><a href="<?= $l['url'] ?? '#' ?>"><?= $l['label'] ?? '' ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

    <div class="footer-col">
      <h5>KẾT NỐI VỚI CHÚNG TÔI</h5>
      <div class="social-links">
        <a href="https://www.facebook.com/"><i class="bi bi-facebook"></i></a>
        <a href="https://www.instagram.com/"><i class="bi bi-instagram"></i></a>
        <a href="https://www.youtube.com/"><i class="bi bi-youtube"></i></a>
        <a href="https://mail.google.com/mail/u/0/#inbox?compose=new"><i class="bi bi-envelope"></i></a>
      </div>
      <div class="zalo-box">
        <p>Quan tâm Zalo OA JSHOP</p>
        <a href="https://www.zalo.me/pc" class="zalo-btn">Quan tâm</a>
      </div>

      <h5>PHƯƠNG THỨC THANH TOÁN</h5>
      <div class="payment-icons">
        <?php foreach ($footer_assets ?? [] as $a): ?>
          <?php if (($a['type'] ?? '') === 'payment'): ?>
            <img src="<?= BASE_URL . ($a['img'] ?? '') ?>" alt="<?= $a['label'] ?? '' ?>">
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <h5>CHỨNG NHẬN</h5>
      <?php foreach ($footer_assets ?? [] as $a): ?>
        <?php if (($a['type'] ?? '') === 'certify'): ?>
          <img src="<?= BASE_URL . ($a['img'] ?? '') ?>" alt="<?= $a['label'] ?? '' ?>" class="certify">
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

  </div>
</footer>

<footer class="bg-dark text-white text-center py-3">
  <p class="mb-0">© 2025 JSHOP - All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/auth.js"></script>

<?php if ($is_home ?? false): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new Swiper(".mySwiper", {
      loop: true,
      effect: "fade",
      autoplay: { delay: 3000 },
      pagination: { el: ".swiper-pagination", clickable: true },
      fadeEffect: { crossFade: true }
    });
  });
</script>
<?php endif; ?>
