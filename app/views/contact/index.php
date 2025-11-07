<?php
// app/views/contact/index.php
$page_title = "Liên hệ - JSHOP";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- HERO SECTION -->
<section class="contact-hero">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-4 fw-bold mb-3">Liên Hệ JSHOP</h1>
        <p class="lead mb-0">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7. Hãy để lại tin nhắn!</p>
      </div>
    </div>
  </div>
</section>

<!-- CONTENT SECTION -->
<div class="container my-5">
  <div class="row g-5">
    
    <!-- FORM LIÊN HỆ -->
    <div class="col-lg-7">
      <div class="card contact-card h-100">
        <div class="card-header bg-warning text-dark">
          <h4 class="mb-0"><i class="bi bi-chat-dots"></i> Gửi tin nhắn cho chúng tôi</h4>
        </div>
        <div class="card-body p-4">
          <form id="contactForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form-control-lg" placeholder="Nhập họ tên" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="Nhập email" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-semibold">Điện thoại</label>
              <input type="tel" name="phone" class="form-control form-control-lg" placeholder="Nhập số điện thoại">
            </div>
            
            <div class="mb-4">
              <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
              <textarea name="message" rows="6" class="form-control" placeholder="Nhập nội dung tin nhắn..." required></textarea>
              <div class="form-text">Tin nhắn của bạn sẽ được phản hồi trong vòng 24h.</div>
            </div>
            
            <button type="submit" class="btn btn-contact btn-lg w-100 text-white">
              <i class="bi bi-send"></i> Gửi tin nhắn
            </button>
          </form>
          <div id="contactMessage" class="mt-3"></div>
        </div>
      </div>
    </div>

    <!-- THÔNG TIN LIÊN HỆ -->
    <div class="col-lg-5">
      <div class="card contact-card h-100">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin liên hệ</h4>
        </div>
        <div class="card-body p-4">
          <div class="contact-info">
            <ul class="list-unstyled">
              <li class="d-flex align-items-start">
                <i class="bi bi-geo-alt text-warning me-3 mt-1"></i>
                <div>
                  <strong>Địa chỉ:</strong><br>
                  <span class="text-muted">180 Cao Lỗ, Phường 4, Quận 8, TP.HCM</span>
                </div>
              </li>
              <li class="d-flex align-items-start">
                <i class="bi bi-telephone text-primary me-3 mt-1"></i>
                <div>
                  <strong>Hotline:</strong><br>
                  <span class="text-muted">1900 1234</span>
                </div>
              </li>
              <li class="d-flex align-items-start">
                <i class="bi bi-envelope text-danger me-3 mt-1"></i>
                <div>
                  <strong>Email:</strong><br>
                  <span class="text-muted">support@jshop.vn</span>
                </div>
              </li>
              <li class="d-flex align-items-start">
                <i class="bi bi-clock text-info me-3 mt-1"></i>
                <div>
                  <strong>Giờ làm việc:</strong><br>
                  <span class="text-muted">
                    Thứ 2 - Thứ 7: 8:00 - 21:00<br>
                    Chủ nhật: 9:00 - 18:00
                  </span>
                </div>
              </li>
            </ul>
          </div>

          <hr class="my-4">

          <div class="text-center">
            <h6 class="mb-3">Theo dõi chúng tôi:</h6>
            <div class="d-flex justify-content-center gap-3">
              <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="btn btn-outline-danger btn-sm rounded-circle">
                <i class="bi bi-youtube"></i>
              </a>
              <a href="#" class="btn btn-outline-info btn-sm rounded-circle">
                <i class="bi bi-tiktok"></i>
              </a>
              <a href="#" class="btn btn-outline-dark btn-sm rounded-circle">
                <i class="bi bi-instagram"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- GOOGLE MAPS -->
  <div class="row mt-5">
    <div class="col-12">
      <div class="card contact-card">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-map"></i> Vị trí cửa hàng</h5>
        </div>
        <div class="card-body p-0">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.518987816833!2d106.62927431533437!3d10.772906992323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ea144839ef1%3A0x798819bdcd295205!2zMTgwIENhbyBMw7IsIFBoxrDhu51uZyA0LCBRdeG6rW4gOCwgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1647856789015!5m2!1svi!2s"
            width="100%" 
            height="400" 
            style="border:0; border-radius: 0 0 15px 15px;" 
            allowfullscreen="" 
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  const msg = document.getElementById('contactMessage');
  const submitBtn = this.querySelector('button[type="submit"]');
  
  // Hiển thị loading
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="bi bi-hourglass"></i> Đang gửi...';
  submitBtn.disabled = true;

  fetch('<?= BASE_URL ?>contact/send', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      msg.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> ${data.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>`;
      this.reset();
    } else {
      msg.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> ${data.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>`;
    }
  })
  .catch(() => {
    msg.innerHTML = `<div class="alert alert-danger">Lỗi kết nối máy chủ!</div>`;
  })
  .finally(() => {
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>