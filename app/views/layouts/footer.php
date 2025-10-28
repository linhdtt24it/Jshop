<!-- Footer -->
<footer class="footer">
  <div class="container footer-top">
    <div class="footer-col company-info">
      <img src="public/images/logo.png" alt="JSHOP" class="footer-logo">
      <p>© 2025 Công Ty Trang Sức JSHOP</p>
      <p>Nguồn hình ảnh: Tác giả tự sưu tầm và tổng hợp từ các nguồn trực tuyến (Unsplash, Freepik, Pinterest, Google Images, ...). Một số hình ảnh có thể được chỉnh sửa để phù hợp với nội dung trình bày.</p>
      <p>ĐT: <a href="tel:02839951703">097 XXXX XXXX</a> - Fax: <a href="tel:02839951702">036 XXXX XXXX</a></p>
      <p>Giấy CNĐKDN: <a href="#">09XXXXXXXX</a> do/////.</p>
      <p>Email: <a href="mailto:cskh@jshop.vn">cskh@jshop.vn</a></p>
      <p>Tổng đài hỗ trợ (08:00 - 21:00, miễn phí):</p>
      <p><strong>Gọi mua: <a href="tel:1800545457">1900 XXXX</a> </strong></p>
      <p><strong>Khiếu nại: <a href="tel:1800545457">1900 XXXX</a> </strong></p>
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
        <img src="public/images/visa.png" alt="Visa">
        <img src="public/images/mastercard.png" alt="Mastercard">
        <img src="public/images/jcb.png" alt="JCB">
        <img src="public/images/internetbanking.png" alt="Internet Banking">
        <img src="public/images/installment.png" alt="Trả góp">
      </div>

      <h5>CHỨNG NHẬN</h5>
      <img src="public/images/dathongbao.png" alt="Đã thông báo Bộ Công Thương" class="certify">
    </div>
  </div>
</footer>

<footer class="bg-dark text-white text-center py-3">
  <p class="mb-0">© 2025 JSHOP - All rights reserved.</p>
</footer>

<!-- JS libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Swiper setup -->
<script>
var swiper = new Swiper(".mySwiper", {
  effect: "fade",
  loop: true,
  autoplay: { delay: 4000, disableOnInteraction: false },
  fadeEffect: { crossFade: true },
  pagination: { el: ".swiper-pagination", clickable: true },
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
});
</script>

<style>
  
.footer {
  background: #fff;
  padding: 60px 0 40px;
  font-size: 15px;
  color: #333;
  border-top: 1px solid #eee;
}
.footer-top {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 40px;
}
.footer-logo {
  width: 120px;
  margin-bottom: 15px;
}
.footer h5 {
  font-size: 16px;
  font-weight: 700;
  color: #002e63;
  margin-bottom: 15px;
}
.footer a {
  color: #333;
  text-decoration: none;
}
.footer a:hover {
  color: #c2185b;
}
.footer ul {
  list-style: none;
  padding: 0;
  margin: 0 0 20px;
}
.footer ul li {
  margin-bottom: 8px;
}
.social-links a {
  font-size: 24px;
  margin-right: 12px;
  color: #333;
  transition: 0.3s;
}
.social-links a:hover {
  color: #c2185b;
}
.payment-icons img {
  height: 30px;
  margin-right: 8px;
}
.certify {
  height: 50px;
  margin-top: 10px;
}
.zalo-box {
  margin: 20px 0;
}
.zalo-btn {
  display: inline-block;
  padding: 6px 14px;
  background: #0084ff;
  color: #fff;
  border-radius: 6px;
  font-size: 14px;
  text-decoration: none;
}
.zalo-btn:hover {
  background: #006ddb;
}
@media (max-width: 768px) {
  .footer-top { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 576px) {
  .footer-top { grid-template-columns: 1fr; text-align: center; }
  .social-links a { margin: 0 8px; }
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
