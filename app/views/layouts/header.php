<?php
require_once __DIR__ . '/../../../config/constants.php';
header('Content-Type: text/html; charset=UTF-8');

// Kiểm tra xem có cần hiển thị OTP modal không
$show_otp_modal = false;
if (!isset($_SESSION['user_id']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === false) {
    $show_otp_modal = true;
}

// Kiểm tra file tồn tại
$loginRegisterPath = __DIR__ . '/../auth/login_register.php';
$otpModalPath = __DIR__ . '/../auth/modal_otp.php';

$hasLoginRegister = file_exists($loginRegisterPath);
$hasOTPModal = file_exists($otpModalPath);

// DEBUG
error_log("Login Register Path: $loginRegisterPath - Exists: " . ($hasLoginRegister ? 'YES' : 'NO'));
error_log("OTP Modal Path: $otpModalPath - Exists: " . ($hasOTPModal ? 'YES' : 'NO'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= htmlspecialchars($page_title ?? 'JSHOP', ENT_QUOTES, 'UTF-8') ?> - Trang sức cao cấp</title>

    <!-- CSS -->
     <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/header.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/footer.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/login.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/otp_modal.css" rel="stylesheet">

    <!-- JS -->
    <script type="module" src="<?= BASE_URL ?>assets/js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<header class="header-main">
  <div class="container">
    <div class="header-top">
  <div class="shop-name">
    <a href="<?= BASE_URL ?>">
        <i class="bi bi-gem me-2 logo-icon"></i> 
        JSHOP
    </a>
</div>
      <div class="search-center">
        <form class="search-form" action="<?= BASE_URL ?>product" method="GET">
          <input class="form-control" type="search" name="q" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <button class="btn btn-search" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>

      <div class="user-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown">
            <button class="btn btn-user dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= BASE_URL ?>profile"><i class="bi bi-person"></i> Tài khoản</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>orders"><i class="bi bi-bag"></i> Đơn hàng</a></li>
              <li><hr class="dropdown-divider"></li>
              <a class="dropdown-item" href="/Jshop/public/logout">Đăng xuất</a>
            </ul>
          </div>
        <?php else: ?>
          <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-person"></i> Đăng nhập
          </button>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>cart" class="btn btn-cart position-relative">
        <i class="bi bi-cart"></i> Giỏ hàng
          <span id="cart-count-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= ($cart_count ?? 0) > 0 ? '' : 'd-none' ?>">
          <?= htmlspecialchars($cart_count ?? 0, ENT_QUOTES, 'UTF-8') ?>
            </span>
        </a>
      </div>
    </div>
  </div>
</header>

<!-- NAVBAR -->
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

<nav class="navbar navbar-sub">
  <div class="container">
    <ul class="nav sub-menu">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category/index/1">Nam giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category/index/2">Nữ giới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category/index/3">Trang sức cưới</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category/index/4">Phong thủy</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category/index/5">Đặc biệt</a></li>
    </ul>
  </div>
</nav>

<!-- SLIDER -->
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

<!-- MODAL LOGIN/REGISTER -->
<?php if (!isset($_SESSION['user_id'])): ?>
    <?php if ($hasLoginRegister): ?>
        <?php include_once $loginRegisterPath; ?>
    <?php else: ?>
        <div class="modal fade" id="loginModal" tabindex="-1"></div>
        <div class="modal fade" id="registerModal" tabindex="-1"></div>
    <?php endif; ?>
<?php endif; ?>

<!-- MODAL OTP (luôn có, ẩn) -->
<?php if ($hasOTPModal): ?>
    <?php include_once $otpModalPath; ?>
<?php else: ?>
    <div class="modal fade" id="otpModal" tabindex="-1"></div>
<?php endif; ?>

<!-- BACK TO TOP -->
<button id="backToTop" class="back-to-top" aria-label="Lên đầu trang">
    <i class="bi bi-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Đảm bảo hàm này nằm ở ngoài cùng, không bọc trong thẻ <script> nào khác
function addToCart(productId) {
    console.log('🛒 Đang thêm sản phẩm:', productId);
    
    // Sử dụng đường dẫn API dựa trên BASE_URL
    const apiUrl = '<?= BASE_URL ?>cart/add?id=' + productId;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) throw new Error('Kết nối thất bại');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('cart-count-badge');
                if (badge) {
                    badge.innerText = data.cart_count;
                    badge.classList.remove('d-none');
                }
                alert('Đã thêm vào giỏ hàng thành công!');
            } else {
                if (data.message && data.message.includes('đăng nhập')) {
                    alert('Vui lòng đăng nhập để mua hàng.');
                    const loginModalEl = document.getElementById('loginModal');
                    if (loginModalEl) {
                        const modal = new bootstrap.Modal(loginModalEl);
                        modal.show();
                    }
                } else {
                    alert(data.message || 'Lỗi khi thêm sản phẩm');
                }
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại sau.');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    // Logic Back to top
    const backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            backToTopButton.classList.toggle('show', window.pageYOffset > 300);
        });
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Logic Swiper cho trang chủ
    <?php if ($is_home ?? false): ?>
    new Swiper('.mySwiper', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        autoplay: { delay: 5000, disableOnInteraction: false },
        effect: 'fade'
    });
    <?php endif; ?>
});
</script>
</body>
</html>