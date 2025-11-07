<?php
// app/views/layouts/footer.php
require_once __DIR__ . '/../../../config/constants.php';
?>

<footer class="footer">
  <div class="container footer-top">
    <div class="footer-col company-info">
      <img src="<?= BASE_URL ?>images/logo.png" alt="JSHOP" class="footer-logo">
      <p>© 2025 Công Ty Trang Sức JSHOP</p>
      <p>Nguồn hình ảnh: Tác giả tự sưu tầm và tổng hợp từ các nguồn trực tuyến (Unsplash, Freepik, Pinterest, Google Images, ...). Một số hình ảnh có thể được chỉnh sửa để phù hợp với nội dung trình bày.</p>
      <p>ĐT: <a href="tel:097XXXXXXX">097 XXXX XXXX</a> - Fax: <a href="tel:036XXXXXXX">036 XXXX XXXX</a></p>
      <p>Giấy CNĐKDN: <a href="#">09XXXXXXXX</a> do/////.</p>
      <p>Email: <a href="mailto:cskh@jshop.vn">cskh@jshop.vn</a></p>
      <p>Tổng đài hỗ trợ (08:00 - 21:00, miễn phí):</p>
      <p><strong>Gọi mua: <a href="tel:1900XXXX">1900 XXXX</a></strong></p>
      <p><strong>Khiếu nại: <a href="tel:1900XXXX">1900 XXXX</a></strong></p>
    </div>

    <div class="footer-col">
      <h5>VỀ JSHOP</h5>
      <ul>
        <li><a href="#">Quan hệ cổ đông</a></li>
        <li><a href="#">Tuyển dụng</a></li>
        <li><a href="#">Xuất khẩu</a></li>
        <li><a href="#">Kinh doanh sỉ</a></li>
        <li><a href="#">Kiểm định kim cương</a></li>
        <li><a href="#">Quà tặng doanh nghiệp</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h5>DỊCH VỤ KHÁCH HÀNG</h5>
      <ul>
        <li><a href="#">Hướng dẫn đo size trang sức</a></li>
        <li><a href="#">Mua hàng trả góp</a></li>
        <li><a href="#">Hướng dẫn thanh toán</a></li>
        <li><a href="#">Cẩm nang sử dụng trang sức</a></li>
        <li><a href="#">Câu hỏi thường gặp</a></li>
      </ul>
      <h5>TỔNG HỢP CHÍNH SÁCH</h5>
      <ul>
        <li><a href="#">Chính sách hoàn tiền</a></li>
        <li><a href="#">Chính sách giao hàng</a></li>
        <li><a href="#">Chính sách đổi trả</a></li>
        <li><a href="#">Chính sách khách hàng thân thiết</a></li>
        <li><a href="#">Bảo mật thông tin</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h5>KẾT NỐI VỚI CHÚNG TÔI</h5>
      <div class="social-links">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
        <a href="#"><i class="bi bi-youtube"></i></a>
        <a href="#"><i class="bi bi-envelope"></i></a>
      </div>
      <div class="zalo-box">
        <p>Quan tâm Zalo OA JSHOP</p>
        <a href="#" class="zalo-btn">Quan tâm</a>
      </div>
      <h5>PHƯƠNG THỨC THANH TOÁN</h5>
      <div class="payment-icons">
        <img src="<?= BASE_URL ?>images/visa.png" alt="Visa">
        <img src="<?= BASE_URL ?>images/mastercard.png" alt="Mastercard">
        <img src="<?= BASE_URL ?>images/jcb.png" alt="JCB">
        <img src="<?= BASE_URL ?>images/internetbanking.png" alt="Internet Banking">
        <img src="<?= BASE_URL ?>images/installment.png" alt="Trả góp">
      </div>
      <h5>CHỨNG NHẬN</h5>
      <img src="<?= BASE_URL ?>images/dathongbao.png" alt="Đã thông báo Bộ Công Thương" class="certify">
    </div>
  </div>
</footer>

<footer class="bg-dark text-white text-center py-3">
  <p class="mb-0">© 2025 JSHOP - All rights reserved.</p>
</footer>

<!-- JS CHUNG -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- SWIPER CHỈ TRÊN TRANG CHỦ -->
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

<!-- AUTH JS -->
<script src="<?= BASE_URL ?>assets/js/auth.js"></script>

<!-- Tách AJAX ra file riêng -->
<script src="<?= BASE_URL ?>assets/js/auth.js"></script>