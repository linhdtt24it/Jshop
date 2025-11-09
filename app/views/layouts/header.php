<?php
// app/views/layouts/header.php
require_once __DIR__ . '/../../../config/constants.php';

// Đảm bảo header UTF-8
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= htmlspecialchars($page_title ?? 'JSHOP', ENT_QUOTES, 'UTF-8') ?> - Trang sức cao cấp</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- SWIPER CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <!-- GOOGLE FONTS - Đổi font hỗ trợ tốt tiếng Việt -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS CHÍNH -->
    <link href="<?= BASE_URL ?>public/assets/css/header.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>public/assets/css/footer.css" rel="stylesheet">

</head>
<body>

<!-- HEADER CHÍNH -->
<header class="header-main">
  <div class="container">
    <div class="header-top">
      <!-- Tên shop - Trên cùng bên trái -->
      <div class="shop-name">
        <a href="<?= BASE_URL ?>">JSHOP</a>
      </div>
      
      <!-- Tìm kiếm ở giữa -->
      <div class="search-center">
        <form class="search-form" action="<?= BASE_URL ?>product" method="GET">
          <input class="form-control" type="search" name="q" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <button class="btn btn-search" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>

      <!-- Đăng nhập và giỏ hàng bên phải -->
      <div class="user-actions">
        <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
          <i class="bi bi-person"></i> Đăng nhập
        </button>
        
        <a href="<?= BASE_URL ?>cart" class="btn btn-cart position-relative">
          <i class="bi bi-cart"></i> Giỏ hàng
          <?php if (($cart_count ?? 0) > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= htmlspecialchars($cart_count, ENT_QUOTES, 'UTF-8') ?>
          </span>
          <?php endif; ?>
        </a>
      </div>
    </div>
  </div>
</header>

<!-- NAVBAR CHÍNH - GIỮ NGUYÊN MENU -->
<nav class="navbar navbar-main">
  <div class="container">
    <ul class="nav main-menu">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product">Sản phẩm</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>collection">Bộ sưu tập</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>news">Tin tức</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact">Liên hệ</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>metalprice">Giá vàng</a></li>
    </ul>
  </div>
</nav>

<!-- SUB MENU - GIỮ NGUYÊN -->
<nav class="navbar navbar-sub">
  <div class="container">
    <ul class="nav sub-menu">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>men">Nam giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=2">Nữ giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=3">Trang sức cưới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=4">Phong thủy</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=5">Đặc biệt</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>promotion">Ưu đãi</a></li>
    </ul>
  </div>
</nav>

<!-- SLIDER - GIỮ NGUYÊN -->
<?php if ($is_home ?? false): ?>
<section class="main-slider">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1624365169364-0640dd10e180?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=2070" alt="Trang sức cao cấp"></div>
      <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1548357194-9e1aace4e94d?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=2007" alt="Bộ sưu tập mới"></div>
      <div class="swiper-slide"><img src="https://i.pinimg.com/736x/87/5d/24/875d24a23b8965b56d1a1427e324d6ed.jpg" alt="Trang sức phong thủy"></div>
      <div class="swiper-slide"><img src="https://i.pinimg.com/1200x/a0/f6/21/a0f621a3b2fa7086d44d9902e5613caf.jpg" alt="Ưu đãi đặc biệt"></div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>
<?php endif; ?>

<!-- NÚT BACK TO TOP -->
<button id="backToTop" class="back-to-top" aria-label="Lên đầu trang">
  <i class="bi bi-chevron-up"></i>
</button>

<!-- MODAL - GIỮ NGUYÊN -->
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Đăng nhập JSHOP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="loginForm" action="<?= BASE_URL ?>auth/login" method="POST">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <div id="loginMessage" class="mt-3"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <p class="text-muted mb-0">Chưa có tài khoản? 
          <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Đăng ký ngay</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Đăng ký JSHOP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="registerForm" action="<?= BASE_URL ?>auth/register" method="POST">
          <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="confirm" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Đăng ký</button>
        </form>
        <div id="registerMessage" class="mt-3"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <p class="text-muted mb-0">Đã có tài khoản? 
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Đăng nhập</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Back to Top Functionality
document.addEventListener('DOMContentLoaded', function() {
  const backToTopButton = document.getElementById('backToTop');
  
  // Hiển thị/nẩy nút khi scroll
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
      backToTopButton.classList.add('show');
    } else {
      backToTopButton.classList.remove('show');
    }
  });
  
  // Xử lý click để scroll lên đầu trang
  backToTopButton.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  
  // Khởi tạo Swiper (nếu có)
  <?php if ($is_home ?? false): ?>
  const swiper = new Swiper('.mySwiper', {
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    },
  });
  <?php endif; ?>
});
</script>
</body>
</html>