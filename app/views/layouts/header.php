<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JSHOP</title>

  <!-- Bootstrap & Swiper -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      background-color: #fff8f0; /* nền trắng ngà nhẹ */
      color: #4b3b2a; /* chữ màu nâu đậm */
    }

    /* 🔹 Thanh trên */
    .navbar-top {
      background-color: #966d45ff !important; /* nâu sáng */
    }
    .navbar-top .nav-link,
    .navbar-top a,
    .navbar-top span,
    .navbar-top .navbar-brand {
      color: #fff !important; /* 🌸 chữ trắng */
      font-size: 1.0rem;      /* 🌸 chữ lớn hơn */
      font-weight: 600;       /* đậm hơn một chút */
      text-transform: uppercase; /* viết hoa toàn bộ (tuỳ thích) */
      letter-spacing: 0.5px;  /* giãn nhẹ chữ */
    }

    .navbar-top .nav-link:hover,
    .navbar-top a:hover {
      color: #f9d9b4 !important; /* vàng nhạt khi hover cho nổi bật */
    }

    /* 🔹 Menu phụ */
    .navbar-sub {
      background-color: #f5efe6 !important; /* trắng ngà */
      border-top: 1px solid #d4bfa3; /* nâu nhạt */
      width: 100%;
      padding: 8px 0;
      position: relative;
      z-index: 10;
    }

    .navbar-sub .navbar-nav {
      width: 100%;
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
    }

    .navbar-sub .nav-link {
      color: #6b4c34; /* nâu đậm */
      font-weight: 600;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .navbar-sub .nav-link:hover {
      color: #fff; /* chữ trắng khi hover */
      background-color: #a67c52; /* nền nâu */
      border-radius: 20px;
      padding: 6px 14px;
    }

    /* === 🌸 MAIN SLIDER === */
    .main-slider {
      width: 100%;
      height: 75vh;
      margin: 0;
      padding: 0;
      overflow: hidden;
      position: relative;
      z-index: 1;
    }

    .swiper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #4b3b2a; /* nâu tối làm nền slide */
    }

    .swiper-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      transition: transform 0.6s ease, filter 0.6s ease;
      filter: brightness(95%) contrast(105%) saturate(110%);
    }

    .swiper-slide-active img {
      transform: scale(1.05);
      filter: brightness(100%) contrast(110%);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .main-slider { height: 70vh; }
    }
    @media (max-width: 576px) {
      .main-slider { height: 60vh; }
    }
</style>

</head>

<body>

<!-- 🔝 HEADER -->
<nav class="navbar navbar-expand-lg navbar-top shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="#">JSHOP</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Bộ sưu tập</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Tin tức</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
      </ul>

      <form class="d-flex ms-3" role="search">
        <input class="form-control me-2" type="search" placeholder="Tìm kiếm...">
        <button class="btn btn-outline-dark" type="submit">Tìm</button>
      </form>

      <div class="d-flex align-items-center ms-3">
        <a href="#" class="btn btn-outline-primary me-2">
          <i class="bi bi-person"></i> Đăng nhập
        </a>
        <a href="#" class="btn btn-outline-success position-relative">
          <i class="bi bi-cart"></i> Giỏ hàng
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- 🔸 MENU PHỤ -->
<nav class="navbar navbar-expand-lg navbar-sub">
  <div class="container-fluid px-5">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="#">Nam giới</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Nữ giới</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Trang sức cưới</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Trang sức phong thủy</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Trang sức đặc biệt</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Ưu đãi</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Giá vàng hiện nay</a></li>
    </ul>
  </div>
</nav>

<!-- 🌸 MAIN SLIDER -->
<section class="main-slider">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1624365169364-0640dd10e180?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=2070" alt="Slider 1">
      </div>
      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1548357194-9e1aace4e94d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=2007" alt="Slider 2">
      </div>
      <div class="swiper-slide">
        <img src="https://i.pinimg.com/736x/87/5d/24/875d24a23b8965b56d1a1427e324d6ed.jpg" alt="Slider 3">
      </div>
      <div class="swiper-slide">
        <img src="https://i.pinimg.com/1200x/a0/f6/21/a0f621a3b2fa7086d44d9902e5613caf.jpg" alt="Slider 4">
      </div>
    </div>

    <div class="swiper-pagination"></div>
  </div>
</section>


<!-- Bootstrap + Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    effect: "fade",
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    fadeEffect: { crossFade: true },
  });
</script>

</body>
</html>
