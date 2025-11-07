<?php
// app/views/layouts/header.php
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'JSHOP') ?> - Trang sức cao cấp</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- SWIPER CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS CHÍNH – ĐẢM BẢO ĐƯỜNG DẪN ĐÚNG -->
    <link href="<?= BASE_URL ?>assets/css/header.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/footer.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR CHÍNH -->
<nav class="navbar navbar-expand-lg navbar-top shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">JSHOP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product">Sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>collection">Bộ sưu tập</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>news">Tin tức</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>metalprice">Giá vàng</a></li>
      </ul>
      <form class="d-flex ms-3" action="<?= BASE_URL ?>product" method="GET">
        <input class="form-control me-2" type="search" name="q" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-outline-dark" type="submit">Tìm</button>
      </form>
      <div class="d-flex align-items-center ms-3">
        <?php if (isset($user)): ?>
          <span class="me-2 text-white">Hi, <?= htmlspecialchars($user['full_name']) ?></span>
          <a href="<?= BASE_URL ?>auth/logout" class="btn btn-outline-light btn-sm">Đăng xuất</a>
        <?php else: ?>
          <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-person"></i> Đăng nhập
          </button>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>cart" class="btn btn-outline-success position-relative">
          <i class="bi bi-cart"></i> Giỏ hàng
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $cart_count ?? 0 ?>
          </span>
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- SUB MENU -->
<nav class="navbar navbar-expand-lg navbar-sub">
  <div class="container-fluid px-5">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=1">Nam giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=2">Nữ giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=3">Trang sức cưới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=4">Phong thủy</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product?category=5">Đặc biệt</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>promotion">Ưu đãi</a></li>
    </ul>
  </div>
</nav>

<!-- SLIDER -->
<?php if ($is_home ?? false): ?>
<section class="main-slider">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1624365169364-0640dd10e180?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=2070" alt=""></div>
      <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1548357194-9e1aace4e94d?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=2007" alt=""></div>
      <div class="swiper-slide"><img src="https://i.pinimg.com/736x/87/5d/24/875d24a23b8965b56d1a1427e324d6ed.jpg" alt=""></div>
      <div class="swiper-slide"><img src="https://i.pinimg.com/1200x/a0/f6/21/a0f621a3b2fa7086d44d9902e5613caf.jpg" alt=""></div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>
<?php endif; ?>

<!-- MODAL -->
<?php if (!isset($user)): ?>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Đăng nhập JSHOP</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="loginForm" action="<?= BASE_URL ?>auth/login" method="POST">
          <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label>Mật khẩu</label><input type="password" name="password" class="form-control" required></div>
          <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <div id="loginMessage" class="mt-3"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <p class="text-muted mb-0">Chưa có tài khoản? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Đăng ký ngay</a></p>
      </div>
    </div>
  </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Đăng ký JSHOP</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="registerForm" action="<?= BASE_URL ?>auth/register" method="POST">
          <div class="mb-3"><label>Họ tên</label><input type="text" name="name" class="form-control" required></div>
          <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label>Mật khẩu</label><input type="password" name="password" class="form-control" required></div>
          <div class="mb-3"><label>Nhập lại</label><input type="password" name="confirm" class="form-control" required></div>
          <button type="submit" class="btn btn-success w-100">Đăng ký</button>
        </form>
        <div id="registerMessage" class="mt-3"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <p class="text-muted mb-0">Đã có tài khoản? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Đăng nhập</a></p>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>